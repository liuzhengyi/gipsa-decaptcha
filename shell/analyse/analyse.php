<?php
ini_set("display_errors", E_ALL);
include('../func.inc.php');


$path = '../images/learn-sample/phpwind/'; $type = 'png';
//$path = './17php/'; $type = 'png'; $avg = 1; $relation = '<';
$rgba = 'rgba';
//$rgba = 'r';
if( 2 == $argc && 'help' == $argv[1]) {	// output help message
	$err_msg = "Usage: $argv[0] \$path='$path' \$type='$type' \$rgba='$rgba' \n";
	exit($err_msg);
}
if($argc < 3) {
	$err_msg = "Usage: $argv[0] path type\n";
	exit($err_msg);
} else {
	$path = $argv[1];
	$type = $argv[2];
	analyse_files($path, $type, $rgba);		// 分析目录下的图片文件的像素
	//learn($path, $type, $avg, $relation);	// 识别目录下的图片文件
}

function analyse_files($path, $type, $rgba) {
	$types = array('gif', 'png', 'jpg');
	if(!in_array($type, $types)) { throw new Exception('improper peremeter: $type'); }

	$img_files = glob($path.'/*.'.$type);	// read pic from directory
	if(!count($img_files)) { throw new Exception("no '$type' files in path: $path"); }
	foreach($img_files as $img_file) {
		$types = array(1=>'gif', 2=>'jpeg', 3=>'png');
		$size = getimagesize($img_file);
		if(!array_key_exists($size[2], $types)) { throw new Exception('improper peremeter: $size["mime"]'); }
		// 输出图像的rgb信息
		print_rgb($img_file, $rgba, $x_end = 120);

		/*
		// 二值化
		$img_array = binarize_by_rgbavg($img_file, $avg=100, $relation='<');

		// 输出整个二值化数组
		//print_binary_array($img_array, $a='O', $b='_');

		// 切割图像
		$char_end_array = array_char_end($img_array);	// todo : add content to return value
		foreach($char_end_array['numx'] as $start => $end) {
			$x_length = $end - $start;
			$y_height = 100;
			$x_start = $start;
			$part_array = slice_array($img_array, $x_length, $y_height, $x_start, $y_start=0);
			// 输出切割出来的单个二值化数据
			$align_arr = align_arr($part_array);
			print_binary_array($part_array, $a='O', $b='_');
			print_binary_array($align_arr, $a='O', $b='_');

			// 将图像二值数据按像素向上对齐
		}
		*/
	}
}

?>
