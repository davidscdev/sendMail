<?php

    require 'config.php';
    require './libs/PHPMailer/Exception.php';
    require './libs/PHPMailer/PHPMailer.php';
    require './libs/PHPMailer/SMTP.php';
    require './libs/PHPMailer/OAuth.php';


    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    class Mensagem{
        private $para = null;
        private $assunto = null;
        private $mensagem = null;

        public function __get($atributo){
            return $this->$atributo;
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;
        }

        public function mensagemValida(){
            if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
                return false;
            }

            return true;

        }
    }

    $mensagem = new Mensagem();

    $mensagem->__set('para', filter_input(INPUT_POST, 'para', FILTER_SANITIZE_EMAIL));
    $mensagem->__set('assunto', filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_STRING));
    $mensagem->__set('mensagem', filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_STRING));

    //print_r($mensagem);

    if(!$mensagem->mensagemValida()){
        echo 'mensagem incompleta!';
        die();
    }

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        //$mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $user;                     //SMTP username
        $mail->Password   = $mailPass;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = $smtpPort;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        //$mail->setFrom('from@example.com', 'Mailer');
        $mail->setFrom('davidsc.dev@gmail.com', 'Teste de suporte.');
        $mail->addAddress($mensagem->__get('para'));     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
       // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Mensagem enviada com sucesso.';
    } catch (Exception $e) {
        echo "Sua mensagem não pode ser enviada. </br>";
        echo "Erro de E-mail: {$mail->ErrorInfo}";
    }
?>