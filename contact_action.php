<?php 
// Source - https://stackoverflow.com/a
// Posted by Fancy John, modified by community. See post 'Timeline' for change history
// Retrieved 2026-01-16, License - CC BY-SA 4.0

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/src/Exception.php';
require __DIR__ . '/src/PHPMailer.php';
require __DIR__ . '/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
	/* ==========================  Define variables ========================== */

	#Your e-mail address
	define("__TO__", "bibinm.sbsol@gmail.com");

	#Message subject
	define("__SUBJECT__", "");

	#Success message
	define('__SUCCESS_MESSAGE__', "Your message has been sent. Thank you!");

	#Error message 
	define('__ERROR_MESSAGE__', "Error, your message hasn't been sent");

	#Messege when one or more fields are empty
	define('__MESSAGE_EMPTY_FILDS__', "Please fill out  all fields");

	/* ========================  End Define variables ======================== */

	//Send mail function
	function send_mail($to,$subject,$message,$headers){
		if(@mail($to,$subject,$message,$headers)){
			echo json_encode(array('info' => 'success', 'msg' => __SUCCESS_MESSAGE__));
		} else {
			echo json_encode(array('info' => 'error', 'msg' => __ERROR_MESSAGE__));
		}
	}

	//Check e-mail validation
	function check_email($email){
		if(!@eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
			return false;
		} else {
			return true;
		}
	}

	//Get post data
	
// 	print_r($_POST);


$recaptchaSecret = '6Lffx10sAAAAAIcEJkkcF2kr3-o3Zf76BpRunVcB';
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

$verify = file_get_contents(
    "https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}"
);

$captcha = json_decode($verify);

if (!$captcha || !$captcha->success) {
    //echo json_encode(['status' => 'captcha_error']);
   // exit;
}



	if(isset($_POST['name']) and isset($_POST['type']) and isset($_POST['email'])){
		$name 	 = $_POST['name'];
		$mail 	 = $_POST['email'];
		$type = $_POST['type'];

		if($name == '') {
			echo json_encode(array('info' => 'error', 'msg' => "Please enter your name."));
			exit();
		} else if($mail == ''){
			echo json_encode(array('info' => 'error', 'msg' => "Please enter valid e-mail."));
			exit();
		} else {
			//Send Mail
			$to = __TO__;
		//	$subject = __SUBJECT__ . ' ' . $name;
			$message = '
			<html>
			<head>
			  <title>Mail from '. $name .'</title>
			</head>
			<body>
			  <table style="width: 500px; font-family: arial; font-size: 14px;" border="1">
				<tr style="height: 32px;">
				  <th align="right" style="width:150px; padding-right:5px;">Name:</th>
				  <td align="left" style="padding-left:5px; line-height: 20px;">'. $name .'</td>
				</tr>
				<tr style="height: 32px;">
				  <th align="right" style="width:150px; padding-right:5px;">E-mail:</th>
				  <td align="left" style="padding-left:5px; line-height: 20px;">'. $mail .'</td>
				</tr>
				<tr style="height: 32px;">
				  <th align="right" style="width:150px; padding-right:5px;">Service Type:</th>
				  <td align="left" style="padding-left:5px; line-height: 20px;">'. $type .'</td>
				</tr>
			  </table>
			</body>
			</html>
			';

// echo $message;exit;

//
// =======================
// SEND EMAIL
// =======================
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'morrisoncarehr@gmail.com';
    $mail->Password   = 'gvljctxeofydmpgc';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('morrisoncarehr@gmail.com', $name);
    $mail->addAddress('info@morrisoncare.co.uk');
    // $mail->addAddress('bibinm.sbsol@gmail.com');
    // $mail->addReplyTo($mail, $name);

    $mail->isHTML(true);
    $mail->Subject = 'Contact Form'. ' - ' . $name;
    $mail->Body    = $message;
   
    $mail->send();
      echo "<script>alert('Mail sent succesfully ! We will contact you soon !');window.location.href='contact-us.html';</script>";
    exit;
} catch (Exception $e) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
//
			
		}
	} else {
		echo json_encode(array('info' => 'error', 'msg' => __MESSAGE_EMPTY_FILDS__));
	}
 ?>