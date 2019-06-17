<?php

class LawController extends Controller
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

        $filter = new Law('filter');
        $filter->is_material = NULL;

		if (isset($_GET['Law'])){
            $filter->attributes = $_GET['Law'];
        }
        $filter->section_id = Controller::getIds($this->user->sections, "section_id");
        $filter->archive = $archive;

        $dataProvider = $filter->search(256);
		$count = $filter->search(256, true);

		$data = $dataProvider->getData();

		$data = Law::getBySections($data);

		$params = array(
			"data" => $data,
			"pages" => $dataProvider->getPagination(),
			"filter" => $filter,
			"count" => $count,
			"labels" => Law::attributeLabels(),
			"archive" => $archive,
		);

		if( !$partial ){
			$this->render("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionAdminView($id, $partial = false)
	{
		$model = Law::model()->with(array("notes"))->findByPk($id);

		$params = array(
			"model" => $model,
			"labels" => Law::attributeLabels(),
			"noteLabels" => Note::attributeLabels(),
		);

		if( $partial ){
			$this->renderPartial("adminView".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->render("adminView".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionAdminCreate($section_id, $archive = 0, $pretension_id = NULL)
	{
		$model = new Law;

		$model->section_id = $section_id;
		$model->manager_id = $this->user->id;
		$model->pretension_id = $pretension_id;

		if(isset($_POST["Law"])) {
			if( $model->updateObj($_POST["Law"]) ){
				Stakeholder::model()->deleteAll("item_id = '".$model->id."' AND type_id = '2'");
				
				if( isset($_POST["Stakeholders"]) ){
					foreach ($_POST["Stakeholders"] as $key => $stakeholderId) {
						$stakeholder = new Stakeholder();
						$stakeholder->type_id = "2";
						$stakeholder->item_id = $model->id;
						$stakeholder->user_id = $stakeholderId;
						$stakeholder->save();
					}
				}

				if( $model->pretension_id ){
					echo json_encode(array(
						"result" => "success",
						"action" => "redirect",
						"href" => Yii::app()->createUrl('/law/adminindex',array("archive" => $model->archive))
					));
				}else{
					$this->actionAdminIndex(true, $archive);
				}
				return true;
			}
		} else {
			if( $pretension_id !== NULL ){
				$pretension = Pretension::model()->findByPk($pretension_id);
				if( $pretension ){
					$model->plf = $pretension->section->contractor;
					$model->dft = $pretension->contractor;
				}
			}

			$this->renderPartial("adminCreate",array(
				"model" => $model
			));
		}
	}

	public function actionAdminUpdate($id, $archive = 0)
	{
		$model = $this->loadModel($id);

		$_GET["section_id"] = $model->section_id;

		$stakeholders = $this->getIds($model->stakeholders, "user_id");

		if(isset($_POST["Law"])) {
			Stakeholder::model()->deleteAll("item_id = '".$model->id."' AND type_id = '2'");

			if( isset($_POST["Stakeholders"]) ){
				foreach ($_POST["Stakeholders"] as $key => $stakeholderId) {
					$stakeholder = new Stakeholder();
					$stakeholder->type_id = "2";
					$stakeholder->item_id = $model->id;
					$stakeholder->user_id = $stakeholderId;
					$stakeholder->save();
				}
			}

			if( $model->updateObj($_POST["Law"]) ){
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
		$model = Law::model()->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
