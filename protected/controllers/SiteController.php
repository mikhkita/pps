<?php

class SiteController extends Controller
{
	public $layout="column1";

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
                "actions" => array("notifications"),
                "users" => array("*"),
            ),
            array("allow",
                "actions" => array("download", "viewFile"),
                "roles" => array("readAll"),
            ),
            // array("allow",
            //     "actions" => array("upload", "install"),
            //     "roles" => array("root"),
            // ),
            array("allow",
                "actions" => array("error", "index", "login", "logout", "install"),
                "users" => array("*"),
            ),
            array("deny",
                "users" => array("*"),
            ),
        );
    }

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			"captcha" => array(
				"class" => "CCaptchaAction",
				"backColor" => 0xFFFFFF,
			),
			// page action renders "static" pages stored under "protected/views/site/pages"
			// They can be accessed via: index.php?r=site/page&view=FileName
			"page" => array(
				"class" => "CViewAction",
			),
		);
	}

    public function actionDownload($file_id){
        $file = File::model()->findByPk($file_id);
        if($file===null)
            throw new CHttpException(404, "The requested page does not exist.");

        $this->downloadFile(Yii::app()->params['saveFolder']."/".$file->name, $file->original);
    }

    public function actionViewFile($file_id){
        $file = File::model()->findByPk($file_id);
        if($file===null)
            throw new CHttpException(404, "The requested page does not exist.");

        $filename = Yii::app()->params['saveFolder']."/".$file->name;
        if (file_exists($filename)) {
            $ext = array_pop(explode(".", $filename));
            // header('Content-Description: File Transfer');
            switch (strtolower($ext)) {
                case "pdf":
                    header('Content-Type: application/pdf');
                    break;
                case "jpg":
                case "jpeg":
                case "png":
                case "gif":
                case "gif":
                    header('Content-Type: image/'.$ext);
                    break;
                
                default:
                    header('Content-Type: application/octet-stream');
                    break;
            }
            // header('Content-Disposition: attachment; filename="'.basename($filename).'"');
            // header('Expires: 0');
            // header('Cache-Control: must-revalidate');
            // header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
            exit;
        }

        // readfile(Yii::app()->params['saveFolder']."/".$file->name);
        // $this->downloadFile(, $file->original);
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
        $this->layout="service";
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error["message"];
	    	else
	        	$this->render("error", $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST["ContactForm"]))
		{
			$model->attributes=$_POST["ContactForm"];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params["adminEmail"], $model->subject, $model->body, $headers);
				Yii::app()->user->setFlash("contact", "Thank you for contacting us. We will respond to you as soon as possible.");
				$this->refresh();
			}
		}
		$this->render("contact",array("model" => $model));
	}

	/**
	 * Displays the login page
	 */
	public function actionIndex(){
		$this->redirect(array("login"));
	}

	public function actionLogin()
	{
        $this->layout="service";
		if( !Yii::app()->user->isGuest ) $this->redirect($this->createUrl(Yii::app()->params["defaultAdminRedirect"]));

		// $this->layout="admin";
		if (!defined("CRYPT_BLOWFISH")||!CRYPT_BLOWFISH)
			throw new CHttpException(500, "This application requires that PHP was compiled with Blowfish support for crypt().");

		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST["ajax"]) && $_POST["ajax"]==="login-form")
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST["LoginForm"]))
		{
			$model->attributes=$_POST["LoginForm"];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect($this->createUrl(Yii::app()->params["defaultAdminRedirect"]));
		}
		// display the login form
		$this->render("login",array("model" => $model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionUpload(){
        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        /* 
        // Support CORS
        header("Access-Control-Allow-Origin: *");
        // other CORS headers if any...
        if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
            exit; // finish preflight CORS requests here
        }
        */

        // 5 minutes execution time
        @set_time_limit(5 * 60);

        // Uncomment this one to fake upload time
        // usleep(5000);

        // Settings
        $targetDir = "upload/images";
        //$targetDir = "uploads";
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 60 * 3600; // Temp file age in seconds


        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }

        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        echo $filePath;

        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;


        // Remove old temp files    
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
            }

            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}.part") {
                    continue;
                }

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match("/\.part$/", $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }   


        // Open temp file
        if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {    
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off 
            rename("{$filePath}.part", $filePath);
        }

        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }

	/*! Сброс всех правил. */
    public function actionInstall() {

        if ( Yii::app()->user->id != 1 ) {
            throw new CHttpException(403, "Forbidden");
        }

        $auth=Yii::app()->authManager;
        
        //сбрасываем все существующие правила
        $auth->clearAll();

        // Пользователи
        $auth->createOperation("readUser", "Просмотр пользователей");
        $auth->createOperation("updateUser", "Создание/изменение/удаление пользователей");

        // Заявки
        $auth->createOperation("readOrder", "Просмотр заявок");
        $auth->createOperation("updateOrder", "Создание/изменение заявок");

        // Платежи
        $auth->createOperation("readPayment", "Просмотр платежей");
        $auth->createOperation("updatePayment", "Создание/изменение/удаление платежей");

        // Справочники
        $auth->createOperation("readDictionary", "Просмотр справочников");
        $auth->createOperation("updatePoint", "Создание/изменение точек маршрута");
        $auth->createOperation("updateDictionary", "Создание/изменение справочников");

        // Турагентства
        $auth->createOperation("readAgency", "Просмотр агентств");
        $auth->createOperation("updateAgency", "Создание/изменение/удаление агентств");

        // Общее
        $auth->createOperation("accessOnlyHis", "Доступ только к своему");
        $auth->createOperation("accessAgency", "Доступ ко всему внутри турагентства");
        $auth->createOperation("accessAll", "Доступ ко всему");

    // Права --------------------------------------------------- Права

        // Управление пользователями (полный доступ к пользователям)
        $role = $auth->createRole("userAdmin");
        $role->addChild("readUser");
        $role->addChild("updateUser");

        // Управление заявками
        $role = $auth->createRole("orderAdmin");
        $role->addChild("readOrder");
        $role->addChild("updateOrder");

        // Управление платежами
        $role = $auth->createRole("paymentAdmin");
        $role->addChild("readPayment");
        $role->addChild("updatePayment");

        // Управление справочниками
        $role = $auth->createRole("dictionaryAdmin");
        $role->addChild("readDictionary");
        $role->addChild("updateDictionary");

        // Управление турагентствами
        $role = $auth->createRole("agencyAdmin");
        $role->addChild("readAgency");
        $role->addChild("updateAgency");


        // Менеджер
        $role = $auth->createRole("manager");
        $role->addChild("accessOnlyHis");
        $role->addChild("orderAdmin");

        // Директор
        $role = $auth->createRole("director");
        $role->addChild("manager");
        $role->addChild("userAdmin");
        $role->addChild("accessAgency");
        $role->addChild("paymentAdmin");

        // Админ
        $role = $auth->createRole("admin");
        $role->addChild("director");
        $role->addChild("accessAll");
        $role->addChild("readDictionary");
        $role->addChild("updatePoint");
        $role->addChild("agencyAdmin");
        $role->addChild("userAdmin");

        // Root
        $role = $auth->createRole("root");
        $role->addChild("admin");
        $role->addChild("dictionaryAdmin");

    // Роли --------------------------------------------------- Роли
        
        // Связываем пользователей с ролями
        $users = User::model()->with("roles.role")->findAll();
        foreach ($users as $i => $user) {

            foreach ($user->roles as $j => $role) {
                $auth->assign($role->role->code, $user->id);
            }
        }

        // Сохраняем роли и операции
        $auth->save();
        
        die();
        $this->render("install");
    }
}
