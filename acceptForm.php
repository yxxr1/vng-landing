<?php
	header('HTTP/1.0 403 Forbidden');
	return;
  $ADMIN_MAIL = '';
  $FROM_MAIL = '';

  ini_set('smtp', '');
  ini_set('sendmail_from', $FROM_MAIL);

  function checkCaptcha($token){
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
      'secret' => '',
      'response' => $token
    ];
    $options = [
      'http' => [
        'method' => 'POST',
        'content' => http_build_query($data)
      ]
    ];
    $context  = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captcha_success = json_decode($verify);
    return $captcha_success->success;
  }

  if( !checkCaptcha( $_POST['g-recaptcha-response'] ) ) return "CAPTCHA ERROR";

  function sendEmail($to, $subject, $text) {
      $headers = "From: info_vng<".$FROM_MAIL.">\r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
      return mail($to, $subject, $text, $headers);
  }

  $text = 'name: '.$_POST['name'].'<br/>email: '.$_POST['email'].'<br/>phone: '.$_POST['phone'];

  header('Location: /');
  if( sendEmail($ADMIN_MAIL, 'vng form', $text) ) echo 'OK';
  else echo 'ERROR';
