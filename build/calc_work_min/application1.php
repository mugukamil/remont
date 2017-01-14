<?php


  $leadData = $_POST['DATA'];
  // Получаем данные из форм и сохраняем в массив
	$phone = $leadData['PHONE_WORK'];
	$name = $leadData['NAME'];
	$EMAIL_WORK = $leadData['UF_CRM_1465545606'];
	$UF_CRM_1465545465 = $leadData['UF_CRM_1465545465'];
	$UF_CRM_1465545310 = $leadData['UF_CRM_1465545310'];
	$UF_CRM_1465545550 = $leadData['UF_CRM_1465545550'];



  $phone = iconv('utf-8', 'windows-1251', $phone);
  $name = iconv('utf-8', 'windows-1251', $name);

	$EMAIL_WORK = iconv('utf-8', 'windows-1251', $EMAIL_WORK);
	$UF_CRM_1465545465 = iconv('utf-8', 'windows-1251', $UF_CRM_1465545465);
	$UF_CRM_1465545310 = iconv('utf-8', 'windows-1251', $UF_CRM_1465545310);
	$UF_CRM_1465545550 = iconv('utf-8', 'windows-1251', $UF_CRM_1465545550);


    $ch = curl_init("http://sms.ru/sms/send");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(

        "api_id"    =>  "13B0F5E5-5CD7-9EB0-A737-DE3408C9B963",
        "to"        =>  "77772375042",
        "text"      =>  iconv("windows-1251","utf-8","$name,Tel: $phone,$EMAIL_WORK,$UF_CRM_1465545465,$UF_CRM_1465545310,$UF_CRM_1465545550")

    ));
    $body = curl_exec($ch);
    curl_close($ch);
?>
<!-- API SMS 2d0aa9db-6874-e704-c168-06747c836224-->
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Страница благодарности</title>
<link href="css/style.css" type="text/css" rel="stylesheet"/>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
</head>


<?php
function show_form()
{
?>

<?
}
function complete_mail() {

    $leadData = $_POST['DATA'];
    // Получаем данные из форм и сохраняем в массив
    $postData = array(

        'Имя:' => $leadData['NAME'],
        'Телефон' => $leadData['PHONE_WORK'],
        'Почта:' => $leadData['EMAIL_WORK'],
        'Комментарий:' => $leadData['COMMENTS'],
		'Авторский надзор:' => $leadData['COMPANY_TITLE'],
		'Тип ремонта:' => $leadData['LAST_NAME'],
		'Строительная бригада:' => $leadData['ADDRESS'],
    );
        $strPostData = '';
        foreach ($postData as $key => $value)
            $strPostData .= ($strPostData == '' ? '' : ' ').$key.' '.($value)."<br>";
        	$str .= "<center><div style='border: 4px double black;'><h2>Заявка: Дизайн Интерьера</h2></div></center><br/><hr><div style='border: 6px black;'><p><strong>".($strPostData)."</strong></p></div>\r\n";
		require 'class.phpmailer.php'; //Дополнительный скрипт для отправки файла, можете не открывать, просто положите рядом с index.html и этим файлом.
		$mail = new PHPMailer();
        $mail->From = 'Di.zverev@gmail.com';      // от кого
        $mail->FromName = 'Di.zverev@gmail.com';   // от кого Имя
        $mail->AddAddress('Di.zverev@gmail.com', 'Дима'); // кому Ваша почта, Имя
        $mail->IsHTML(true);        // формат письма HTML
        $mail->Subject = "Новая заявка: Ремонт коттеджей";  // тема письма
        // если есть файл, то прикрепляем его к письму
        if(isset($_FILES['upl'])) {
                 if($_FILES['upl']['error'] == 0){
                    $mail->AddAttachment($_FILES['upl']['tmp_name'], $_FILES['upl']['name']);
                 }
        }
        $mail->Body = $str;
        // отправляем наше письмо
        if (!$mail->Send()) die ('Mailer Error: '.$mail->ErrorInfo);
}

if (!empty($_POST['submit'])) complete_mail();
else show_form();

?>

<?

define('CRM_HOST', 'Dz-pro.bitrix24.ru'); // Домен срм системы
define('CRM_PORT', '443');
define('CRM_PATH', '/crm/configs/import/lead.php');
define('CRM_LOGIN', 'Di.zverev@gmail.com');  // логин
define('CRM_PASSWORD', 'agonda2011'); // пароль

/********************************************************************************************/

// POST processing
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $leadData = $_POST['DATA'];

    $metka = "Сайт: Ремонт коттеджей"; // Название лида, обязательное условие
    // получаем данные из полей и задаем название лида
    $postData = array(
        'TITLE' => $metka,
        'NAME' => $leadData['NAME'],
        'PHONE_WORK' =>$leadData['PHONE_WORK'],
        'UF_CRM_1465545606' => $leadData['UF_CRM_1465545606'],
        'EMAIL_WORK' => $leadData['EMAIL_WORK'],
		    'UF_CRM_1465545465' => $leadData['UF_CRM_1465545465'],
		    'UF_CRM_1465545310' => $leadData['UF_CRM_1465545310'],
		    'UF_CRM_1465545550' => $leadData['UF_CRM_1465545550'],
    );

    // авторизация, проверка логина и пароля
    if (defined('CRM_AUTH'))
    {
        $postData['AUTH'] = CRM_AUTH;
    }
    else
    {
        $postData['LOGIN'] = CRM_LOGIN;
        $postData['PASSWORD'] = CRM_PASSWORD;
    }

    $fp = fsockopen("ssl://".CRM_HOST, CRM_PORT, $errno, $errstr, 30);
    if ($fp)
    {
        // формируем и шифруем строку с данными из формы
        $strPostData = '';
        foreach ($postData as $key => $value)
            $strPostData .= ($strPostData == '' ? '' : '&').$key.'='.urlencode($value);
            $str = "POST ".CRM_PATH." HTTP/1.0\r\n";
            $str .= "Host: ".CRM_HOST."\r\n";
            $str .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $str .= "Content-Length: ".strlen($strPostData)."\r\n";
            $str .= "Connection: close\r\n\r\n";

        $str .= $strPostData;

        // отправляем запрос в срм систему
        fwrite($fp, $str );
        $result = '';
        while (!feof($fp))
        {
            $result .= fgets($fp, 128);
        }
        fclose($fp);

        $response = explode("\r\n\r\n", $result);
        $output = '<pre>'.print_r($response[1], 1).'</pre>';
    }
    else
    {
        echo 'Connection Failed! '.$errstr.' ('.$errno.')';
    }
}
else
{
    $output = '';

}

?>


<script type="text/JavaScript">

    function doRedirect() {
        atTime = "15000";
        toUrl = "http://a-ds.kz/calc_work_min/";

        setTimeout("location.href = toUrl;", atTime);
    }

</script>

<body onload="doRedirect();">
<!-- То, что будет показываться на странице благодарности -->

<div class="thanks"><h2>Спасибо, Ваша заявка принята!</h2><p>Наш менеджер свяжется с Вами в течении 15 минут</p>
<p>Если ваша заявка поступила после 21:00, мы обязательно свяжемся с Вами<br/> на следующий день после 10:00.</p><a href="http://a-ds.kz/calc_work_min/">Обновить</a></div>

</body>
</html>
