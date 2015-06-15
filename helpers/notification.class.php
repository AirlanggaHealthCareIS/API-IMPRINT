<?php
	
	class Notification{
		private $sender_id = "526266735061";//named-foundry-844
		private $api_key = "AIzaSyDzMrkDa-CpRRZLK2n9xkX0Ez3P_qOpvYs";
		
		function send($regId, $content){
			
			$msg = array
			(
				'message' 		=> $content,
				'title'			=> 'Notifikasi IMPRINT',
				'vibrate'		=> 1,
				'sound'			=> 1,
				'largeIcon'		=> 'large_icon',
				'smallIcon'		=> 'small_icon'
			);
			
			$fields = array
			(
				'registration_ids' 	=> is_string($regId)? array($regId): $regId,
				'data'			=> $msg
			);
			 
			$headers = array
			(
				'Authorization: key=' . $this->api_key,
				'Content-Type: application/json'
			);
			 
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE ) );
			$result = curl_exec($ch );
			curl_close( $ch );

			return  $result;
		}
	}
?>