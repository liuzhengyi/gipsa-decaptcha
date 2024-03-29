<?php
/* learn.php 学习验证码，人工参与
 * liuzhengyi 2013-05-27
 *
 * 学习结果以序列化的PHP数组形式存储在../maps/下对应文件中
 */
include('../includes/lib/func.inc.php');
include('./shell_study_config.php');
//$path = '../images/learn-sample/pps/'; $type = 'png'; $avg = 66; $relation = '==';
//$path = './njaumy/'; $type = 'jpg'; $avg = 66; $relation = '<';
if($argc < 2 || !is_string($argv[1])) {
	$err_msg = "Usage: $argv[0] CAPTCHA_NAME ";
	exit($err_msg);
}
$CAPTCHA_NAME = strtoupper($argv[1]);
//if(defined($CAPTCHA_NAME)) {
if(!defined(strval($CAPTCHA_NAME))) {
	$err_msg =  "no config about $argv[1] in shell_study_config found, please fill in the config file first. ";
	exit($err_msg);
}
$base = $argv[1];
$path = ${$base.'_test_path'};
$type = ${$base.'_type'};
$avg = ${$base.'_avg'};
$relation = ${$base.'_relation'};
$flags = ${$base.'_flags'};

recognize($path, $type, $avg, $relation, $flags); // 学习目录下的图片文件

function recognize($path, $type, $avg, $relation, $flags) {

	// variables and lock file // old style
	// $map_file = dirname(__FILE__).'/'.substr_replace(basename(__FILE__), '.map', -4, 0);
	// $map_file = dirname(__FILE__).'/'.substr_replace(basename(__FILE__), '.map', -4, 0);
	// $lock_file = $map_file.'.lock';

	// variables and lock file // new style
	// get the lock file and directory
	$tmp = strtok($path, "/");
	while ($tmp !== false) {
		$base_file = $tmp;
	    $tmp = strtok("/");
	}
	$map_file = '../maps/'.$base_file.'.map.php';	// todo move '../maps/' to global config
	$lock_file = $map_file.'.lock';
	if(!file_exists($lock_file)) {
		$err_msg = "no map files found. please run the study program before recognize. \n";
		exit($err_msg);
	} else if (!file_exists($map_file)) {
		$err_msg = "error, please delete the lock file $lock_file and try again.\n";
		exit($err_msg);
	}
	include($map_file);	// init $known_maps;

	// 识别的基本处理

	// 打开目录下指定类型的图像文件
	$types = array('gif', 'png', 'jpg');
	if(!in_array($type, $types)) {
		$err_msg = 'function: ('.__FUNCTION__.") error message (improper parameter: $type , will now exit.) \n";
		// throw new Exception('improper peremeter: $type');
		exit($err_msg);
	}
	$img_files = glob($path.'/*.'.$type);	// read pic from directory
	if(!count($img_files)) {
		$err_msg = 'function: ('.__FUNCTION__.") error message (no '$type' files in path: $path , will now exit.) \n";
		// throw new Exception($err_msg);
		exit($err_msg);
	}

	foreach($img_files as $img_file) {
		$types = array(1=>'gif', 2=>'jpeg', 3=>'png');
		$size = getimagesize($img_file);
		if(!array_key_exists($size[2], $types)) { throw new Exception('improper peremeter: $size["mime"]'); }
		// 打开图像
		$tmp_cmd = '$res = imagecreatefrom'.$types[$size[2]].'($img_file);';
		eval($tmp_cmd);
		$size = getimagesize($img_file);
		// 二值化
		$img_array = binarize_by_rgbavg($img_file, $avg, $relation, $size[0], $size[1]);
		//print_binary_array($img_array );
		//$pure_array = slice_array($img_array, $x_lenth=9*4, $y_height=10, $x_start=14, $y_start=6);
		//print_binary_array($pure_array );
		// 降噪
		drop_array_noise($img_array);
		print_binary_array($img_array, $a='O', $b='_');
		// 做分割标记
		$char_end_array = array_char_end($img_array);	// todo : add content to return value
		// 按照分割标记 打印出单个字符 识别或学习
		foreach($char_end_array['numx'] as $start => $end) {
			$x_length = $end - $start;
			$y_height = count($img_array);
			$x_start = $start;
			$part_array = slice_array($img_array, $x_length, $y_height, $x_start, $y_start=0);
			//print_binary_array($part_array, $a='O', $b='_');
			if($flags['align']) {
				$part_array = align_arr($part_array);
			}
			$array_str = array_to_str($part_array);
			if(array_key_exists($array_str, $known_maps)) {
				echo "{$known_maps[$array_str]} \n";
				continue;
			}
			//$data = readline('What is it ?');
			//$known_maps[$array_str] = $data;
			// 输出切割出来的单个二值化数据
			// print_binary_array($part_array, $a='O', $b='_');

		}
	}
}





function pps_tell($file, $type, $avg, $relation) {

	// variables and lock file
	$map_file = dirname(__FILE__).'/'.substr_replace(basename(__FILE__), '.map', -4, 0);
	$lock_file = $map_file.'.lock';
	$img_file = $file;

	if(!file_exists($lock_file)) {
		$err_msg = 'error: function('.__FUNCTION__.'); lockfile not exist, please learn before tell';
		exit($err_msg);
	}
	include($map_file);	// init $known_maps;
	// $known_maps = unserialize($known_maps_array_serial);

	// 识别前的基本处理

	// 打开目录下指定类型的图像文件
	$types = array('gif', 'png', 'jpg');
	if(!in_array($type, $types)) {
		$err_msg = 'function: ('.__FUNCTION__.") error message (improper parameter: $type , will now exit.) \n";
		// throw new Exception('improper peremeter: $type');
		exit($err_msg);
	}
	/*
	$img_files = glob($path.'/*.'.$type);	// read pic from directory
	if(!count($img_files)) {
		$err_msg = 'function: ('.__FUNCTION__.") error message (no '$type' files in path: $path , will now exit.) \n";
		// throw new Exception($err_msg);
		exit($err_msg);
	}
	*/

	$types = array(1=>'gif', 2=>'jpeg', 3=>'png');
	$size = getimagesize($img_file);
	if(!array_key_exists($size[2], $types)) { throw new Exception('improper peremeter: $size["mime"]'); }

	// 二值化
	$img_array = binarize_by_rgbavg($img_file, $avg, $relation, $size[0], $size[1]);
	//print_binary_array($img_array );
	//$pure_array = slice_array($img_array, $x_lenth=9*4, $y_height=10, $x_start=14, $y_start=6);
	//print_binary_array($pure_array );
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
		if($flags['align']) {
			$part_array = align_arr($part_array);
		}
		$array_str = array_to_str($part_array);
		if(array_key_exists($array_str, $known_maps)) {
			$code .= $known_maps[$array_str];
		} else {
			$code .= '_';
		}
		// 输出切割出来的单个二值化数据
		// print_binary_array($part_array, $a='O', $b='_');
	}
	return $code;
}


?>
