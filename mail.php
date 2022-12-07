<?php

require "includes/PHPMailer.php";
require "includes/SMTP.php";
require "includes/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function sendMail($user_id, $newDate)
{
    require "vendor/autoload.php";

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Port = "587";
    $mail->CharSet = "UTF-8";

    $mail->Username = $_ENV['EMAIL_USERNAME'];
    $mail->Password = $_ENV['EMAIL_PASSWORD'];
    $mail->Subject = "Neuer Termin eingegangen: " .  $newDate->format("d.m.Y") . " " . $newDate->format("H:i") . " Uhr" . " - " . $newDate->modify("+3 hours")->format("H:i") . " Uhr";
    $mail->setFrom("tacscfc@gmail.com");
    $mail->isHTML(true);
    $mail->Body =
        "ID: " . $user_id . "<br>" .
        "Datum: " . $newDate->format("d.m.Y") . "<br>" .
        "Uhrzeit: " . $newDate->format("H:i") . " Uhr" . " - " . $newDate->modify("+3 hours")->format("H:i") . " Uhr";

    $mail->addAddress("tacscfc@gmail.com");
    $mail->send();
    $mail->smtpClose();
}

function sendMailCanceled($user_id, $user_date, $starting_time, $ending_time)
{
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Port = "587";
    $mail->CharSet = "UTF-8";

    $mail->Username = $_ENV['EMAIL_USERNAME'];
    $mail->Password = $_ENV['EMAIL_PASSWORD'];
    $mail->Subject = "Ein Termin wurde storniert: " .  $user_date->format("d.m.Y") . " " . $starting_time->format("H:i") . " Uhr" . " - " . $ending_time->format("H:i") . " Uhr";;
    $mail->setFrom("tacscfc@gmail.com");
    $mail->isHTML(true);
    $mail->Body =
        "ID: " . $user_id . "<br>" .
        "Datum: " . $user_date->format("d.m.Y") . "<br>" .
        "Uhrzeit: " . $starting_time->format("H:i") . " Uhr" . " - " . $ending_time->format("H:i") . " Uhr";

    $mail->addAddress("tacscfc@gmail.com");
    $mail->send();
    $mail->smtpClose();
}
