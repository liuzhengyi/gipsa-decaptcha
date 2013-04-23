<?php
ini_set("display_errors", E_ALL);
define('X_START', 1);
define('X_END', 58);
//define('Y_START', 6);
define('Y_START', 0);
//define('Y_END', 16);
define('Y_END', 22);


//$path = './pps/'; $type = 'png'; $avg = 66; $relation = '==';
//$path = './njaumy/'; $type = 'jpg'; $avg = 66; $relation = '<';
$path = './phpwind/'; $type = 'png'; $avg = 70; $relation = '<';
//$path = './17php/'; $type = 'png'; $avg = 1; $relation = '<';
$rgba = 'rgba';
//$rgba = 'r';
analyse_files($path, $type, $rgba);		// 分析目录下的图片文件的像素
//learn($path, $type, $avg, $relation);	// 识别目录下的图片文件



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
		//print_rgb($img_file, $rgba, $x_end=80 );

		// 二值化
		global $avg;
		$img_array = binarize_by_rgbavg($img_file, $avg, $relation='<');
		echo "image: $img_file \n---------------\n";
		print_binary_array($img_array, $a='O', $b='_');

		// 输出整个二值化数组
		//print_binary_array($img_array, $a='O', $b='_');

		// 切割图像
		$char_end_array = array_char_end($img_array);	// todo : add content to return value

		$y_height = '';
		$y_start = '';
		foreach($char_end_array['numy'] as $start => $end ) {
			$y_height = $end - $start;
			$y_start = $start;
		}
		//var_dump($char_end_array['numx']);

		foreach($char_end_array['numx'] as $start => $end) {
			$x_length = $end - $start;
			$x_start = $start;

			// 输出切割出来的单个二值化数据
			$part_array = slice_array($img_array, $x_length, $y_height, $x_start, $y_start);
			print_binary_array($part_array, $a='O', $b='_');
			// 将图像二值数据按像素向上对齐
			//$align_arr = align_arr($part_array);
			// 输出切割对齐了的图像数组
			//print_binary_array($align_arr, $a='O', $b='_');

		}

		// test array_char_end();
		/*
		$end_array = array_char_end($array);
		var_dump($end_array);
		*/
	}
}


/* 将一个二维数组中的值连接成一个字符串
 *
 * input args:
 *	@$array -- input array
 */
function array_to_str($array) {
	$array_str = '';
	$y_end = count($array);
	$x_end = count($array[0]);
	for($j = 0; $j < $y_end; $j++) {
		for($i = 0; $i < $x_end; $i++) {
			$array_str .= strval($array[$j][$i]);
		}
	}
	return $array_str;
}

/* 根据像素的rgb平均值将图像二值化
 *
 * input args: 
 *	$img_file -- image file
 *	$avg -- 期望像素平均值，阈值
 *	$relation -- 期望点与期望像素平均值的关系，可能值为'<, >, ==, ===, >=, <='
 *	$x_start, $y_start,-- 读取范围起始点
 *	$x_end, $y_end,-- 读取范围结束点，为0则读到末尾
 * output:
 *	对指定图像的指定范围二值化后的数组
 */
function binarize_by_rgbavg($img_file, $avg, $relation, $x_end=0, $y_end=0, $x_start=0, $y_start=0 ) {
	$binary_array = array();

	$size = array();
	$res = open_img($img_file, &$size);
	if( $y_end > $size[1] || 0 == $y_end ) { $y_end = $size[1]; }
	if( $x_end > $size[0] || 0 == $x_end ) { $x_end = $size[0]; }

	for( $i = $y_start; $i < $y_end; $i++) {
		for( $j = $x_start; $j < $x_end; $j++ ) {
			$rgb = imagecolorat($res, $j, $i);
			$rgbarray = imagecolorsforindex($res, $rgb);
			$rgbsum = $rgbarray['red']+$rgbarray['green']+$rgbarray['blue'];
			$rgbavg = $rgbsum/3;
			$relation_array = array('<', '>', '>=', '<=', '==', '!=');
			if(!in_array($relation, $relation_array)) {
				throw new Exception('improper peremeter: $relation');
			}
			$relation_cmd = '
			if($rgbavg '.$relation.' $avg) {
				$binary_array[$i][$j]=1;
			} else {
				$binary_array[$i][$j]=0;
			};';
			eval($relation_cmd);
		}
	}
	return $binary_array;
}

