<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to 'column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout='admin';
    public $scripts = array();
    public $types = array(
        1 => "pretension",
        2 => "law",
        3 => "execution"
    );
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public $interpreters = array();

    public $user;
    public $isMobile = false;

    public $texts = NULL;

    public $start;
    public $render;
    public $debugText = "";

    public $adminMenu = array();
    public $settings = NULL;

    public function init() {
        parent::init();
        date_default_timezone_set("Asia/Krasnoyarsk");
        $this->user = User::model()->with("roles.role")->findByPk(Yii::app()->user->id);

        $this->isMobile = (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4)));
        // $this->isMobile = true;

        $adminMenu = ModelNames::model()->findAll(array("order" => "t.sort ASC"));

        $this->adminMenu["items"] = array();
        foreach ($adminMenu as $key => $value) {
            $this->adminMenu["items"][ $value->code ] = $this->toLowerCaseModelNames($value);
        }

        // $code = ( isset($_GET["class"]) )?$_GET["class"]:(Yii::app()->controller->id);
        $code = Yii::app()->controller->id;

        $this->adminMenu["cur"] = $this->toLowerCaseModelNames(ModelNames::model()->find(array("condition" => "code = '".$code."'")));
        
        $this->start = microtime(true);
    }

    public function getRequired($model){
        $rules = $model->rules();

        foreach ($rules as $key => $rule) {
            if( $rule[1] == "required" ){
                $out = explode(",", $rule[0]);

                foreach ($out as $i => $val) {
                    $out[$i] = trim($val);
                }

                return $out;
            }
        }

        return array();
    }

    public function getMaxLength($model){
        $rules = $model->rules();

        $result = array();
        foreach ($rules as $key => $rule) {
            if( $rule[1] == "length" ){
                $tmp = explode(",", $rule[0]);

                foreach ($tmp as $i => $val) {
                    $result[ trim($val) ] = $rule["max"];
                }
            }
        }

        return $result;
    }

    public function beforeRender($view){
        parent::beforeRender($view);

        $this->render = microtime(true);

        $this->debugText = "Controller ".round(microtime(true) - $this->start,4);
        
        return true;
    }
    
    public function afterRenderPartial(){
        parent::afterRenderPartial();

        $this->debugText = ($this->debugText."<br>View ".round(microtime(true) - $this->render,4));
    }

    public function getParam($code, $force = false){
        if( $force ) $this->settings = NULL;

        if( $this->settings == NULL ) $this->getSettings();

        return $this->settings[mb_strtoupper($code,"UTF-8")];
    }

    public function setParam($code,$value){
        $model = Settings::model()->find("code='".$code."'");
        $model->value = $value;
        if( is_array($this->settings) )
            $this->settings[$code] = $value;
        return $model->save();
    }

    public function getSettings(){
        $model = Settings::model()->findAll();

        $this->settings = array();

        foreach ($model as $param) {
            $this->settings[$param->code] = $param->value;
        }
    }

    public function toLowerCaseModelNames($el){
        if( !$el ) return false;
        $el->vin_name = mb_strtolower($el->vin_name, "UTF-8");
        $el->rod_name = mb_strtolower($el->rod_name, "UTF-8");

        return $el;
    }

    public function getRusMonth($num){
        $rus = array(
            "января",
            "февраля",
            "марта",
            "апреля",
            "мая",
            "июня",
            "июля",
            "августа",
            "сентября",
            "октября",
            "ноября",
            "декабря",
        );

        return $rus[$num-1];
    }

    public function getRusDate($time, $withTime = false){
        $tmp = explode(" ", $time);
        $date = explode(".", date("j.n.Y", strtotime($tmp[0])));
        return $date[0]."&nbsp;".Controller::getRusMonth($date[1])."&nbsp;".$date[2]."&nbsp;г.".( (isset($tmp[1]) && $withTime == true)?(" ".$tmp[1]):"" );
    }

    public function getTime($time){
        return date("G:i", strtotime($time));
    }

    public function getIds($model, $field = NULL){
        $ids = array();
        foreach ($model as $key => $value)
            array_push($ids, (($field !== NULL)?$value[$field]:$value->id) );
        return $ids;
    }

    public function getAssoc($model, $field = NULL){
        $out = array();
        foreach ($model as $key => $value){
            $out[(($field !== NULL)?$value[$field]:$value->id)] = $value;
        }
        return $out;
    }

    public function readDates(&$model){
        $modelName = get_class($model);

        if( isset($_GET["previous"]) ){
            $previousMonth = $this->getPreviousMonth();

            $model->date_from = $previousMonth->from;
            $model->date_to = $previousMonth->to;

            return true;
        }

        $from = (isset($_GET[$modelName]["date_from"]))?$_GET[$modelName]["date_from"]:NULL;
        $to = (isset($_GET[$modelName]["date_to"]))?$_GET[$modelName]["date_to"]:NULL;
        if( $from === NULL && $to === NULL ){

            // Проверка на наличие дат в сессии (пока не заносим туда)
            // #TODO# сделать запоминание дат
            if( isset($_SESSION[$modelName]) && isset($_SESSION[$modelName]["date"]) ){
                $from = $_SESSION[$modelName]["date"]["from"];
                $to = $_SESSION[$modelName]["date"]["to"];
            }else{
                // Дефолтные значения
                $from = $this->getCurrentMonthFrom();
            }
        }

        $model->date_from = $from;
        $model->date_to = $to;
    }

    public function removeFiles($ids = array()){
        if( !count($ids) ) return false;

        $files = File::model()->findAll("id IN (".implode(", ", $ids).")");

        if( count($files) ){
            $out = array();
            foreach ($files as $key => $file) {
                array_push($out, $file->original);
            }

            $note = Note::model()->findByPk($files[0]->note_id);

            Log::add("1".$note->type_id, $note->item_id, $note->getItemName()." (".$note->item_id.")", 6, "Примечание от ".$note->date."<br>".implode("<br>", $out));
        }

        File::model()->updateAll(array("note_id" => 0), "id IN (".implode(", ", $ids).")");
    }

    public function addFiles($noteId){
        $count = $_POST["uploaderPj_count"];
        $out = array();

        for( $i = 0; $i < $count; $i++ ){
            $name = $_POST["uploaderPj_".$i."_tmpname"];
            $original = $_POST["uploaderPj_".$i."_name"];
            $status = $_POST["uploaderPj_".$i."_status"];

            if( $status == "done" ){
                if( $this->saveFile($name) ){
                    $file = new File();
                    $file->original = $original;
                    $file->name = $name;
                    $file->note_id = $noteId;
                    if( !$file->save() ){
                        print_r($file->getErrors());
                    }else{
                        array_push($out, $file->original);
                    }
                }
            }
        }

        if( count($out) ){
            $note = Note::model()->findByPk($noteId);

            Log::add("1".$note->type_id, $note->item_id, $note->getItemName()." (".$note->item_id.")", 5, "Примечание от ".$note->date."<br>".implode("<br>", $out));
        }
    }

    public function saveFile($name){
        $arr = explode("/", $name);
        $name = array_pop($arr);

        $tmpFileName = Yii::app()->params['tempFolder']."/".$name;
        $fileName = Yii::app()->params['saveFolder']."/".$name;

        return rename($tmpFileName, $fileName);
    }   

    public function downloadFile($source,$filename) {
        if (file_exists($source)) {
        
            if (ob_get_level()) {
              ob_end_clean();
            }

            $arr = explode(".", $source);
            
            header("HTTP/1.0 200 OK");
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.$filename );
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($source));
            
            readfile($source);
            exit;
        }
    }

    public function isCurrentMonth($model){
        return $model->date_from == $this->getCurrentMonthFrom() && $model->date_to == NULL;
    }

    public function isPreviousMonth($model){
        $previousMonth = $this->getPreviousMonth();
        return $model->date_from == $previousMonth->from && $model->date_to == $previousMonth->to;
    }

    public function getCurrentMonthFrom(){
        return date("d.m.Y", strtotime("first day of this month"));
    }

    public function getPreviousMonth(){
        return (object) array( 
            "from" => date("d.m.Y", strtotime("first day of previous month")),
            "to" => date("d.m.Y", strtotime("last day of previous month")),
        );
    }

    public function accessFilter(&$filter){
        if( Yii::app()->user->checkAccess('accessAll') ){
            
        }else if( Yii::app()->user->checkAccess('accessAgency') ){
            $filter->agency_id = $this->user->agency_id;
        }else if( Yii::app()->user->checkAccess('accessOnlyHis') ){
            $filter->user_id = Yii::app()->user->id;
        }
    }

    public function getUser(){
        return User::model()->with("roles.role")->findByPk(Yii::app()->user->id);
    }

    public function addDays($indate, $days){
        $date = date_create($indate);
        @date_add($date, date_interval_create_from_date_string("$days days"));
        return @date_format($date, "d.m.Y");
    }

    public function pluralForm($number, $after) {
       $cases = array (2, 0, 1, 1, 1, 2);
       return $after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
    }

    public function replaceToBr($str){
        return str_replace("\n", "<br>", $str);
    }

    public function replaceToSpan($str){
        return "<span>".str_replace("<br>", "</span><span>", $str)."</span>";
    }

    public function diff($model, $attrs){
        $labels = $model->attributeLabels();
        $parents = $model->relations();

        $out = array();
        foreach ($labels as $code => $name) {
            if( (isset($parents[$code]) && $code != "stakeholders" ) || in_array($code, array("id", "init_debt", "debt")) || ($code == "pretension_id" && isset($labels["debtor"])) ) continue;

            switch ($code) {
                case "stakeholders":
                    $prev = $model->getStakeholdersString();
                    $new = $attrs->getStakeholdersString();
                    if( $prev != $new ){
                        array_push($out, $name.": с «".$prev."» на «".$new."»");
                    }
                    break;
                case "manager_id":
                    if( $model[$code] != $attrs[$code] ){
                        array_push($out, $name.": с «".$model->manager->fio."» на «".$attrs->manager->fio."»");
                    }
                    break;
                
                default:
                    if( $model[$code] != $attrs[$code] ){
                        $tmp = explode("_id", $code);
                        if( count($tmp) && isset($parents[$tmp[0]]) ){
                            array_push($out, $name.": с «".$model->{$tmp[0]}->name."» на «".$attrs->{$tmp[0]}->name."»");
                        }else{
                            array_push($out, $name.": с «".$model[$code]."» на «".$attrs[$code]."»");
                        }
                    }
                    break;
            }
        }
        return implode("<br>", $out);
    }

    public function number_format($num, $count, $sep1, $sep2){
        $out = number_format($num, $count, $sep1, "@");
        return str_replace("@", "&nbsp;", $out);
    }

    public function sendMail($subject, $attrs, $email_to){
        include_once Yii::app()->basePath.'/extensions/phpmail.php';
        $email_from = "robot@bf-nk.ru";
        $message = "";

        foreach ($attrs  as $key => $value){
            if( $key != "" ){
                $message .= "<div><p><b>".$key.": </b>".$value."</p></div>";
            }else{
                $message .= "<div><p>".$value."</p></div>";
            }
        }   
        
        return send_mime_mail("БизнесФорвард «Суды»", $email_from, $name, $email_to, 'UTF-8', 'UTF-8', $subject, $message, true);
    }

    public function mb_ucfirst($text) {
        return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
    }

    public function implodeErrors($arr){
        $errors = array();
        foreach ($arr as $key => $item) {
            array_push($errors, implode(", ", $item));
        }
        return implode("; ", $errors);
    }

    public function returnError( $text ){
        echo json_encode(array(
            "result" => "error",
            "message" => $text
        ));
        die();
    }

    public function returnSuccess( $array = array() ){
        $arResult = array(
            "result" => "success"
        );
        $arResult = $arResult + $array;

        echo json_encode($arResult);
        die();
    }

    public function sendNoteMail($note){
        $attrs = array("Текст примечания" => $note->text);

        if($note->sum){
            $attrs["Сумма взыскания"] = $this->number_format( $note->sum, 2, '.', '&nbsp;' )."&nbsp;руб.";
        }

        if($files = $note->getFilesString()){
            $attrs["Файлы к примечанию"] = $files;
        }

        switch ($note->type_id) {
            case 1:
                $attrs["Наименование контрагента"] = $note->pretension->contractor;
                $attrs["Ответственный"] = $note->pretension->manager->fio;
                $attrs[""] = "<a href='http://".$_SERVER["HTTP_HOST"].Yii::app()->createUrl('/pretension/adminview',array('id' => $note->pretension->id))."'>Ссылка на примечание</a>";
                $email_to = $note->pretension->getStakeholdersString("email");
                $type = "претензии";
                break;
            case 2:
                $attrs["Истец"] = $note->law->plf;
                $attrs["Ответчик"] = $note->law->dft;
                $attrs["Ответственный"] = $note->law->manager->fio;
                $attrs[""] = "<a href='http://".$_SERVER["HTTP_HOST"].Yii::app()->createUrl('/law/adminview',array('id' => $note->law->id))."'>Ссылка на судебное дело</a>";
                $email_to = $note->law->getStakeholdersString("email");
                $type = "судебному делу";
                break;
            case 3:
                $attrs["Наименование должника"] = $note->execution->debtor;
                $attrs["Ответственный"] = $note->execution->manager->fio;
                $attrs[""] = "<a href='http://".$_SERVER["HTTP_HOST"].Yii::app()->createUrl('/execution/adminview',array('id' => $note->execution->id))."'>Ссылка на дело испол. производства</a>";
                $email_to = $note->execution->getStakeholdersString("email");
                $type = "делу испол. производства";
                break;
        }
        return $this->sendMail("Новое примечание к $type", $attrs, $email_to);
    }
}