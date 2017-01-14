<?php


    $leadData = $_POST;
    // Получаем данные из форм и сохраняем в массив
	$phone = $leadData['email'];
	$name = 'Новый подписчик.';
    $phone = iconv('utf-8', 'windows-1251', $phone);
    $name = iconv('utf-8', 'windows-1251', $name);
    $ch = curl_init("http://sms.ru/sms/send");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(

        "api_id"    =>  "13B0F5E5-5CD7-9EB0-A737-DE3408C9B963",
        "to"        =>  "77772375042",
        "text"      =>  iconv("windows-1251","utf-8","New subscriber. Phone: $phone")

    ));
    $body = curl_exec($ch);
    curl_close($ch);
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
    $leadData = $_POST;
	$banner = "Подписчик";
    $metka = "Новый подписчик: Дизайн интерьера"; // Название лида, обязательное условие
    // получаем данные из полей и задаем название лида
    $postData = array(
        'TITLE' => $metka, 
        'NAME' => $banner, 
        'EMAIL_WORK' =>$leadData['email'],
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

<?php
// если несколько получателей
  // обратите внимание на запятую
$to = 'di.zverev@gmail.com';

// тема письма
$subject = 'НОВЫЙ ПОДПИСЧИК: 10 ОШИБОК РЕМОНТА';

// текст письма
$message = '<body style="background-color: #e8c22c; border: 4px double black; border-radius: 10px"><center><h3>
*--------------------------------------------------------------------*<br></h3>
<h1 style="color: red;">НОВЫЙ ПОДПИСЧИК!</h1>
<h2 style="color: black;">ТЕМА: 10 ОШИБОК РЕМОНТА</h2>
<h2 style="color: blue;">
E-MAIL: <b>' . $_POST['email'] . '</b>
</h2>
<h3>
*--------------------------------------------------------------------*<br></h3>
<br>
<br>
<br>
Разработчик, написать письмо: <a href="mailto:di.zverev@gmail.com"><b>Дмитрий Ба</b></a><br>
<p style="color: green; font-size: 11pt;">Поддержка формы заказа,<br> веб-дизайн, разработка сайтов <br>и landing page.</p></center>
</body>'
;

// Для отправки HTML-письма должен быть установлен заголовок Content-type
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=cp-1251' . "\r\n"; 

// Дополнительные заголовки
$headers .= 'To: FOR ME>' . "\r\n"; // Свое имя и email
$headers .= 'From: '  . $_POST['email'] . '<' . $_POST['email'] . '>' . "\r\n";


// Отправляем
mail($to, $subject, $message, $headers);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="cp-1251">
<title>Страница благодарности</title>
<link href="css/style_tst.css" type="text/css" rel="stylesheet"/>
<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
</head>


<script type="text/JavaScript">

    function doRedirect() {
        atTime = "15000";
        toUrl = "http://a-ds.kz/";

        setTimeout("location.href = toUrl;", atTime);
    }

</script>
<body onload="doRedirect();">
<!-- То, что будет показываться на странице благодарности -->

<div class="thanks"><h2><b>Спасибо за подписку!</b></h2>
<img src="http://a-ds.kz//banner-podpiska/img/mail.png" width="80px">
<p>Теперь Вы будете получать на почту<br/>
полезную информацию о проведении ремонта<br/> и не допустите 10 типичных ошибок!</p><a href="http://a-ds.kz/">Обновить</a></div>

</body>
</html>