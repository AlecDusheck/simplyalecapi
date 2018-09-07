<?php

namespace sa\controllers;

use PHPMailer\PHPMailer\PHPMailer;

class ResumeSender extends Controller
{
    public function send()
    {
        $logManager = new \sa\classes\logManager();

        if (!($_SERVER['REQUEST_METHOD'] === 'POST')){
            $arr = array('error' => 'true', 'details' => 'This API server only accepts POST requests. Sorry.', 'details_code' => "bad_method");
            echo \GuzzleHttp\json_encode($arr);
            exit;
        }

        $_POST = json_decode(file_get_contents('php://input'), true);

        if(!isset($_POST['email'])){
            $arr = array('error' => 'true', 'details' => 'Missing request prams.', 'details_code' => var_dump($_POST));
            echo \GuzzleHttp\json_encode($arr);
            exit;
        }

        $to = $_POST['email'];

        if(!filter_var($to, FILTER_VALIDATE_EMAIL)){
            $arr = array('error' => 'true', 'details' => 'Malformed email address. How did you get here?', 'details_code' => "malformed_email");
            echo \GuzzleHttp\json_encode($arr);
            exit;
        }

        if(!$logManager->getOkSend(filter_var($to, FILTER_SANITIZE_EMAIL))){
            $arr = array('error' => 'true', 'details' => 'Email throttled.', 'details_code' => "throttled_email");
            echo \GuzzleHttp\json_encode($arr);
            exit;
        }

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->Username = $this->container->get('settings')['general']['smtpUsername'];
        $mail->Password = $this->container->get('settings')['general']['smtpPassword'];
        $mail->SetFrom($this->container->get('settings')['general']['smtpFrom'], "Alec");
        $mail->Subject = "Resume";
        $mail->Body = "Hi,\n\nSomeone typed this email address in my website (simplyalec.com) to be sent a copy of my resume. If you didn't initiate this request, disregard this email. \n\nRegards,\nAlec";
        $mail->addAttachment(__DIR__ . "/../../files/Resume.docx", 'Resume.docx');
        $mail->addAddress(filter_var($to, FILTER_SANITIZE_EMAIL));
        if(!$mail->send()){
            $arr = array('error' => 'true', 'details' => 'Details are unavailable. Please pull out of production.', 'details_code' => "internal_error");
        }else{
            $arr = array('error' => 'false', 'details' => 'Mail sent.', 'details_code' => "mail_sent");
        }
        $logManager->logSend($to);
        header("Content-Type: application/json");
        echo \GuzzleHttp\json_encode($arr);
        exit;
    }
}