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
				"actions" => array("adminIndex"),
				"roles" => array("readUser"),
			),
			array("allow",
				"actions" => array("adminUpdate", "adminDelete", "adminCreate"),
				"roles" => array("updateUser"),
			),
			array("deny",
				"users" => array("*"),
			),
		);
	}

	public function actionAdminIndex($partial = false){
		unset($_GET["partial"]);

		if( !$partial ){
			$this->layout = "admin";
			$this->pageTitle = $this->adminMenu["cur"]->name;
		}

        $filter = new Payment('filter');

		if (isset($_GET['Payment'])){
            $filter->attributes = $_GET['Payment'];
        }

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
			$this->render("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionAdminCreate($type)
	{
		$persons = array(
			24,
			25,
			26,
			27,
			28,
		);

		if( !count( $persons ) ){
			throw new CHttpException(404, "Passengers are not selected");
		}

		$model = Person::model()->findAll(array(
			"condition" => "id IN (".implode(",", $persons).")",
			"order" => "order_id DESC"
		));

		if( !$model ){

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
		}

		

		// if(isset($_POST["Payment"])) {
		// 	$result = $model->updateObj($_POST["Payment"], $_POST["Person"]);
		// 	if( $result->result ){
		// 		Controller::returnSuccess( array(
		// 			"action" => "redirectDelay",
		// 			"href" => Yii::app()->createUrl("/payment/adminIndex")
		// 		) );
		// 	}else{
		// 		Controller::returnError("Ошибка: ".$result->message);
		// 	}
		// } else {
		// 	$this->render("adminCreate",array(
		// 		"model" => $payment,
		// 		"persons" => $persons,
		// 		"directions" => Person::model()->directions,
		// 	));
		// }
	}

	public function actionAdminUpdate($id)
	{
		$payment = $this->loadModel($id);

		if(isset($_POST["Payment"])) {
			if( $model->updateObj($_POST["Payment"], $_POST["Person"]) ){
				$this->actionAdminIndex();
				return true;
			}
		}else{
			$this->render("adminUpdate",array(
				"payment" => $payment
			));
		}
	}

	public function actionAdminDelete($id)
	{
		$this->loadModel($id)->delete();

		$this->actionAdminindex(true);
	}

	public function loadModel($id)
	{
		$model = Payment::model()->with("persons")->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
