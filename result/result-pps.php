<html>
<head>
	<title>识别结果</title>
</head>
<body>
<?php
include('index.inc.php');
?>

	<div id="pps">
	<h1>pps</h1>
	<ol>
<?php
ini_set("display_errors", 1);
	$images = glob('../images/test/pps/*.png');
	include('./pps.php');
	foreach($images as $image) {
		$code = tell($image, $type, $avg, $relation);	// 识别目录下的图片文件
		echo "\t \t <li><img src=\"$image\" > ........... $code </li> \n";
	}
?>
	</ol>
	</div> <!-- end of DIV pps -->
</body>
</html>
