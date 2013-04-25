<?php
/* 
 *
 */
//require_once('../lib/func.inc.php');
//$path = './images/pps/'; $type = 'png'; $avg = 66; $relation = '==';
//$path = './njaumy/'; $type = 'jpg'; $avg = 66; $relation = '<';
//$rgba = 'rgba';
//analyse_files($path, $type, $rgba);		// 分析目录下的图片文件的像素
//learn($path, $type, $avg, $relation);	// 识别目录下的图片文件
//$file = '../images/pps/1.png';
//$str = tell($file, $type, $avg, $relation);	// 识别目录下的图片文件
//echo $str;

function recognizebyfile($file, $type, $avg, $relation, $map_file, $align='') {

	// variables and lock file
	//$map_file = dirname(__FILE__).'/'.substr_replace(basename(__FILE__), '.map', -4, 0);
	$lock_file = $map_file.'.lock';
	$img_file = $file;

	if(!file_exists($lock_file)) {
		$err_msg = 'error: file('.__FILE__.'); function('.__FUNCTION__.'); lockfile('.$lock_file.') not exist, please learn before tell';
		exit($err_msg);
	}
	include($map_file);	// init $known_maps;
	//$known_maps = unserialize($known_maps_array_serial);

	// 识别前的基本处理

	// 检查图像类型	// todo 自动判别图像类型
	$types = array('gif', 'png', 'jpg');
	if(!in_array($type, $types)) {
		$err_msg = 'function: ('.__FUNCTION__.") error message (improper parameter: $type , will now exit.) \n";
		// throw new Exception('improper peremeter: $type');
		exit($err_msg);
	}

	$types = array(1=>'gif', 2=>'jpeg', 3=>'png');
	$size = getimagesize($img_file);
	if(!array_key_exists($size[2], $types)) { throw new Exception('improper peremeter: $size["mime"]'); }

	// 二值化
	$img_array = binarize_by_rgbavg($img_file, $avg, $relation, $size[0], $size[1]);
	// 降噪
	drop_array_noise($img_array);
	// 做分割标记
	$char_end_array = array_char_end($img_array);	// todo : add content to return value
	// 按照分割标记 取出单个字符 识别之
	$code = '';
	foreach($char_end_array['numx'] as $start => $end) {
		$x_length = $end - $start;
		$y_height = count($img_array);
		$x_start = $start;
		$part_array = slice_array($img_array, $x_length, $y_height, $x_start, $y_start=0);
		//print_binary_array($part_array, $a='O', $b='_');
		if($align) {
			$align_arr = align_arr($part_array);
			$array_str = array_to_str($align_arr);
		} else {
			$array_str = array_to_str($part_array);
		}
		if(array_key_exists($array_str, $known_maps)) {
			$code .= $known_maps[$array_str];
		} else {
			$code .= '_';
		}
	}
	return $code;
}
?>
