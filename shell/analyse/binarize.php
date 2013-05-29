<?php
ini_set("display_errors", E_ALL);
include('../func.inc.php');

if($argc < 5) {
	echo "Usage: $argv[0] \$imgpath \$imgtype \$relation \$avg\n";
	exit(-1);
}

$path = '../images/learn-sample/phpwind/'; $type = 'png'; $avg = 70; $relation = '<';
//$path = './17php/'; $type = 'png'; $avg = 1; $relation = '<';
$rgba = 'rgba';
//$rgba = 'r';
$path = $argv[1];
$type = $argv[2];
$relation = $argv[3];
$avg = $argv[4];
//echo "binarize($path, $type, $avg, $relation)";		// 分析目录下的图片文件的像素
binarize($path, $type, $avg, $relation);		// 分析目录下的图片文件的像素
exit;
//learn($path, $type, $avg, $relation);	// 识别目录下的图片文件

function binarize($path, $type, $avg, $relation) {
	$types = array('gif', 'png', 'jpg');
	if(!in_array($type, $types)) {
		$err_msg = 'file('.__FILE__.'); function('.__FUNCTION__."); error(improper parameter: $type );";
		exit($err_msg);
	}

	$img_files = glob($path.'/*.'.$type);	// read pic from directory
	if(!count($img_files)) {
		$err_msg = 'file('.__FILE__.'); function('.__FUNCTION__."); error(no '$type' files in path: '$path' );";
		exit($err_msg);
	}
	foreach($img_files as $img_file) {
		$types = array(1=>'gif', 2=>'jpeg', 3=>'png');
		$size = getimagesize($img_file);
		if(!array_key_exists($size[2], $types)) { throw new Exception('improper peremeter: $size["mime"]'); }
		// 输出图像的rgb信息
		//print_rgb($img_file, $rgba, $x_end = 120);

		// 二值化
		$img_array = binarize_by_rgbavg($img_file, $avg, $relation);

		// 输出整个二值化数组
		print_binary_array($img_array, $a='O', $b='_');
		// 输出降噪后的整个二值化数组
		drop_array_noise($img_array);
		print_binary_array($img_array, $a='O', $b='_');

		// 切割图像
		$char_end_array = array_char_end($img_array);	// todo : add content to return value
		$y_height = count($img_array);
		foreach($char_end_array['numx'] as $start => $end) {
			$x_length = $end - $start;
			$x_start = $start;
			$part_array = slice_array($img_array, $x_length, $y_height, $x_start, $y_start=0);
			// 输出切割出来的单个二值化数组
			print_binary_array($part_array, $a='O', $b='_');
			// 对齐一个二值化数组，并输出之
			//$align_arr = align_arr($part_array);
			//print_binary_array($align_arr, $a='O', $b='_');
		}
	}
}

?>
