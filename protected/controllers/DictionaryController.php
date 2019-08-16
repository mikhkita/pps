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
				"actions" => array("index", "list"),
				"roles" => array("readDictionary"),
			),
			array("allow",
				"actions" => array("update"),
				"roles" => array("updateDictionary"),
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

		$models = ModelNames::model()->findAll(array("order" => "t.sort ASC", "condition" => "parent_id = '23'"));

		$params = array(
			"data" => $models,
			"count" => count( $models ),
		);

		if( !$partial ){
			$this->render("index".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("index".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionList($partial = false, $class = NULL){
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
			$this->render("list".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("list".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionCreate($class = NULL)
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
				$this->actionList(true, $_GET["class"]);
				return true;
			}
		} else {
			$this->renderPartial("create",array(
				"model" => $model,
				"fields" => $fields
			));
		}
	}

	public function actionUpdate($id, $class = NULL)
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
				$this->actionList(true, $_GET["class"]);
				return true;
			}
		} else {
			$this->renderPartial("update",array(
				"model" => $model,
				"fields" => $fields
			));
		}
	}

	public function actionDelete($id, $class = NULL)
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

		$this->actionList(true, $_GET["class"]);
	}

	public function loadModel($id, $className)
	{
		$model = $className::model()->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
