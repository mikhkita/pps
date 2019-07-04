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
				"actions" => array("adminIndex"),
				"roles" => array("readOrder"),
			),
			array("allow",
				"actions" => array("adminUpdate", "adminDelete", "adminCreate"),
				"roles" => array("updateOrder"),
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
			$this->render("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionAdminCreate()
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
					"href" => Yii::app()->createUrl("/order/adminIndex"),
					"message" => "Заявка успешно отправлена"
				) );
			}else{
				Controller::returnError("Ошибка: ".$result->message);
			}
		} else {
			$this->render("adminCreate",array(
				"model" => $model,
				"person" => new Person,
				"default_payment_type_id" => ($this->user->agency->default_payment_type_id)?$this->user->agency->default_payment_type_id:1
			));
		}
	}

	public function actionAdminUpdate($id)
	{
		$model = $this->loadModel($id);

		if(isset($_POST["Person"])) {
			$result = $model->updateObj(NULL, $_POST["Person"]);
			if( $result->result ){
				Controller::returnSuccess( array(
					"action" => "redirectDelay",
					"href" => Yii::app()->createUrl("/order/adminIndex"),
					"message" => "Заявка успешно сохранена"
				) );
			}else{
				Controller::returnError("Ошибка: ".$result->message);
			}
		}else{
			$this->render("adminUpdate",array(
				"model" => $model,
				"person" => new Person
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
		$model = Order::model()->with("persons")->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
