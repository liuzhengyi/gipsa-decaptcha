<?php
require_once('./includes/lib/func.inc.php');
$basefilename = basename(__FILE__);
$h2 = substr($basefilename, 0, strpos($basefilename, '.'));

ini_set("display_errors", 1);
require('includes/functions/analyse.php');	// todo modify analyse.php and require it
if(isset($_GET['start_analyse'])) {
// todo: analyse
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
	</div> <!-- end of DIV header -->

	<p>必选的行为：二值化。<br />可选的行为：输出rgba值， 降噪，切割，对齐。 </p>
	<div id="parameters">
	<form action="#" method="get" >
	 请选择要分析的验证码：
	<select name="captcha-source">
		<option value="njaumy">njaumy</option>
		<option value="pps">pps</option>
		<option value="phpwind">phpwind</option>
	</select>

	<br />
	二值化参数：
	<select name="binarize-parameter-rgba">
		<option value="a">RGB平均值</option>
		<option value="r">R值</option>
		<option value="g">G值</option>
		<option value="b">B值</option>
	</select>
	<select name="binarize-parameter-relation">
		<option value="<">&lt;</option>
		<option value=">">&gt;</option>
		<option value="==">=</option>
		<option value="<=">&lt;=</option>
		<option value=">=">&gt;=</option>
	</select>
	<select name="binarize-parameter-value">
		<?php
		for($i=0; $i < 256; $i++) {
			if(66==$i) {
				echo "<option value=\"$i\" selected=\"selected\" >$i</option>";
			} else {
				echo "<option value=\"$i\">$i</option>";
			}
		}
		?>
	</select>

	<br />
	请选择分析过程：<br />
	<label for="process-0"><input type="checkbox" name="process-outputrgba" value="true" id="process-0" />输出rgb信息</label>&nbsp;&nbsp;&nbsp;
	<label for="process-1"><input type="checkbox" name="process-binarize" value="true" checked="checked" disabled="disabled" id="process-1" />二值化(必选)</label>&nbsp;&nbsp;&nbsp;
	<label for="process-2"><input type="checkbox" name="process-dropnoise" value="true" id="process-2" />降噪</label>&nbsp;&nbsp;&nbsp;
	<label for="process-3"><input type="checkbox" name="process-align" value="true" id="process-3" />对齐</label>&nbsp;&nbsp;&nbsp;
	<label for="process-4"><input type="checkbox" name="process-divide" value="true" id="process-4" />切割</label>
	<br /><br /><br /><input type="submit" name="start_analyse" value="开始分析" />
	</form>
	</div> <!-- end of DIV parameters-->

	<div id="main-content">
	<?php
	if(isset($_GET['start_analyse'])) {
		echo '<pre>';
		//var_dump($_GET);
		analysebyfile('./images/test/njaumy/0.jpg', 'jpg', 66, '<');
		echo '</pre>';
	}
	?>
	<p>here is main-content</p>
	</div> <!-- end of DIV main-content -->

</body>
</html>
