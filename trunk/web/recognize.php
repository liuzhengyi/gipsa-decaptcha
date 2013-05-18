<?php
ini_set("display_errors", 1);
require_once('./includes/lib/func.inc.php');
$basefilename = basename(__FILE__);
$h2 = substr($basefilename, 0, strpos($basefilename, '.'));

require('includes/functions/recognize.php');
if(isset($_GET['start_recognize']) && !empty($_GET['captcha-source'])) {
switch ($_GET['captcha-source']) {
	case 'njaumy':
		$avg = 66;
		$relation = '<';
		$type = 'jpg';
		$img_files = glob('./images/test/njaumy/*.'.$type);
		$map_file = './maps/njaumy.map.php';
		$align = true;
		break;
	case 'pps':
		$avg = 66;
		$relation = '==';
		$type = 'png';
		$img_files = glob('./images/test/pps/*.'.$type);
		$map_file = './maps/pps.map.php';
		$align = false;
		break;
	default:
		break;
}
$result_arr = array();
foreach($img_files as $img_file) {
	$result_arr[$img_file] = recognizebyfile($img_file, $type, $avg, $relation, realpath($map_file), $align);
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmls="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>验证码识别系统-刘正义</title>
	<link rel="stylesheet" href="styles.css" type="text/css" />
</head>
<body>
	<div id="header">
	<?php include('./includes/uiparts/header.inc.php'); ?>
	</div>

	<form action="#" method="get">
	请选择要识别的验证码类别：
	<select name="captcha-source">
		<option value="njaumy">njaumy</option>
		<option value="pps">pps</option>
		<option value="phpwind">phpwind</option>
	</select>
	<br />
	<input type="submit" name="start_recognize" value="开始识别" />
	</form>
	<div id="main-content">
	<p>here is main-content</p>
	<?php
	if(isset($_GET['start_recognize']) && !empty($_GET['captcha-source'])) {
		echo '<ol>';
		foreach($result_arr as $k => $v) {
			echo '<li><img src="'.$k.'">-------'.$v.'</li>';
		}
		echo '</ol>';
	}
	?>
	</div> <!-- end of DIV main-content -->
</body>
</html>
