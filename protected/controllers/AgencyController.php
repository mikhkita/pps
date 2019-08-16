<?php

class AgencyController extends Controller
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
				"roles" => array("readAgency"),
			),
			array("allow",
				"actions" => array("update"),
				"roles" => array("updateAgency"),
			),
			array("allow",
				"actions" => array("delete", "create"),
				"roles" => array("root"),
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

        $filter = new Agency('filter');

		if (isset($_GET['Agency'])){
            $filter->attributes = $_GET['Agency'];
        }

        $dataProvider = $filter->search(50);
		$count = $filter->search(50, true);

		$params = array(
			"data" => $dataProvider->getData(),
			"pages" => $dataProvider->getPagination(),
			"filter" => $filter,
			"count" => $count,
			"labels" => Agency::attributeLabels(),
		);

		if( !$partial ){
			$this->render("index".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("index".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionCreate()
	{
		$model = new Agency;

		if(isset($_POST["Agency"])) {
			if( $model->updateObj($_POST["Agency"]) ){
				$this->actionIndex(true);
				return true;
			}
		} else {
			$this->renderPartial("create",array(
				"model" => $model
			));
		}
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if(isset($_POST["Agency"])) {
			if( $model->updateObj($_POST["Agency"]) ){
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
		$model = Agency::model()->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
