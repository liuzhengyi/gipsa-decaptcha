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
	<p>本页面介绍本项目</p>
	<p>本系统有两套接口，一套是基于web PHP的，也就是您现在看到的。<br />另一套是基于Shell模式下的PHP解释器的。</p>
	<p>目前本项目的进展情况为：<br />可以识别若干类没有变形的简单验证码和一类有形变的验证码。</p>
	<p>预期目标：<br />识别两类或以上有形变的验证码。</p>
	<p>功能:</p>
	<dl>
		<dt>分析页面(analyse):</dt>
		<dd>对验证码图像进行像素打印，二值化，降噪，切割，对齐等行为，并可视化打印，以期获得图像的规律。</dd>
		<dt>学习页面(study):</dt>
		<dd>按照分析页面得到的规律，设置一定的参数，人工协助学习样本。</dd>
		<dt>识别页面(recognize):</dt>
		<dd>根据学习页面得到的结果，识别测试数据。</dd>
	</dl>
	<p>目前功能尚不完善，有些功能只开放部分参数，其他参数使用默认值即可。</p>

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
