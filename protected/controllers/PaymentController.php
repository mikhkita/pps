<?php

class PaymentController extends Controller
{
	public function filters()
	{
		return array(
			"accessControl"
		);
	}

	public function accessRules()
	{
		return array(
			array("allow",
				"actions" => array("index"),
				"roles" => array("readPayment"),
			),
			array("allow",
				"actions" => array("update", "delete", "create"),
				"roles" => array("updatePayment"),
			),
			array("allow",
				"actions" => array("callback"),
				"users" => array("*"),
			),
			array("deny",
				"users" => array("*"),
			),
		);
	}

	public function actionCallback($payment_id, $orderId, $success)
	{
		$payment = Payment::model()->findByPk($payment_id);
		// var_dump($_GET);
		// die();

		if( $payment ){
			$payment->status_id = ( $success == "1" )?4:6;
			if( $success == "1" ){
				$payment->transaction = $orderId;
			}
			$payment->save();

			$this->redirect( Yii::app()->createUrl("/payment/update", array("id" => $payment_id)) );
		}else{
			echo "Ошибка оплаты. Не найден платеж";
			var_dump($_GET);
			die();
		}
	}

	public function actionIndex($partial = false){
		unset($_GET["partial"]);

		// $number = Payment::getNextBillNumber();
		// var_dump($number);

		if( !$partial ){
			$this->layout = "admin";
			$this->pageTitle = $this->adminMenu["cur"]->name;
		}

        $filter = new Payment('filter');

		if (isset($_GET['Payment'])){
            $filter->attributes = $_GET['Payment'];
        }

        Controller::accessFilter($filter);

        $dataProvider = $filter->search(50);
		$count = $filter->search(50, true);

		$params = array(
			"data" => $dataProvider->getData(),
			"pages" => $dataProvider->getPagination(),
			"filter" => $filter,
			"count" => $count,
			"labels" => Payment::attributeLabels(),
		);

		if( !$partial ){
			$this->render("index".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("index".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionCreate($type)
	{
		$persons = explode(",", $_POST["ids"]);

		if( !count( $persons ) ){
			throw new CHttpException(404, "Passengers are not selected");
		}

		$model = Person::model()->findAll(array(
			"condition" => "id IN (".implode(",", $persons).")",
			"order" => "order_id DESC"
		));

		if( !$model ){
			throw new CHttpException(404, "Passengers are not found");
		}

		$payment = new Payment;

		if( $payment->updateObj(array( 
			"user_id" => Yii::app()->user->id, 
			"type_id" => intval($type) 
		)) ){
			foreach ($model as $key => $person) {
				$tmp = new PaymentPerson;

				$tmp->payment_id = $payment->id;
				$tmp->person_id = $person->id;
				$tmp->direction_id = $person->direction_id;
				$tmp->sum = $person->price_without_commission;

				$tmp->save();
			}
			Controller::returnSuccess( array(
				"action" => "redirect",
				"href" => Yii::app()->createUrl("/payment/update", array("id" => $payment->id)),
			) );
		}else{
			Controller::returnError("Ошибка создания платежа");
		}
	}

	public function actionUpdate($id)
	{
		$payment = $this->loadModel($id);

		if( isset($_POST["PaymentPerson"]) && $payment->isEditable() ) {
			$paymentPersons = $_POST["PaymentPerson"];

			if( count($paymentPersons) ){
				foreach ($payment->persons as $key => $person) {
					$paymentPerson = $paymentPersons[ $person->person_id ];
					if( isset($paymentPerson) ){
						if( isset($paymentPerson["sum"]) ){
							$person->sum = $paymentPerson["sum"];
						}

						if( isset($paymentPerson["direction_id"]) ){
							$person->direction_id = $paymentPerson["direction_id"];
						}

						$person->save();
					}
				}

				switch ($payment->type_id) {
					case 1:
						$payment->status_id = 2;
						$payment->save();

						$sberbank = new Sberbank();

						$description = "Оплата билетов";
						$jsonParams = array(
							"payment_id" => $payment->id
						);

						$result = (object) $sberbank->requestTicket($payment->id, $payment->getTotalSum(), $description, $jsonParams );

						if( $result->status == "success" ){
							Controller::returnSuccess( array(
								"action" => "redirect",
								"href" => $result->url,
							) );
						}else{
							Controller::returnError("Ошибка: ". $result->errorMessage);
						}
						break;
					case 2:
						$payment->status_id = 3;
						$payment->number = $payment->getNextBillNumber();
						$payment->save();

						Controller::returnSuccess( array(
							"action" => "showPopup",
							"sum" => number_format( $payment->getTotalSum(), 0, ',', '&nbsp;' )." руб.",
							"date" => Controller::getRusDate($payment->date),
							"number" => $payment->prefix.$payment->number,
							"message" => "Счет успешно создан",
						) );
						break;
				}
			}else{
				Controller::returnError("Ошибка: не выбраны пассажиры");
			}
		}else{
			$title = $payment->getTitle();

			$this->render("update",array(
				"payment" => $payment,
				"title" => $title
			));
		}
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		$this->actionIndex(true);
	}

	public function loadModel($id)
	{
		$model = Payment::model()->with("persons")->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
