<?

Class Debug {
    function __construct() {

    }

    public function set($dirname,$message,$error = false){
        $string = date("Y-m-d H:i:s", time())." ";
        $name = date("Y-m-d", time());
        $string .= ( ( $error === true )?"Ошибка":( ($error === false)?"Сообщение":$error ) ).": ";
        $string .= $message;

        file_put_contents(Yii::app()->basePath."/logs/".$dirname."/".$name.".txt", $string."\n", FILE_APPEND);

        if( $error === true )
            file_put_contents(Yii::app()->basePath."/logs/errors.txt", $string."\n", FILE_APPEND);
    }

    public function log($message, $echo = false, $error = false ){
        if( $echo ) echo $message."\n";

        Debug::set("debug", $message, $error);
    }
    public function error($message, $echo = false){
        if( $echo ) echo $message."\n";

        Debug::set("debug", "Ошибка: ".$message, true);
    }

}

?>