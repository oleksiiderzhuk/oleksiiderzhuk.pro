
<?php
    $DIR="../";
//	include ($DIR.$DIR."head_top.php");
//	include ("config.php");
	require("PHPMailer\PHPMailerAutoload.php"); // path to the PHPMailerAutoload.php file.
?>


<?php

$dat = date("d.m.Y");
echo "сьогодні: ".$dat."<br>";
		

	$adr='dialeks@minfin.local';
		$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";
$mail->Host = "webmail.minfin.local";
//echo "host: ".$mail->Host."<br>";
$mail->Port = "25"; // 8025, 587 and 25 can also be used. Use Port 465 for SSL.
//echo "port: ".$mail->Port."<br>";
$mail->SMTPAuth = false;
$mail->SMTPSecure = false;
$mail->Username = "minfin\webadmin";
//echo "user: ".$mail->Username."<br>";
$mail->Password = "Admin-2017";
//echo "pass: ".$mail->Password."<br>";
$mail->CharSet = 'utf-8';

$mail->From = "dialeks@minfin.local";
$mail->FromName = "Реестр ДМДК";

$mail->AddAddress($adr, "");
//echo "tomail: ".$tomail."<br>";
$mail->AddReplyTo("ilienkors@minfin.local", "Sender's Name");
 
$mail->Subject = "Документи на затвердження!";
$html="<html><head></head><body>Test mail</body></html>";
$mail->MsgHTML($html);
$mail->Body=$html;
$mail->IsHTML(true);
//echo "Body: ".$mail->Body."<br>"; 
$mail->WordWrap = 0; 

if(
!$mail->Send()
) {
echo 'Message was not sent.';
echo 'Mailer error: ' . $mail->ErrorInfo;
exit;
} else {
echo "Message has been sent to $adr.<br><br>";
}


?>

