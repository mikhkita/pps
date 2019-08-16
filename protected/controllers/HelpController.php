<?php

class HelpController extends Controller
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
				"users" => array("*"),
			),
		);
	}

	public function actionIndex(){
		$this->render("index".(($this->isMobile)?"Mobile":""), $params);
	}

	public function actionOrder(){
		$this->render("order".(($this->isMobile)?"Mobile":""), $params);
	}

	public function actionPayment(){
		$this->render("payment".(($this->isMobile)?"Mobile":""), $params);
	}
}
