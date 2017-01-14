<?php

    $leadData = $_POST;
    // Получаем данные из форм и сохраняем в массив
	
	$name = $leadData['custom_U5049'];
	$phone = $leadData['custom_U5053'];
	
    
    $name = iconv('utf-8', 'windows-1251', $name);
	$phone = iconv('utf-8', 'windows-1251', $phone);
    $ch = curl_init("http://sms.ru/sms/send");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(

        "api_id"    =>  "13B0F5E5-5CD7-9EB0-A737-DE3408C9B963",
        "to"        =>  "77772375042",
        "text"      =>  iconv("windows-1251","utf-8","New lead! $name,Tel: $phone")

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

    $metka = "Сайт: Дизайн интерьера"; // Название лида, обязательное условие
    // получаем данные из полей и задаем название лида
    $postData = array(
        'TITLE' => $metka, 
        'NAME' => $leadData['custom_U5049'], 
        'PHONE_WORK' =>$leadData['custom_U5053'],
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
/* 	
If you see this text in your browser, PHP is not configured correctly on this hosting provider. 
Contact your hosting provider regarding PHP configuration for your site.

PHP file generated by Adobe Muse CC 2015.1.2.344
*/

require_once('form_process.php');

$form = array(
	'subject' => 'Отправка Форма Дизайн интерьера (Рассчитать КОМФОРТ)',
	'heading' => 'Отправка новой формы',
	'success_redirect' => '',
	'resources' => array(
		'checkbox_checked' => 'Отмечено',
		'checkbox_unchecked' => 'Флажок не установлен',
		'submitted_from' => 'Формы, отправленные с веб-сайта: %s',
		'submitted_by' => 'IP-адрес посетителя: %s',
		'too_many_submissions' => 'Недопустимо высокое количество отправок с этого IP-адреса за последнее время',
		'failed_to_send_email' => 'Не удалось отправить сообщение эл. почты',
		'invalid_reCAPTCHA_private_key' => 'Недействительный закрытый ключ reCAPTCHA.',
		'invalid_field_type' => 'Неизвестный тип поля \'%s\'.',
		'invalid_form_config' => 'Недопустимая конфигурация поля \"%s\".',
		'unknown_method' => 'Неизвестный метод запроса сервера'
	),
	'email' => array(
		'from' => 'di.zverev@gmail.com',
		'to' => 'di.zverev@gmail.com'
	),
	'fields' => array(
		'custom_U5049' => array(
			'order' => 1,
			'type' => 'string',
			'label' => 'Введите имя*',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Введите имя*\' не может быть пустым.'
			)
		),
		'custom_U5053' => array(
			'order' => 2,
			'type' => 'string',
			'label' => 'Введите телефон*',
			'required' => true,
			'errors' => array(
				'required' => 'Поле \'Введите телефон*\' не может быть пустым.'
			)
		)
	)
);

process_form($form);
?>
