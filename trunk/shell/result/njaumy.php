<?php
//	$map_file = dirname(__FILE__).'/'.substr_replace(basename(__FILE__), '.map', -4, 0);
//	exit();
include('../func.inc.php');

//$path = './pps/'; $type = 'png'; $avg = 66; $relation = '==';
$path = '../images/test/njaumy/'; $type = 'jpg'; $avg = 66; $relation = '<';
$rgba = 'rgba';
//analyse_files($path, $type, $rgba);		// 分析目录下的图片文件的像素
learn($path, $type, $avg, $relation);	// 识别目录下的图片文件

function learn($path, $type, $avg, $relation) {

	// variables and lock file
	$map_file = dirname(__FILE__).'/'.substr_replace(basename(__FILE__), '.map', -4, 0);
	$lock_file = $map_file.'.lock';
	if(file_exists($lock_file)) {
		// 若存在lockfile，说明已经学习完毕，直接使用学习结果
		if(file_exists($map_file)) {
			include($map_file);	// init $known_maps;
			//$known_maps = unserialize($known_maps_array_serial);
			//var_dump($known_maps);
		} else {
			$err_msg = "error, please delete the lock file $lock_file and try again.\n";
			exit($err_msg);
		}
	} else {
		// 若不存在lockfile，则进行学习
		$fh = fopen($map_file, 'w+');
		if(!$fh) {
			$err_msg = 'function: ('.__FUNCTION__.") error message (open file: $map_file falled, will now exit.) \n";
			exit($err_msg);
		}
		$known_maps = array();
	}

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

	if(!file_exists($lock_file)) {
		$start_content = "<?php\n".'$known_maps = array('."\n\n);\n?>";
		/*$start_content = "<?php\n"."\$known_maps_array_serial = '"."0;\n?>"; */
		fwrite($fh, $start_content);
	}

	foreach($img_files as $img_file) {
		$types = array(1=>'gif', 2=>'jpeg', 3=>'png');
		$size = getimagesize($img_file);
		if(!array_key_exists($size[2], $types)) { throw new Exception('improper peremeter: $size["mime"]'); }

		$img_array = binarize_by_rgbavg($img_file, $avg=100, $relation='<');
		//print_binary_array($img_array );
		//print_binary_array($pure_array );

		$char_end_array = array_char_end($img_array);	// todo : add content to return value
			$y_height = count($img_array);
		foreach($char_end_array['numx'] as $start => $end) {
			$x_length = $end - $start;
			$x_start = $start;
			$part_array = slice_array($img_array, $x_length, $y_height, $x_start, $y_start=0);
			print_binary_array($part_array, $a='O', $b='_');
			$align_arr = align_arr($part_array);
			$array_str = array_to_str($align_arr);
			if(array_key_exists($array_str, $known_maps)) {
				echo "{$known_maps[$array_str]} \n";
				continue;
			}
			$data = readline('What is it ?');
			$known_maps[$array_str] = $data;
			// 输出切割出来的单个二值化数据
			// print_binary_array($align_arr, $a='O', $b='_');

			if(!file_exists($lock_file)) {
				fseek($fh, -6, SEEK_END);
				fwrite($fh, '"'.$array_str.'" =>"'.$data."\",\n".");\n?>"."\n");
			}
		}
	}
	if(!file_exists($lock_file)) {
	/*
		fseek($fh, -5, SEEK_END);
		$array_serial = serialize($known_maps);
		fwrite($fh, $array_serial);
		fwrite($fh, "';\n?>"."\n");
	*/
		fclose($fh);
		exec("touch $lock_file");
	}
}
?>
