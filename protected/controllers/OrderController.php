<?php

class OrderController extends Controller
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
				"roles" => array("readOrder"),
			),
			array("allow",
				"actions" => array("update", "delete", "create"),
				"roles" => array("updateOrder"),
			),
			array("deny",
				"users" => array("*"),
			),
		);
	}

	public function actionIndex($partial = false){
		unset($_GET["partial"]);

		if( !$partial ){
			$this->layout = "admin";
			$this->pageTitle = $this->adminMenu["cur"]->name;
		}

		// $mailer = new Mailer();
		// $mailer->send();

        $filter = new Order('filter');

		if (isset($_GET['Order'])){
            $filter->attributes = $_GET['Order'];
        }

        Controller::accessFilter($filter);

        $dataProvider = $filter->search(20);
		$count = $filter->search(20, true);

		$params = array(
			"data" => $dataProvider->getData(),
			"pages" => $dataProvider->getPagination(),
			"filter" => $filter,
			"count" => $count,
			"labels" => Person::attributeLabels(),
		);

		if( !$partial ){
			$this->render("index".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("index".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionCreate()
	{
		$model = new Order;

		if( $this->user->agency && $this->user->agency->default_start_point_id ){
			$model->start_point_id = $this->user->agency->default_start_point_id;
		}

		if(isset($_POST["Order"])) {
			$result = $model->updateObj($_POST["Order"], $_POST["Person"]);
			if( $result->result ){
				Controller::returnSuccess( array(
					"action" => "redirectDelay",
					"href" => Yii::app()->createUrl("/order/index"),
					"message" => "Заявка успешно отправлена"
				) );
			}else{
				Controller::returnError("Ошибка: ".$result->message);
			}
		} else {
			$this->render("create",array(
				"model" => $model,
				"person" => new Person,
				"default_payment_type_id" => ($this->user->agency->default_payment_type_id)?$this->user->agency->default_payment_type_id:1
			));
		}
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if(isset($_POST["Person"])) {
			$result = $model->updateObj(NULL, $_POST["Person"]);
			if( $result->result ){
				Controller::returnSuccess( array(
					"action" => "redirectDelay",
					"href" => Yii::app()->createUrl("/order/index"),
					"message" => "Заявка успешно сохранена"
				) );
			}else{
				Controller::returnError("Ошибка: ".$result->message);
			}
		}else{
			$this->render("update",array(
				"model" => $model,
				"person" => new Person
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
		$model = Order::model()->with("persons")->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