/* 给一个二值化数组降噪
 *
 */
function drop_array_noise($res_array, $x_end, $y_end, $x_start=0, $y_start=0) {
}

/* 按照给定的范围切割一个二维数组
 * 从1开始计数
 * 
 */
function slice_array($res_array, $x_length, $y_height, $x_start=0, $y_start=0) {
	$y_size = count($res_array);
	$x_size = count($res_array[0]);
	$y_end = $y_start + $y_height;
	$x_end = $x_start + $x_length;
	$ret_array = array();
	for($i = $y_start; $i < $y_end; $i++) {
		for($j = $x_start; $j < $x_end; $j++) {
			$ret_array[$i-$y_start][$j-$x_start] = $res_array[$i][$j];
		}
	}
	return $ret_array;
}

/* 输出一个二值化数组
 *
 * input args:
 *	$array -- 二值化数组
 *	$x, $y -- 数组边界，从1开始
 *	$a, $b -- 为1输出$a, 非1输出$b
 */
function print_binary_array($array, $a='O', $b='_') {
	$y_end = count($array);
	$x_end = count($array[0]);
	for($i = 0; $i < $y_end; $i++) {
		for( $j = 0; $j < $x_end; $j++ ) {
			if($array[$i][$j]) {
				echo $a;
			} else { echo $b; }
		} echo "\n";
	} echo "\n";
}

/* 打印出一幅图片的rgb值和/或rgb平均值
 * input args:
 *	@$img_file -- 要处理的图片文件
 *	@$rgba	-- 要打印的内容，r->red, g->green, b->blue, a->avg
 *	@$x_end, $y_end, $x_start, $y_start 要打印的范围
 * return value:
 *	N/A
 *
 */
function print_rgb($img_file, $rgba='rgba', $x_end=0, $y_end=0, $x_start=0, $y_start=0) {
	$rgbas = array('r'=>'red', 'g'=>'green', 'b'=>'blue', 'a'=>'avg');

	$size = array();
	$res = open_img($img_file, &$size);
	if( $y_end > $size[1] || 0 == $y_end ) { $y_end = $size[1]; }
	if( $x_end > $size[0] || 0 == $x_end ) { $x_end = $size[0]; }

	echo "FILE: $img_file \n======================================================================\n";
	echo "FILE: $img_file \n======================================================================\n";
	foreach($rgbas as $k => $v) {
		if(false === strpos($rgba, $k)) { continue;}
		
		// print header start 
		echo "$v value \n---------------------------------------------------------------------\n";
		print("    ");
		for( $j = $x_start; $j < $x_end; $j++ ) { printf("%4d", $j); }
		echo "\n";
		print("    ");
		for( $j = $x_start; $j < $x_end; $j++ ) { print("----"); }
		echo "\n";
		// print header end 
		for( $i = $y_start; $i < $y_end; $i++ ) {
			printf("%-3d", $i); echo '|';	// print line number
			for( $j = $x_start; $j < $x_end; $j++ ) {
				$rgb = imagecolorat($res, $j, $i);
				$rgbarray = imagecolorsforindex($res, $rgb);
				if('a' == $k) {
					$rgbavg = ($rgbarray['red']+$rgbarray['blue'] +$rgbarray['green'])/3; 
					printf("%4d", $rgbavg);
				} else {
					printf("%4d", $rgbarray[$v]);
				}
			}
			echo "\n";
		}
		echo "\n";
	}
}

function open_img($img_file, &$size) {
	$types = array(1=>'gif', 2=>'jpeg', 3=>'png');
	$size = getimagesize($img_file);
	if(!array_key_exists($size[2], $types)) { throw new Exception('improper peremeter: $size["mime"]'); }
	$tmp_cmd = '$res = imagecreatefrom'.$types[$size[2]].'($img_file);';
	eval($tmp_cmd);	// $res generated
	return $res;
}



/* 检测一个已经二值化的图像数组中的字符分隔点
 *
 *
 *
 */
