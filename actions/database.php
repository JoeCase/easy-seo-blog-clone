<?php
require_once 'config.php';

function connect(){
	$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	/* check connection */
	if (mysqli_connect_errno()) {
		return false;
	}
	return $connection;
}

function close($mysqli){
	$mysqli->close();
}

function clearUTF($s)
{
	setlocale(LC_ALL, 'en_US.UTF-8');
	$r = '';
	$error_level = error_reporting();
	for ($i = 0; $i < mb_strlen($s, 'UTF-8'); $i++)
	{
		$ch2 = mb_substr($s, $i, 1, 'UTF-8');
		
		error_reporting(0); // do not show error when unconvinient character is in $ch2 => remove empty string, later it is chacked
		$ch1 = iconv('UTF-8', 'us-ascii//TRANSLIT', $ch2);
		error_reporting($error_level);
		
		$ch2 = ($ch1 && strlen($ch1) > 0 && $ch1 != '?') ? $ch1 : azbuka_latinka($ch2);
		
		$ch1 = mb_strtolower(preg_replace(array('/[^a-zA-Z0-9%\s\+\-\!\ˇ\~\´\¨\§\°\`\@\#\$\^\&\*\(\)\=\{\}\|\?\>\<\:\;\,\.\\\'\"\\\\\/\[\]\_]/', '/[%\s\+\-\!\ˇ\~\´\¨\§\°\`\@\#\$\^\&\*\(\)\=\{\}\|\?\>\<\:\;\,\.\\\'\"\\\\\/\[\]\_]+/'), array('', '-'), $ch2));
		
		$ch2 = ($ch1 && strlen($ch1) > 0) ? $ch1 : urlencode($ch2);

		$r .= $ch2;
	}
    $r = str_replace("%","",$r);
	return strlen($r) > 0 ? $r : 'n-a';
}

/** Převod azbuky na latinku podle GOST 16876-71
 * @param string text v azbuce
 * @return string text v latince
 * @copyright Jakub Vrána, http://php.vrana.cz/
 */
function azbuka_latinka($s) {
	return strtr($s, array(
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'jj', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'eh', 'ю' => 'ju', 'я' => 'ja',
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'JO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I', 'Й' => 'JJ', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'KH', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SHH', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'EH', 'Ю' => 'JU', 'Я' => 'JA',
	));
}

function get_slug($str)
{
	return clearUTF($str);
}

?>