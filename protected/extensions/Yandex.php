<?

Class Yandex {

    private $domains;
    private $curl;
    private $onPage = 10;
    private $depth = 2;
    public $debug = NULL;
    
    function __construct($domains) {
        include_once Yii::app()->basePath.'/extensions/simple_html_dom.php';

        $this->domains = $domains;
        $this->curl = new Curl();
    }

    public function parseAll($keyWords = NULL, $city = NULL, $limit = 100) {
        $result = array_fill_keys($keyWords, array("number" => 1000+$limit, "title" => NULL, "link" => NULL, "description" => NULL) );

        $this->debug = $result; /* DEBUG */

        if( is_array($keyWords) && is_integer($city) ){
            foreach ($keyWords as $i => $word) {
                $result[$word] = $this->parse($word, $city, $limit);
            }
        }
        return $result;
    }

    public function parse($word = NULL, $city = NULL, $limit = 100) {
        $result = array_fill_keys($this->domains, array("number" => 1000+$limit, "title" => NULL, "link" => NULL, "description" => NULL) );

        $this->debug[$word] = array("req_count" => 0, "req_ext_count" => 0, "captcha_count" => 0, "result" => "", "exec_time" => 0); /* DEBUG */
        $start_debug = microtime(true);

        // Перебираем страницы одного ключевого слова
        for ($page=0; $page < ceil($limit/$this->onPage); $page++) { 
            $url = $this->getUrl($word, $page, $city);
            $this->findPosition($url, $page, $word, $result);
            // sleep(rand(2,4));
            if( $this->isFull($result) ){
                /* TODO: Сделать уточнение позиции с заданной точностью */
                break;
            }
        }
        Log::debug($this->debug[$word]["req_count"]." ".$this->debug[$word]["captcha_count"]." (".$this->debug[$word]["result"].") ".$this->debug[$word]["exec_time"]." - ".$word." ".$city);
        $this->debug[$word]["exec_time"] = round(microtime(true) - $start_debug, 2);
        return $result;
    }

    private function findPosition($url, $page, $word, &$domains){
        $this->debug[$word]["req_count"] = $this->debug[$word]["req_count"]+1; /* DEBUG */
        $result = $this->curl->request( $url );
        $html = str_get_html( $result );
        $i = 0;
        var_dump($url);
        if( !$html ) die("Не получена страница");

        $captcha_count = 0;
        // Если запросило капчу пытаемся ее ввести, пока не введем правильно (максимум 5 раз)
        while( $html->find(".form__captcha") && $captcha_count < 5 ){
            $captcha_count++;
            $captcha = new Captcha();
            $this->debug[$word]["captcha_count"] = $this->debug[$word]["captcha_count"]+1; /* DEBUG */
            $captcha_text = $captcha->getCaptcha( $html->find(".form__captcha",0)->src );

            $params = array(
                "key" => $html->find(".form__key",0)->value,
                "retpath" => str_replace("&amp;", "&", $html->find(".form__retpath",0)->value),
                "rep" => $captcha_text
            );

            $result = $this->curl->request( "https://yandex.ru/checkcaptcha?key=".urlencode($params["key"])."&rep=".urlencode($params["rep"])."&retpath=".urlencode($params["retpath"]) );

            $html = str_get_html( $result );
        }

        // Перебираем все объявления
        foreach ($html->find(".serp-item") as $key => $item) {
            // Не смотрим в рекламные объявления и объявления яндекса (фото, видео, справочник)
            if( $item->find(".organic__path") && !$item->find(".label_color_yellow") ){
                $i++;
                foreach ($domains as $domain => $position) {
                    if( $item->find(".organic__path a", 0)->plaintext == $domain && $position >= 1000 ){
                        $domains[$domain]["number"] = $i + $page*$this->onPage;
                        $domains[$domain]["title"] = mb_substr(trim($item->find(".organic__url",0)->plaintext), 0, 100, "UTF-8");
                        $domains[$domain]["link"] = substr($item->find(".organic__url",0)->getAttribute("href"), 0, 255);
                        $domains[$domain]["description"] = mb_substr(trim($item->find(".organic__content-wrapper .text-container",0)->plaintext), 0, 200, "UTF-8");
                        $this->debug[$word]["result"] .= ($domains[$domain]." ");
                    }
                }
            }
        }
    }

    private function isFull($domains){
        foreach ($domains as $i => $domain)
            if( $domain["number"] >= 1000 ) return false;
        return true;
    }

    private function getUrl($word, $page, $city){
        return "https://yandex.ru/search/?lr=".$city."&text=".urlencode($word)."&p=$page";
    }
}

?>