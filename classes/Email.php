<?php 
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use Sendinblue\Client\Api\EmailCampaignsApi;

class Email{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token){
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = 'tls';
        $mail->setFrom('b.paginasweb@gmail.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'Confirma tu Cuenta';
        //set html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ". $this->nombre ."</strong>. Cuenta creada CORRECTAMENTE en App Salon, confirma la solicitud presionando el siguiente enlace:</p>";
        $contenido .= "<p>Presiona aqui: <a href='".$_ENV['APP_URL']."/confirmar-cuenta?token=". $this->token ."'>Confirmar Cuenta</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, simplemente ignora el mensaje.</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;
        //enviar mail
        $mail->send();
    }


    public function enviarInstrucciones(){
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = 'tls';
        $mail->setFrom('b.paginasweb@gmail.com');
        $mail->addAddress($this->email);
        $mail->Subject = 'Reestablece tu PASSWORD';
        //set html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ". $this->nombre ."</strong>. Haz solicitado REESTABLECER tu PASSWORD, sigue el siguiente enlace para hacerlo:</p>";
        $contenido .= "<p>Presiona aqui: <a href='".$_ENV['APP_URL']."/recuperar?token=". $this->token ."'>Reestablecer Password</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, simplemente ignora el mensaje.</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;
        //enviar mail
        $mail->send();
    }
}

