<?

Class Mailer {
    
    function __construct() {
        include_once Yii::app()->basePath.'/extensions/PHPMailer.php';
    }

    public function send() {
        $mail = new PHPMailer();

        try {
            $mail->setFrom('robot@spp.com', 'Служба Пассажирских Перевозок');
            $mail->addAddress('mike@kitaev.pro'); 
            $mail->isHTML(true);

            $mail->Subject = 'Here is the subject';
            $mail->Body = 'This is the HTML message body <b>in bold!</b>';
            $mail->send();

            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

?>