<?php

class BackController extends Controller
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

	// public function actionAdminIndex($partial = false){
	// 	unset($_GET["partial"]);
	// 	if( !$partial ){
	// 		$this->layout = "admin";
	// 		$this->pageTitle = $this->adminMenu["cur"]->name;
	// 	}

 //        $filter = new Back('filter');

	// 	if (isset($_GET['Back'])){
 //            $filter->attributes = $_GET['Back'];
 //        }

 //        $dataProvider = $filter->search(50);
	// 	$count = $filter->search(50, true);

	// 	$params = array(
	// 		"data" => $dataProvider->getData(),
	// 		"pages" => $dataProvider->getPagination(),
	// 		"filter" => $filter,
	// 		"count" => $count,
	// 		"labels" => Back::attributeLabels(),
	// 	);

	// 	if( !$partial ){
	// 		$this->render("adminIndex".(($this->isMobile)?"Mobile":""), $params);
	// 	}else{
	// 		$this->renderPartial("adminIndex".(($this->isMobile)?"Mobile":""), $params);
	// 	}
	// }

	public function actionCreate()
	{
		$back = new Back();

		$persons = explode(",", $_GET["ids"]);

		if( !count( $persons ) ){
			throw new CHttpException(404, "Passengers are not selected");
		}

		$persons = Person::model()->findAll(array(
			"condition" => "id IN (".implode(",", $persons).")",
			"order" => "order_id DESC",
		));

		if( !$persons ){
			throw new CHttpException(404, "Passengers are not found");
		}

		if(isset($_POST["Back"])) {
			$result = $back->updateObj($_POST["Back"], $_POST["BackPerson"]);
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
				"back" => $back,
				"persons" => $persons,
			));
		}
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if(isset($_POST["Back"])) {
			if( $model->updateObj($_POST["Back"]) ){
				$this->actionIndex(true);
				return true;
			}
		}else{
			$this->renderPartial("update",array(
				"model" => $model,
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
		$model = Back::model()->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
