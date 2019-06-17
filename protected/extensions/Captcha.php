<?

Class Captcha {

    public $cookie = NULL;
    private $key = "917bfc0bfd25115e4062ad37fd469498";
    private $curl = NULL;

    function __construct() {
        $this->curl = new Curl();
    }

    function __destruct() {
        
    }

    public function getCaptcha($url){  
        // Скачиваем капчу
        $image_path = $this->loadImage($url);

        // Пробуем отправлять капчу
        $params = array(
            'key' => $this->key,
            'file'=> new CurlFile($image_path) 
        );
        $result = $this->curl->request("http://rucaptcha.com/in.php", $params);
        // Повторять, пока не будут свободные операторы
        // while( $result = 'ERROR_NO_SLOT_AVAILABLE' ) {
            // sleep(5);
            // $result = $this->curl->request("http://rucaptcha.com/in.php", $params);
        // }

        if(strpos($result, "|") !== false) {
            $url = "http://rucaptcha.com/res.php?";
            $url_params = array(
                'key' => $this->key,
                'action' => 'get',
                'id' => substr($result, 3)
            );
            $url .= urldecode(http_build_query($url_params));

            // Ждем, пока капчу не введут
            $result = $this->curl->request($url);
            while ($result == 'CAPCHA_NOT_READY') {
                sleep(2);
                $result = $this->curl->request($url);
            } 

            // print_r($result."<br>");
            if(strpos($result, "|") !== false) {
                $captcha = substr($result, 3);
                return $captcha;
            }else{
                // TODO: Обработать ошибку    
            }
        }else{
            // TODO: Обработать ошибку
        }
    }

    private function loadImage($url){
        $captcha_path = Yii::app()->basePath.'/extensions/captcha/'.md5(time()."rank").'.gif';  
        file_put_contents($captcha_path, file_get_contents($url) ); 
        return $captcha_path;
    }
}

?>