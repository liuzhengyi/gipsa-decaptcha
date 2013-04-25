<?php
require_once('./includes/lib/func.inc.php');
$basefilename = basename(__FILE__);
$h2 = substr($basefilename, 0, strpos($basefilename, '.'));

ini_set("display_errors", 1);
require('includes/functions/study.php');
if(isset($_POST['start_study'])) {
// todo: check arguments and study
// check arguments
// var_dump($_POST);	// todo comment debug
	if(empty($_POST['binarize-parameter-rgba'])
		|| empty($_POST['binarize-parameter-relation'])
		|| empty($_POST['binarize-parameter-value'])
	) {
		$err_msg[] = '二值化参数不可缺少。';
	}
	if(empty($_POST['img_files'])) {
		$err_msg[] = '您没有提交任何学习信息';
	}
	$results = $_POST['img_files'];	// todo is it safe?
	$type = 'png';	// todo modify to dynamic
	$map_file = './maps/pps.map.php';	// todo modify to dynamic
	$bi_parameters['avg'] = '66';	// todo modify to dynamic
	$bi_parameters['relation'] = '==';	// todo modify to dynamic
	if(!empty($_POST['process-dropnoise'])) {$pro_parameters['dropnoise_flag'] = true;} else {$pro_parameters['dropnoise_flag'] = false; }
	if(!empty($_POST['process-divide'])) {$pro_parameters['divide_flag'] = true;} else {$pro_parameters['divide_flag'] = false; }
	if(!empty($_POST['process-align'])) {$pro_parameters['align_flag'] = true;} else {$pro_parameters['align_flag'] = false; }
	$ret_value = study_pro($results, $type, $map_file, $bi_parameters, $pro_parameters);
	if(0 === $ret_value) {
		echo '<p>study finished</p><p><a href="study.php">return</a></p>';
	} else {
		echo '<p>an error occured, the study may failed</p><p><a href="study.php">return</a></p>';
	}
	exit();
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
	 请选择要学习的验证码：
	<select name="captcha-source">
		<option value="pps">pps</option>
		<!-- 暂不开放
		<option value="njaumy">njaumy</option>
		<option value="phpwind">phpwind</option>
		-->
	</select>
	<input type="submit" name="list" value="点此列出相关验证码图片" />
	</form>
	<hr />

	<br />
	<form action="#" method="post" >
	<h3>学习参数和过程选择</h3>
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
			if(66==$i) { echo "<option value=\"$i\" selected=\"selected\" >$i</option>";
			} else { echo "<option value=\"$i\">$i</option>"; }
		} ?>
	</select>
	<br />

	<br />
	学习过程：
	<label for="process-1"><input type="checkbox" name="process-binarize" value="true" id="process-1" checked="checked" disabled="disabled" />二值化(必选)</label>&nbsp;&nbsp;&nbsp;
	<label for="process-2"><input type="checkbox" name="process-dropnoise" value="true" id="process-2" <?php if(!empty($_GET['process-dropnoise'])) {echo 'checked="checked"';} ?> />降噪</label>&nbsp;&nbsp;&nbsp;
	<label for="process-3"><input type="checkbox" name="process-divide" value="true" id="process-3" <?php if(!empty($_GET['process-divide'])){echo 'checked="checked"';} ?> />切割</label>
	<label for="process-4"><input type="checkbox" name="process-align" value="true" id="process-4" <?php if(!empty($_GET['process-align'])){echo 'checked="checked"';} ?> />对齐</label>&nbsp;&nbsp;&nbsp;
	<hr />
	<h3> 验证码图片列表： </h3>
	<?php
	// 根据$_GET['captcha-source'] 给出相关验证码图片
	if(empty($_GET['captcha-source'])) {
		echo '<p class="hint">请先在页面顶端选择要学习的验证码，并点击列出相关图片按钮。</p>';
		exit();
	} else if ('pps' == $_GET['captcha-source']) {
		$img_dir = './images/learn-sample/pps/';
	} else if ('njaumy' == $_GET['captcha-source']) {
		$img_dir = './images/learn-sample/njaumy/';
	} else if ('phpwind' == $_GET['captcha-source']) {
		$img_dir = './images/learn-sample/phpwind/';
	}
	$img_files = glob($img_dir.'*');
	//var_dump($img_files);
	echo '<ol>';
	foreach($img_files as $img_file) {
		echo '<li><img src="'.$img_file.'"><input type="text" name="img_files['.$img_file.']" /></li><br />'."\n";
	}
	echo '</ol>';
	?>
	<input type="hidden" name="captcha-source" value="<?php echo $_GET['captcha-source']?>" />
	<input type="submit" name="start_study" value="开始学习" />
	</form>
	</div> <!-- end of DIV parameters-->

	<div id="main-content">
	<p>here is main-content</p>
	</div> <!-- end of DIV main-content -->

</body>
</html>
