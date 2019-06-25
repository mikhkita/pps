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
		// $className = "Order";

		// $model = new $className();

		// echo "<pre>";
		// var_dump($model);
		// echo "</pre>";
		// if( !$partial ){
		// 	$this->layout = "admin";
		// 	$this->pageTitle = $this->adminMenu["cur"]->name;
		// }

  //       $filter = new Order('filter');

		// if (isset($_GET['Order'])){
  //           $filter->attributes = $_GET['Order'];
  //       }

  //       $dataProvider = $filter->search(50);
		// $count = $filter->search(50, true);

		// $params = array(
		// 	"data" => $dataProvider->getData(),
		// 	"pages" => $dataProvider->getPagination(),
		// 	"filter" => $filter,
		// 	"count" => $count,
		// 	"labels" => Order::attributeLabels(),
		// );

		if( !$partial ){
			$this->render("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionAdminCreate()
	{
		$model = new Order;

		if(isset($_POST["Order"])) {
			if( $model->updateObj($_POST["Order"]) ){
				$this->actionAdminIndex();
				return true;
			}
		} else {
			$this->render("adminCreate",array(
				"model" => $model,
				"person" => new Person
			));
		}
	}

	public function actionAdminUpdate($id)
	{
		$model = $this->loadModel($id);

		if(isset($_POST["Order"])) {
			if( $model->updateObj($_POST["Order"]) ){
				$this->actionAdminIndex();
				return true;
			}
		}else{
			$this->renderPartial("adminUpdate",array(
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
		$model = Order::model()->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
