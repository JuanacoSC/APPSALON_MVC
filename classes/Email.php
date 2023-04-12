<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

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
        // Crear el objeto de email

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'd93241dbcb500c';
        $mail->Password = '51036e07c77aa2';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'Appsalon.com');
        $mail->Subject = 'confirma tu cuenta';

        //Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre  .   "<strong> Has creado tu cuenta en Appsalon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p> presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'> Confirmar cuenta </a> </p>";
        $contenido .= "<p> Si no soliciste esta cuenta, puedes ignorar el mensaje </p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar el email
        $mail->send();
    }

    public function enviarInstrucciones(){

         // Crear el objeto de email

         $mail = new PHPMailer();
         $mail->isSMTP();
         $mail->Host = 'sandbox.smtp.mailtrap.io';
         $mail->SMTPAuth = true;
         $mail->Port = 2525;
         $mail->Username = 'd93241dbcb500c';
         $mail->Password = '51036e07c77aa2';
 
         $mail->setFrom('cuentas@appsalon.com');
         $mail->addAddress('cuentas@appsalon.com', 'Appsalon.com');
         $mail->Subject = 'Reestablece tu password';
 
         //Set HTML
         $mail->isHTML(TRUE);
         $mail->CharSet = 'UTF-8';
 
         $contenido = "<html>";
         $contenido .= "<p><strong>Hola " . $this->nombre  .   "<strong> Has solicitado restablecer tu password, sigue el siguiente enlace para poder hacerlo</p>";
         $contenido .= "<p> presiona aquí: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'> Reestablecer contraseña </a> </p>";
         $contenido .= "<p> Si no soliciste esta cuenta, puedes ignorar el mensaje </p>";
         $contenido .= "</html>";
 
         $mail->Body = $contenido;
 
         //Enviar el email
         $mail->send();

    }
}