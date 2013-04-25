<?php
/* 按照给定的参数识别验证码
 * /includes/functions/study.php
 * 学习结果以PHP数组的形式存储在map文件中
 */
//include('../func.inc.php');
$path = '../images/test/pps/'; $type = 'png'; $avg = 66; $relation = '==';
//$path = './njaumy/'; $type = 'jpg'; $avg = 66; $relation = '<';
$rgba = 'rgba';
//analyse_files($path, $type, $rgba);		// 分析目录下的图片文件的像素
//study($path, $type, $avg, $relation);		// 学习目录下的图片文件
//$file = '../images/pps/1.png';
//$str = pps_tell($file, $type, $avg, $relation);	// 识别一张图片
//echo $str;

function study_pro($results, $type, $map_file, $bi_parameters, $pro_parameters) {
// $results[] 中含有图像文件和对应的验证码字符串
// 本函数将按照$bi_parameters[] 和 $pro_parameters[] 中指定的参数和过程来对$results[] 进行分析，并将结果存入$map_file中
	$lock_file = $map_file.'.lock';

	$fh = fopen($map_file, 'w+');
	if(!$fh) {
		$err_msg = 'function: ('.__FUNCTION__.") error message (open file: $map_file falled, will now exit.) \n";
		exit($err_msg);
	}
	$start_content = "<?php\n".'$known_maps = array('."\n\n);\n?>";
	fwrite($fh, $start_content);

	$known_maps = array();
	foreach($results as $img_file => $code) {
		// 二值化
		// todo modify arguments !!
		$img_array = binarize_by_rgbavg($img_file, $bi_parameters['avg'], $bi_parameters['relation']);

		// 降噪
		echo '<pre>';	// todo debug delete
		print_binary_array($img_array);
		echo '</pre>';
		drop_array_noise($img_array);
		echo '<pre>';	// todo debug delete
		print_binary_array($img_array);
		echo '</pre>';

		// 做分割标记
		// todo modify arguments !!
		$char_end_array = array_char_end($img_array);	// todo : add content to return value

		// 分割学习
		$y_height = count($img_array);
		$char_count = 0;
		foreach($char_end_array['numx'] as $start => $end) {
			$x_length = $end - $start;
			$x_start = $start;
			$part_array = slice_array($img_array, $x_length, $y_height, $x_start, $y_start=0);
			$array_str = array_to_str($part_array);
			if(array_key_exists($array_str, $known_maps)) {
				//echo "{$known_maps[$array_str]} \n";
				continue;
			}
			$known_maps[$array_str] = $code[$char_count];
			// 输出切割出来的单个二值化数据
			// print_binary_array($part_array, $a='O', $b='_');

			// store the known maps in an array
			fseek($fh, -6, SEEK_END);
			fwrite($fh, '"'.$array_str.'" =>"'.$code[$char_count]."\",\n".");\n?>"."\n");
			$char_count++;
		}
	}
	fclose($fh);
	exec("touch $lock_file");
	return 0;
}
?>
