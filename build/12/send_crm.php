<?php
    function ccurl($url, $method = "GET", $nobody = false, $header = true, $post_data = "", $auto_redirect = false){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/10.0 (compatible; CrawlBot/1.0.0)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 15);
        if ($nobody == true)
        {
            curl_setopt($ch, CURLOPT_NOBODY, true);
        }
    $html = curl_exec($ch);
    $code=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    if ($auto_redirect)
    {
    if ($code == 301 || $code == 302)
        {
            $matches = array();
            preg_match("/(Location:|URI:)[^(\n)]*/", $html, $matches);
            $url = trim(str_replace($matches[1],"",$matches[0]));
            $html = ccurl($url, $method, $nobody, $header);
        }
    }
    curl_close($ch);
    return $html;
    }


    $fromname = "vabez.ru";
    $subject = "? " . strip_tags(trim($_POST["subject"]));
    $receiveremail = "top.ba@ya.ru";



    $sendername = strip_tags(trim($_POST["sendername"]));
	$senderphone = strtr(strip_tags(trim($_POST["senderphone"])), array ('+7' => '8'));
    $senderemail = strip_tags(trim($_POST["senderemail"]));
    $sendersubject = strip_tags(trim($_POST["subject"]));
    $sendermessage = strip_tags(trim($_POST["sendermessage"]));

    $camtype = strip_tags(trim($_POST["cam-type"]));
    $camcount = strip_tags(trim($_POST["cam-count"]));
    $camlocation = strip_tags(trim($_POST["cam-location"]));


    $textareafiles = $_POST["textareafiles"];


    if (empty($_POST["subject"])) {
        $errors[] = "�� ��� ���� ���������";
    }

    if (!$sendername) {
        $errors[] = "������� ���� ���";
    }
    elseif (
        strlen($sendername) < 2) { $errors[] = "��� �� ������ ���� ������ 2-� ��������";
    }


    if (!$senderphone) {
        $errors[] = "�������� ���������� �������";
    }
    elseif (strlen($senderphone) < 7) {
        $errors[] = "����� �������� ������ ���� �� ������ 7 ����";
    }


    if (!empty($_POST["senderemail"])) {
        if (!filter_var($senderemail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "������� ���������� e-mail �����";
        }
    }

    $message = '';
    if ($sendername) {$message .= '<strong>���:</strong> '. $sendername .'<br>';}
    if ($senderphone) {$message .= '<strong>����� ���.:</strong> '. $senderphone .'<br>';}
    if ($senderemail) {$message .= '<strong>e-mail:</strong> '. $senderemail .'<br><br>';}
    if ($sendersubject) {$message .= '<strong>����:</strong> '. $sendersubject .'<br>';}
    if ($sendermessage) {$message .= '<strong>���������:</strong> '. $sendermessage .'<br>';}

    if ($camtype) {$message .= '<br><strong>�������� ����������:</strong> '. $camtype .'<br>';}
    if ($camcount) {$message .= '<strong>���������� ����������:</strong> '. $camcount .'<br>';}
    if ($camlocation) {$message .= '<strong>������������ ����������:</strong> '. $camlocation .'<br>';}

    if ($textareafiles) {$message .= '<br><strong>������������� �����:<br></strong> '. $textareafiles .'<br>';}

    $message .= '<hr><p style="margin: 0px; font-size: 0.8em; color: #959595;">IP ����c: <b>'. $_SERVER['REMOTE_ADDR'] .'</b></p>
    <p style="margin: 0px; font-size: 0.8em; color: #959595">User Agent: '. $_SERVER['HTTP_USER_AGENT'] .'</p>';

    /*************************************************************************/

    $response = array('state' => '', 'message' => '');
    $errortext = "";

    if ($errors) {
        foreach ($errors as $error) {
            $errortext .= '<li>' . $error . "</li>";
        }
        $response['state'] = 'error';
        $response['message'] = '<div class="error">��� �������� ��������� �������� ��������� ������:<br><ul>' . $errortext . '</ul></div>';
        echo json_encode($response);
    } else {


        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "Content-Type: text/html; charset=utf-8\r\n";
        $headers .= "From: " . $fromname . " <from@example.com>\r\n";

        if (mail($receiveremail, $subject, $message, $headers)) {
            $response['state'] = 'success';
            $response['message'] = '<p class="success">���� ��������� ������� ����������</p>';

            echo json_encode($response);
        } else {
            $response['state'] = 'error';
            $response['message'] = '<p class="error">������!</p>';

            echo json_encode($response);
        }
		


        /*
         *
        $comment = "";
        if (isset($camtype) && !empty($camtype))
        {
            $comment .= "�������� ����������: ".$camtype;
        }
        if (isset($camcount) && !empty($camcount))
        {
            $comment .= "<div>���������� ����������: ".$camcount."</div>";
        }
        if (isset($camlocation) && !empty($camlocation))
        {
            $comment .= "<div>������������ ����������: ".$camlocation."</div>";
        }
        if (isset($sendermessage) && !empty($sendermessage))
        {
            $comment .= "<div>���������: ".$sendermessage."</div>";
        }
        if (isset($textareafiles) && !empty($textareafiles))
        {
            $comment .= "<div>������������� �����: ".$textareafiles."</div>";
        }
        if (isset($comment) && !empty($comment))
        {
            $lead['COMMENTS'] = $comment;
        }
        //Bitrix24
        require_once('ibitrix24/ibitrix24.php');
        require_once('ibitrix24/config.php');
        $lead_data = array(
            'TITLE' => '����� ������ vabez.ru',
            'SOURCE_ID' => '4',
            'NAME' => $sendername,
            'EMAIL_WORK' => $senderemail,
            'PHONE_MOBILE' => strtr(preg_replace('/[^\d+]/', '', $_POST["senderphone"]),  array('+7' => '8')),
            'UF_CRM_1431770129' => IBITRIX24_getGeo(),
            'COMMENTS' => $comment,
            'UF_CRM_1431878940' => $sendersubject,
        );
        IBITRIX24_LeadAdd(BITRIX24_HOST, BITRIX24_LOGIN, BITRIX24_PASS, $lead_data);
        //End_Bitrix24


        */
    }

?>


<?

define('CRM_HOST', 'dimaba.bitrix24.ru'); // ����� ��� �������
define('CRM_PORT', '443'); 
define('CRM_PATH', '/crm/configs/import/lead.php'); 
define('CRM_LOGIN', 'Di.barbashin@gmail.com');  // �����
define('CRM_PASSWORD', '457diMA25'); // ������

/********************************************************************************************/

// POST processing
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $leadData = $_POST;
	$banner = "���������";
    $metka = "����� ���������: ������ ���������"; // �������� ����, ������������ �������
    // �������� ������ �� ����� � ������ �������� ����
    $postData = array(
        'TITLE' => $metka, 
        'NAME' => $banner, 
        'EMAIL_WORK' =>$leadData['senderemail'],
    );

    // �����������, �������� ������ � ������
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
        // ��������� � ������� ������ � ������� �� �����
        $strPostData = '';
        foreach ($postData as $key => $value)
            $strPostData .= ($strPostData == '' ? '' : '&').$key.'='.urlencode($value);
            $str = "POST ".CRM_PATH." HTTP/1.0\r\n";
            $str .= "Host: ".CRM_HOST."\r\n";
            $str .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $str .= "Content-Length: ".strlen($strPostData)."\r\n";
            $str .= "Connection: close\r\n\r\n";

        $str .= $strPostData;

        // ���������� ������ � ��� �������
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