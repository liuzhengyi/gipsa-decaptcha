<?php
$basefilename = basename(__FILE__);
$h2 = substr($basefilename, 0, strpos($basefilename, '.'));
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

	<div id="main-content">
	<p>here is main-content</p>
	</div> <!-- end of DIV main-content -->

	<!--
	<div id="origin_image">
	<form action="#" method="post">
	请选择验证码来源：<br /><select>
	<option value="pps" name="pps">pps</option>
	<option value="njaumy" name="njaumy" >njaumy</option>
	<option value="phpwind" name="phpwind" >phpwind</option>
	</select>
	</form>
	</div>
	-->

	<!--
	<div id="">
	<label for="">Directory:<input type="text" name="directory" id="directory"/></label>
	-->
</body>
</html>