function array_char_end($img_array) {
	$div_point = array();
	$y_end = count($img_array);
	$x_end = count($img_array[0]);
	echo '$y_end='.$y_end."\n";	// todo : delete
	echo '$x_end='.$x_end."\n";	// todo : delete

	// 返回数组中包含的信息
	$div_point['arrx'] = array();	// 以数组形式记录数据X轴投影分布
	$div_point['arry'] = array();	// 以数组形式记录数据Y轴投影分布
	$div_point['strx'] = '';	// 以bitmap str形式记录X轴投影
	$div_point['stry'] = '';	// 以bitmap str形式记录Y轴投影
	$div_point['numx'] = array();	// 记录X轴分隔点坐标
	$div_point['numy'] = array();	// 记录Y轴分隔点坐标

	// 按X轴方向寻找分隔点
	for($i = 0; $i < $x_end; $i++) {
		for($j = 0; $j < $y_end; $j++) {
			if(1 == $img_array[$j][$i]) {
				$div_point['arrx'][$i] = 1;
				$div_point['strx'] .= '1';
				break;
			}
		}
		if(isset($div_point['arrx'][$i])) {
			continue;
		}
		$div_point['arrx'][$i] = 0;
		$div_point['strx'] .= '0';
	}

	// 按X轴方向寻找分隔点
	for($j = 0; $j < $y_end; $j++) {
		for($i = 0; $i < $x_end; $i++) {
			if(1 == $img_array[$j][$i]) {
				$div_point['arry'][$j] = 1;
				$div_point['stry'] .= '1';
				//$div_point['numy'][] = $j;
				break;
			}
		}
		if(isset($div_point['arry'][$j])) {
			continue;
		}
		$div_point['arry'][$j] = 0;
		$div_point['stry'] .= '0';
	}
	// 根据$div_point[arr(x|y)]生成$div_point[num(x|y)]数组
	$x_end -= 1;
	$tmp_var = 0;
	for($i = 0, $j = 0; $i < $x_end; $i++) {
		if($div_point['arrx'][$i] != $div_point['arrx'][$i+1]) {
			if(0 == $j%2) {
				$tmp_var = $i+1;
			} else {
				$div_point['numx'][$tmp_var] = $i+1;	// 获得有变化产生的位置索引，从0开始计
			}
			$j++;
		}
	}
	$y_end -= 1;
	for($i = 0, $j = 0; $i < $y_end; $i++) {
		if($div_point['arry'][$i] != $div_point['arry'][$i+1]) {
			if(0 == $j%2) {
				$tmp_var = $i+1;
			} else {
				$div_point['numy'][$tmp_var] = $i+1;	// 获得有变化产生的位置索引，从0开始计
			}
			$j++;
		}
	}

	return $div_point;
}

/* align_arr()
 * 向某个方向对齐一个二值化数组
 *
 *
 */
function align_arr($array, $arrow='d') {
	$arrows = 'udlr';
	$ret_arr = array();

	//if(strpos($arrow, 'u')) {
	// X轴向上对齐
		$y_end = count($array);
		$x_end = count($array[0]);
		$y_start = 0;
		for($i = 0; $i < $x_end; $i++) {
			for($j = 0; $j < $y_end; $j++) {
				if(0 != $array[$j][$i]) {
					$y_start = $j;
					break;
				}
			}
			$y_new_end = $y_end - $y_start;
			for($j = 0; $j < $y_end; $j++) {
				if($j < $y_new_end) {
					$k = $j+$y_start;
					$ret_arr[$j][$i] = $array[$k][$i];
				} else {
					$ret_arr[$j][$i] = 0;
				}
			}
		}
		/*
		// X轴向下对齐 尚未通过测试
	} else if(strpos($arrow, 'd')){
		$y_start = count($array);
		$x_end = count($array[0]);
		$y_end = 0;
		for($i = 0; $i < $x_end; $i++) {
			for($j = $y_end; $j > 0; $j--) {
				if(0 != $array[$j][$i]) {
					$y_start = $j;
					break;
				}
			}
			$y_new_end = $y_end - $y_start;
			for($j = $y_end; $j > 0; $j--) {
				if($j > $y_new_end) {
					$k = $j-$y_start;
					$ret_arr[$j][$i] = $array[$k][$i];
				} else {
					$ret_arr[$j][$i] = 0;
				}
			}
		}
	}
	*/

	return $ret_arr;
}
?>
