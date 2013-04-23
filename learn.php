<?php
include('./func.inc.php');

$path = './images/pps/'; $type = 'png'; $avg = 66; $relation = '==';
//$path = './njaumy/'; $type = 'jpg'; $avg = 66; $relation = '<';
$rgba = 'rgba';
//analyse_files($path, $type, $rgba);		// 分析目录下的图片文件的像素
learn($path, $type, $avg, $relation);	// 识别目录下的图片文件

function learn($path, $type, $avg, $relation) {

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

	$map_file = './map_file.php';
	$fh = fopen($map_file, 'w+');
	/*$start_content = "<?php\n".'$known_maps = array('."\n\n);\n?>"; */
	$start_content = "<?php\n"."\$known_maps_array_serial = '"."0;\n?>";
	fwrite($fh, $start_content);

	$known_maps = array();

	foreach($img_files as $img_file) {
		$types = array(1=>'gif', 2=>'jpeg', 3=>'png');
		$size = getimagesize($img_file);
		if(!array_key_exists($size[2], $types)) { throw new Exception('improper peremeter: $size["mime"]'); }
		$tmp_cmd = '$res = imagecreatefrom'.$types[$size[2]].'($img_file);';
		eval($tmp_cmd);
		$size = getimagesize($img_file);
		$img_array = binarize_by_rgbavg($img_file, $avg, $relation, $size[0], $size[1]);
		//print_binary_array($img_array );
		$pure_array = slice_array($img_array, $x_lenth=9*4, $y_height=10, $x_start=14, $y_start=6);
		//print_binary_array($pure_array );

		for($i = 0; $i < 4; $i++) {
			// silce the whole array

//			fseek($fh, -6, SEEK_END);
			$part_array = array();
			$part_array[$i] = slice_array($pure_array, $length=9, $height=10, $x_start=$i*9);
			$array_str = array_to_str($part_array[$i]);

			print_binary_array($part_array[$i] );
			if(array_key_exists($array_str, $known_maps)) {
				echo "{$known_maps[$array_str]} \n";
				continue;
			}
			//var_dump($known_maps);
			// study by human
			$data = readline('What is the digit:');
			$known_maps[$array_str] = $data;
			/*fwrite($fh, '"'.$array_str.'" =>"'.$data."\",\n".");\n?>"."\n"); */
		}
	}
	fseek($fh, -5, SEEK_END);
	$array_serial = serialize($known_maps);
	fwrite($fh, $array_serial);
	fwrite($fh, "';\n?>"."\n");
	fclose($fh);
}
?>
