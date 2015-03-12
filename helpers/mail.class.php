<?php
	class Mailer {
		
		//create random string
		function send($conf, $add_reply = '', $dest_email, $dest_name, $subject, $body, $attach = '') {
			
			require_once($conf['includes_dir'].'phpmailer/class.phpmailer.php');
			
			//Create a new PHPMailer instance
			$mail = new PHPMailer;
			
			//Tell PHPMailer to use SMTP
			$mail->isSMTP();
			
			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			$mail->SMTPDebug = 2;
			
			//Ask for HTML-friendly debug output
			$mail->Debugoutput = 'html';
			
			//Set the hostname of the mail server
			$mail->Host = $conf['SMTPHost'];
			
			//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
			$mail->Port = $conf['SMTPPort'];
			
			//Set the encryption system to use - ssl (deprecated) or tls
			$mail->SMTPSecure = 'tls';
			
			//Whether to use SMTP authentication
			$mail->SMTPAuth = true;
			
			//Username to use for SMTP authentication - use full email address for gmail
			$mail->Username = $conf['SMTPUser'];
			
			//Password to use for SMTP authentication
			$mail->Password = $conf['SMTPPass'];
			
			//Set who the message is to be sent from
			$mail->setFrom($conf['SMTPEmail'],$conf['SMTPAdmin']);
			
			if(!empty($add_replay)){
				//Set an alternative reply-to address
				$mail->addReplyTo('replyto@example.com', 'First Last');
			}
			
			//Set who the message is to be sent to
			$mail->addAddress($dest_email, $dest_name);
			
			//Set the subject line
			$mail->Subject = $subject;
			
			//Replace the plain text body with one created manually
			$mail->Body = $body;
			
			if(!empty($attach)){
				//Attach an image file
				$mail->addAttachment($attach);
			}
			
			//send the message, check for errors
			if (!$mail->send()) {
				$err = 1;
				$msg = $mail->ErrorInfo;
			} else {
				$err = 0;
				$msg = "Email terkirim";
			}
			
			return array($err, $msg);
		}
	}
?>