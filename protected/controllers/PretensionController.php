<?php

class PretensionController extends Controller
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
				"actions" => array("adminIndex", "adminView"),
				"roles" => array("readAll"),
			),
			array("allow",
				"actions" => array("adminUpdate", "adminDelete", "adminCreate"),
				"roles" => array("updateAll"),
			),
			array("deny",
				"users" => array("*"),
			),
		);
	}

	public function actionAdminIndex($partial = false, $archive = 0){
		unset($_GET["partial"]);
		if( !$partial ){
			$this->layout = "admin";
			$this->pageTitle = $this->adminMenu["cur"]->name;
		}

        $filter = new Pretension('filter');

		if (isset($_GET['Pretension'])){
            $filter->attributes = $_GET['Pretension'];
        }
        $filter->section_id = Controller::getIds($this->user->sections, "section_id");
        $filter->archive = $archive;

        $dataProvider = $filter->search(256);
		$count = $filter->search(256, true);

		$data = $dataProvider->getData();

		$data = Pretension::getBySections($data);

		$params = array(
			"data" => $data,
			"pages" => $dataProvider->getPagination(),
			"filter" => $filter,
			"count" => $count,
			"labels" => Pretension::attributeLabels(),
			"archive" => $archive
		);

		if( !$partial ){
			$this->render("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionAdminView($id, $partial = false)
	{
		$model = Pretension::model()->with(array("notes"))->findByPk($id);

		$params = array(
			"model" => $model,
			"labels" => Pretension::attributeLabels(),
			"noteLabels" => Note::attributeLabels(),
		);

		if( $partial ){
			$this->renderPartial("adminView".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->render("adminView".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionAdminCreate($section_id, $archive = 0)
	{
		$model = new Pretension;
		$section = Section::model()->findByPk($section_id);

		$model->section_id = $section_id;
		$model->manager_id = $this->user->id;

		if(isset($_POST["Pretension"])) {
			if( $model->updateObj($_POST["Pretension"]) ){
				Stakeholder::model()->deleteAll("item_id = '".$model->id."' AND type_id = '1'");
				
				if( isset($_POST["Stakeholders"]) ){
					foreach ($_POST["Stakeholders"] as $key => $stakeholderId) {
						$stakeholder = new Stakeholder();
						$stakeholder->type_id = "1";
						$stakeholder->item_id = $model->id;
						$stakeholder->user_id = $stakeholderId;
						$stakeholder->save();
					}
				}
				$this->actionAdminIndex(true, $archive);
				return true;
			}
		} else {
			$this->renderPartial("adminCreate",array(
				"model" => $model,
				"section" => $section
			));
		}
	}

	public function actionAdminUpdate($id, $archive = 0)
	{
		$model = $this->loadModel($id);

		$_GET["section_id"] = $model->section_id;

		$stakeholders = $this->getIds($model->stakeholders, "user_id");

		if(isset($_POST["Pretension"])) {
			Stakeholder::model()->deleteAll("item_id = '".$model->id."' AND type_id = '1'");
			if( isset($_POST["Stakeholders"]) ){
				foreach ($_POST["Stakeholders"] as $key => $stakeholderId) {
					$stakeholder = new Stakeholder();
					$stakeholder->type_id = "1";
					$stakeholder->item_id = $model->id;
					$stakeholder->user_id = $stakeholderId;
					$stakeholder->save();
				}
			}

			if( $model->updateObj($_POST["Pretension"]) ){
				$this->actionAdminIndex(true, $archive);
				return true;
			}
		}else{
			$this->renderPartial("adminUpdate",array(
				"model" => $model,
				"stakeholders" => $stakeholders
			));
		}
	}

	public function actionAdminDelete($id, $archive = 0)
	{
		$this->loadModel($id)->delete();

		$this->actionAdminindex(true, $archive);
	}

	public function loadModel($id)
	{
		$model = Pretension::model()->with("section")->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
