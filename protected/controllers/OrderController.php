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

        $filter = new Order('filter');

		if (isset($_GET['Order'])){
            $filter->attributes = $_GET['Order'];
        }

        if( Yii::app()->user->checkAccess('director') ){
        	$filter->agency_id = $this->user->agency_id;
        }else if( Yii::app()->user->checkAccess('manager') ){
        	$filter->user_id = Yii::app()->user->id;
        }

        $dataProvider = $filter->search(50);
		$count = $filter->search(50, true);

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
