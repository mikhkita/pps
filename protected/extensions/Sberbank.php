<?

Class Sberbank {
    private $login  = 'avtobus-api';  //Логин
    private $password = 'avtobus';  //Пароль 
    private $token = 'vfehaf30es58m1bpogulgrh9vi'; // token
    private $apiEndpoint = 'https://3dsec.sberbank.ru/payment/rest'; // endpoint
    private $backUrl    = "";   //Адрес возврата
    
    function __construct() {
        $this->backUrl = "http://spp.com/payment/callback";
    }

    public function requestTicket($id, $amount, $desc, $json_params = []) {
        $data = $this->makeRequestFields($id, $amount, $desc, $json_params, true);

        $result = $this->sendSberRequest('/register.do', $id, $data);
        if ($result === false) {
            Log::debug( print_r(array(
                "rest_url" => '/register.do',
                "request_params" => compact('id', 'fullname', 'phone', 'amount', 'desc'),
                "response_message" => $result['response']['errorMessage'],
                "response_code" => $result['response']['errorCode'],
                "response" => $result['response'],
            ), true) );
            return false;
        }
        return $result;
    }

    private function sendSberRequest($url, $orderId, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiEndpoint . $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded; charset=utf-8"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resp = curl_exec($ch);
        $result = json_decode($resp, true);
        if ($result) {
            if ($result['errorCode'] > 0) {
            } else {
                $data = array(
                    'id'            => $result['orderId'],
                    'order_id'      => $orderId,
                    'response_code'     => $result['errorCode'],
                    'response_message'  => $result['errorMessage'],
                );

                return array('status' => 'success', 'url' => $result['formUrl'], 'response' => $result, 'data' => $data);
            }
        }
        return array('status' => 'error', 'response' => $result, 'data' => $data);
    }

    private function makeRequestFields($id, $amount, $desc, $json_params = [], $fromPartners = false) {
        $amount = (int) ($amount * 100);

        $data = array(
            'token' => $this->token,
            'orderNumber' => md5($id.time().rand()),
            'amount' => $amount,
            'returnUrl' => $this->backUrl . '?success=1&payment_id=' . $id,
            'failUrl' => $this->backUrl . '?fail=1&payment_id=' . $id,
            'description' => $desc,
        );
        if ($json_params) {
            $data['jsonParams'] = json_encode($json_params);
        }
        return $data;
    }
}

?>