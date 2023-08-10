<?php

include "mail/src/PHPMailer.php";
include "mail/src/Exception.php";
include "mail/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


// mailer("peter.k@btech.christuniversity.in", "Peter K Joseph", "Test Mail", "Test Mail", "This is a test mail from the CSA - Center for Social Actions");
function mailer($recieverMail, $recieverName, $subject, $subtext, $message)
{
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = //username;
        $mail->Password   = //password;
        $mail->SMTPSecure =  "STARTTLS";
        $mail->Port       = 587;
        $mail->setFrom('/mail ID/', 'Communications @ CSA - Center for Social Actions');
        $mail->addReplyTo('/mail ID/', 'Reply Back to CSA Kengeri');
        // $mail->addCC('csa.kengeri@christuniversity.in', "CSA Response Mail");
        $mail->isHTML(true);;
        $mail->addAddress($recieverMail, $recieverName);
        $mail->Subject = "[CSA] " . $subject;
        $message = '<body>
            <style>@import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700;800&display=swap");</style>
            <main style="font-family: "Open Sans", sans-serif;padding: 10px;margin: 0 auto;background-color: #f9f8ff;box-shadow: 0 0 15px #aaaaaa;width: 90%;max-width: 900px;border-radius: 0.25rem;">
                <nav style="width: 100%;display: flex;justify-content: center;align-items: center;">
                    <center><img src="https://christuniversity.in/uploads/userfiles/image/Copy%20of%20cad-logo.png" style="width: 20%;max-width: 150px;min-width: 100px;" alt="" srcset=""></center>
                </nav>
                <hr style="width: 80%;">
                <h2 style="width: 100%;text-align: center;font-weight: 700;">' . $subtext . '</h2>
                <div style="width: 85%;margin: auto;">
                    <p>Dear ' . $recieverName . ',<i></i><br>Greetings from CSA - Center for Social Actions<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $message . '<br>
                </h4>
                    <h4>This is an automated message<br><br>Sent by<br>CSA - Center for Social Actions<br>Christ University<br>Kengeri Campus
                    <br><img style="padding: 0; transform: translate(-5px); width: 10%; min-width: 50px" alt="CSA" src="https://christuniversity.in/uploads/userfiles/image/Copy%20of%20cad-logo.png"></p>
                    </h4>
                </div>
            </main>
            </body>';
        $mail->Body    = $message;
        $mail->AltBody = "You have a new mail from CSA - Center for Social Actions";
        $mail->send();
    } catch (Exception $e) {
        http_response_code(500);
        echo "CODE 6: Mailing engine failed";
        echo $e;
    }
}
