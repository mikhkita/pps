<?php

class DictionaryController extends Controller
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
				"actions" => array("adminIndex", "adminList"),
				"roles" => array("readDictionary"),
			),
			array("allow",
				"actions" => array("adminUpdate", "adminDelete", "adminCreate"),
				"roles" => array("updateDictionary"),
			),
			array("deny",
				"users" => array("*"),
			),
		);
	}

	public function actionAdminIndex($partial = false){
		unset($_GET["partial"]);

		$models = ModelNames::model()->findAll(array("order" => "t.sort ASC", "condition" => "parent_id = '23'"));

		$params = array(
			"data" => $models,
			"count" => count( $models ),
		);

		if( !$partial ){
			$this->render("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionAdminList($partial = false, $class = NULL){
		unset($_GET["partial"]);

		$className = trim($class);

		if( !$className || !class_exists($className) ){
			throw new CHttpException(404, "Class «".$className."» is not defined");
		}

        $filter = new $className('filter');

        if( !$filter->isDictionary ){
			throw new CHttpException(404, "Class «".$className."» is not dictionary");
		}

		if (isset($_GET[ $className ])){
            $filter->attributes = $_GET[ $className ];
        }

        $dataProvider = $filter->search(50);
		$count = $filter->search(50, true);

		$params = array(
			"data" => $dataProvider->getData(),
			"pages" => $dataProvider->getPagination(),
			"filter" => $filter,
			"count" => $count,
			"labels" => $className::attributeLabels(true),
		);

		if( !$partial ){
			$this->render("adminList".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("adminList".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionAdminCreate($class = NULL)
	{
		$className = trim($class);

		if( !$className || !class_exists($className) ){
			throw new CHttpException(404, "Class «".$className."» is not defined");
		}

		$model = new $className();

		if( !$model->isDictionary ){
			throw new CHttpException(404, "Class «".$className."» is not dictionary");
		}

		$fields = $className::attributeLabels(true);

		foreach ($fields as $key => $label) {
			if( $key == "id" ){
				unset($fields[$key]);
			}
		}

		if(isset($_POST[ $className ])) {
			if( $model->updateObj($_POST[ $className ]) ){
				$this->actionAdminList(true, $_GET["class"]);
				return true;
			}
		} else {
			$this->renderPartial("adminCreate",array(
				"model" => $model,
				"fields" => $fields
			));
		}
	}

	public function actionAdminUpdate($id, $class = NULL)
	{
		$className = trim($class);

		if( !$className || !class_exists($className) ){
			throw new CHttpException(404, "Class «".$className."» is not defined");
		}

		$model = $this->loadModel($id, $className);

		if( !$model->isDictionary ){
			throw new CHttpException(404, "Class «".$className."» is not dictionary");
		}

		$fields = $className::attributeLabels(true);

		foreach ($fields as $key => $label) {
			if( $key == "id" ){
				unset($fields[$key]);
			}
		}

		if(isset($_POST[ $className ])) {
			if( $model->updateObj($_POST[ $className ]) ){
				$this->actionAdminList(true, $_GET["class"]);
				return true;
			}
		} else {
			$this->renderPartial("adminUpdate",array(
				"model" => $model,
				"fields" => $fields
			));
		}
	}

	public function actionAdminDelete($id, $class = NULL)
	{
		$className = trim($class);

		if( !$className || !class_exists($className) ){
			throw new CHttpException(404, "Class «".$className."» is not defined");
		}

		$model = $this->loadModel($id, $className);

		if( !$model->isDictionary ){
			throw new CHttpException(404, "Class «".$className."» is not dictionary");
		}

		$model->delete();

		$this->actionAdminList(true, $_GET["class"]);
	}

	public function loadModel($id, $className)
	{
		$model = $className::model()->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
