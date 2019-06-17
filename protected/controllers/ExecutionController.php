<?php

class ExecutionController extends Controller
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

	public function actionAdminIndex($state_id = NULL, $partial = false){
		unset($_GET["partial"]);
		if( !$partial ){
			$this->layout = "admin";
			$this->pageTitle = $this->adminMenu["cur"]->name;
		}

		// Если не указан тип, то редиректить на первый
		$states = State::model()->findAll(array("order" => "id ASC"));
		if( $state_id === NULL ){
			header("Location: ".$this->createUrl('/'.$this->adminMenu["cur"]->code.'/adminindex', array("state_id" => $states[0]->id)));
			die();
		}

        $filter = new Execution('filter');
        $filter->is_material = NULL;
        $filter->state_id = $state_id;
        $filter->section_id = Controller::getIds($this->user->sections, "section_id");

		if (isset($_GET['Execution'])){
            $filter->attributes = $_GET['Execution'];
        }

        $dataProvider = $filter->search(256);
		$count = $filter->search(256, true);

		$data = $dataProvider->getData();

		$data = Execution::getBySections($data);

		$params = array(
			"data" => $data,
			"pages" => $dataProvider->getPagination(),
			"filter" => $filter,
			"count" => $count,
			"labels" => Execution::attributeLabels(),
			"states" => $states
		);

		if( !$partial ){
			$this->render("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->renderPartial("adminIndex".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionAdminView($id, $partial = false)
	{
		$model = Execution::model()->with(array("notes"))->findByPk($id);

		$params = array(
			"model" => $model,
			"labels" => Execution::attributeLabels(),
			"noteLabels" => Note::attributeLabels(),
		);

		if( $partial ){
			$this->renderPartial("adminView".(($this->isMobile)?"Mobile":""), $params);
		}else{
			$this->render("adminView".(($this->isMobile)?"Mobile":""), $params);
		}
	}

	public function actionAdminCreate($section_id, $law_id = NULL, $state_id = 1)
	{
		$model = new Execution;

		$model->state_id = $state_id;
		$model->section_id = $section_id;
		$model->manager_id = $this->user->id;
		$model->law_id = $law_id;

		if(isset($_POST["Execution"])) {
			if( $model->updateObj($_POST["Execution"]) ){
				Stakeholder::model()->deleteAll("item_id = '".$model->id."' AND type_id = '3'");
				
				if( isset($_POST["Stakeholders"]) ){
					foreach ($_POST["Stakeholders"] as $key => $stakeholderId) {
						$stakeholder = new Stakeholder();
						$stakeholder->type_id = "3";
						$stakeholder->item_id = $model->id;
						$stakeholder->user_id = $stakeholderId;
						$stakeholder->save();
					}
				}
				if( $model->law_id !== NULL ){
					echo json_encode(array(
						"result" => "success",
						"action" => "redirect",
						"href" => Yii::app()->createUrl('/execution/adminindex',array("state_id" => $model->state_id))
					));
				}else{
					$this->actionAdminIndex($model->state_id, true);
				}
				return true;
			}
		} else {
			if( $law_id !== NULL ){
				$law = Law::model()->findByPk($law_id);
				if( $law ){
					$model->debtor = $law->dft;
				}
			}

			$this->renderPartial("adminCreate",array(
				"model" => $model
			));
		}
	}

	public function actionAdminUpdate($id, $state_id = NULL)
	{
		$model = $this->loadModel($id);

		$_GET["section_id"] = $model->section_id;
		$_GET["state_id"] = $state_id;

		$stakeholders = $this->getIds($model->stakeholders, "user_id");

		if(isset($_POST["Execution"])) {
			Stakeholder::model()->deleteAll("item_id = '".$model->id."' AND type_id = '3'");
			if( isset($_POST["Stakeholders"]) ){
				foreach ($_POST["Stakeholders"] as $key => $stakeholderId) {
					$stakeholder = new Stakeholder();
					$stakeholder->type_id = "3";
					$stakeholder->item_id = $model->id;
					$stakeholder->user_id = $stakeholderId;
					$stakeholder->save();
				}
			}

			if( $model->updateObj($_POST["Execution"]) ){
				$this->actionAdminIndex($state_id, true);
				return true;
			}
		}else{
			$this->renderPartial("adminUpdate",array(
				"model" => $model,
				"stakeholders" => $stakeholders
			));
		}
	}

	public function actionAdminDelete($id, $state_id)
	{
		$model = $this->loadModel($id);
		// $state_id = $model->state_id;
		$model->delete();

		$this->actionAdminindex($state_id, true);
	}

	public function loadModel($id)
	{
		$model = Execution::model()->findByPk($id);

		if($model===null)
			throw new CHttpException(404, "The requested page does not exist.");
		return $model;
	}
}
