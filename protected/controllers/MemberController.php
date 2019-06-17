<?php

class MemberController extends Controller
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
				"roles" => array("read"),
			),
			array("allow",
				"actions" => array("adminUpdate", "adminDelete", "adminCreate"),
				"roles" => array("update"),
			),
			array("deny",
				"users" => array("*"),
			),
		);
	}

	public function actionAdminIndex($company_id, $partial = false){
		unset($_GET["partial"]);
		if( !$partial ){
			$this->layout = "admin";
			$this->pageTitle = $this->adminMenu["cur"]->name;
		}

		$model = $this->loadCompany($company_id);

        $memberFilter = new Member('filter');
        $memberFilter->company_id = $company_id;

        $directorFilter = new Director('filter');
        $directorFilter->company_id = $company_id;

		$params = array(
			"members" => $memberFilter->search(1000)->getData(),
			"directors" => $directorFilter->search(1000)->getData(),
			"memberLabels" => Member::attributeLabels(),
			"directorLabels" => Director::attributeLabels(),
			"company" => $model,
		);

		if( !$partial ){
			$this->render("adminIndex", $params);
		}else{
			$this->renderPartial("adminIndex", $params);
		}
	}

	public function actionAdminCreate($director = false)
	{
		$modelName = ($director)?("Director"):("Member");

		$model = ($director)?(new Director):(new Member);

		if(isset($_POST[$modelName])) {
			if( $model->updateObj($_POST[$modelName]) ){
				$this->actionAdminIndex($model->company_id, true);
				return true;
			}
		} else {
			$this->renderPartial(strtolower($modelName)."/adminCreate",array(
				"model" => $model
			));
		}
	}

	public function actionAdminUpdate($id, $director = false)
	{
		$modelName = ($director)?("Director"):("Member");

		$model = $this->loadModel($id, $director);

		if(isset($_POST[$modelName])) {
			if( $model->updateObj($_POST[$modelName]) ){
				$this->actionAdminIndex($model->company_id, true);
				return true;
			}
		}else{
			$this->renderPartial(strtolower($modelName)."/adminUpdate",array(
				"model" => $model,
			));
		}
	}

	public function actionAdminDelete($id, $director = false)
	{
		$model = $this->loadModel($id, $director);

		$company_id = $model->company_id;

		$model->delete();

		$this->actionAdminindex($company_id, true);
	}

	public function loadModel($id, $director = false)
	{
		if( $director ){
			$model = Director::model()->findByPk($id);
		}else{
			$model = Member::model()->findByPk($id);
		}

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}

	public function loadCompany($id)
	{
		$model = Company::model()->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
