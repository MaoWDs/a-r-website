<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Configuración de reCAPTCHA
$siteKey = '6LcjSg4qAAAAAPimJh6ZcQqcd8fRAHQ33gUlUb7v';
$secretKey = '6LcjSg4qAAAAANEu0CyEkagbC2k1UI4pm1pp7B1f';

// Verificar reCAPTCHA
if (isset($_POST['g-recaptcha-response'])) {
  $recaptchaResponse = $_POST['g-recaptcha-response'];
  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $data = array(
    'secret' => $secretKey,
    'response' => $recaptchaResponse,
    'remoteip' => $_SERVER['REMOTE_ADDR']
  );
  $options = array(
    'http' => array(
      'method' => 'POST',
      'header' => 'Content-Type: application/x-www-form-urlencoded',
      'content' => http_build_query($data)
    )
  );
  $context = stream_context_create($options);
  $result = json_decode(file_get_contents($url, false, $context), true);
  if (!$result['success']) {
    // reCAPTCHA no válido, mostrar error
    echo 'Error: reCAPTCHA no válido';
    exit;
  }
}
// Procesar formulario
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];

// Configuración de PHPMailer
$mail = new PHPMailer();

try {

  $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
  $mail->isSMTP(); // Habilitamos el uso de SMTP
  $mail->Host = 'smtp.gmail.com'; // Dirección del servidor SMTP
  $mail->SMTPAuth = true; // Autenticación SMTP
  $mail->Username = ''; // Usuario SMTP
  $mail->Password = ''; // Contraseña SMTP
  $mail->SMTPSecure = 'tls'; // Tipo de seguridad (tls o ssl)
  $mail->Port = 587; // Puerto del servidor SMTP

  // Configuración del correo electrónico
  // Configuramos el remitente
  $mail->setFrom($email, $firstName . ' '. $lastName); // Correo electrónico y nombre del remitente

  // Configuramos el destinatario
  $mail->addAddress('mauri_spake@hotmail.com', 'Ammon & Rizos'); // Correo electrónico y nombre del destinatario

  // Configuramos el asunto y el cuerpo del correo electrónico
  $mail->Subject = 'Contact from Website A&R';
  $mail->Body = "Nombre: $firstName $lastName\nEmail: $email\nTeléfono: $phone\nMensaje: $message";

  // Enviamos el correo electrónico
  $mail->send();
  $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
