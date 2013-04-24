<?php
/* 分析验证码
 * 必选的行为： 二值化
 * 可选的行为： 输出rgb信息， 降噪， 切割， 对齐
 *
 */

function analysebyfile($file, $type, $avg, $relation, $rgba_flag=false, $dropnoise_flag=false, $divide_flag=false, $align_flag=false) {
	$img_file = $file;

	// 对图像类型进行判断
	$types = array('gif', 'png', 'jpg');
	if(!in_array($type, $types)) {
		$err_msg = 'function: ('.__FUNCTION__.") error message (improper parameter: $type , will now exit.) \n";
		// throw new Exception($err_msg);
		exit($err_msg);
	}

	$types = array(1=>'gif', 2=>'jpeg', 3=>'png');
	$size = getimagesize($img_file);
	if(!array_key_exists($size[2], $types)) { throw new Exception('improper peremeter: $size["mime"]'); }
	$size = getimagesize($img_file);
	// 输出像素值
	if($rgba_flag) {
		$img_array = binarize_by_rgbavg($img_file, $avg, $relation, 0, 0);
		echo "<h3>$img_file</h3>";
		echo '<hr /><div class="pixel-data">';
		echo '<p>像素值信息</p>';
		web_print_rgb($img_file, 'r');
		echo '</div><!-- end of DIV pixel -->';
		echo '<hr />';
	}

	// 二值化 并输出结果 必选
	echo '<p>二值化</p>';
	echo '<pre>';
	print_binary_array($img_array,'0','=' );
	echo '</pre>';
	echo '<hr />';
	// 二值化并降噪 并输出结果
	if($dropnoise_flag) {
		echo '<p>二值化并降噪</p>';
		echo '<pre>';
		drop_array_noise($img_array);
		print_binary_array($img_array,'0','=' );
		echo '</pre>';
		echo '<hr />';
	}
	// 做分割标记
	if($divide_flag) {
		echo '<p>切割后的单个字符</p>';
		$char_end_array = array_char_end($img_array);	// todo : add content to return value
		// 按照分割标记 打印出单个字符
		foreach($char_end_array['numx'] as $start => $end) {
			$x_length = $end - $start;
			$y_height = count($img_array);
			$x_start = $start;
			$part_array = slice_array($img_array, $x_length, $y_height, $x_start, $y_start=0);
			echo '<div class="single-char-arr">';
			echo '<pre>';
			// 输出切割出来的单个二值化数据
			print_binary_array($part_array,'0','=' );
			echo '</pre></div>';
		}
		echo '<hr class="clear" />';
	}
	// 打印出对齐后的单个字符
	if($align_flag) {
		echo '<p>对齐后的单个字符</p>';
		foreach($char_end_array['numx'] as $start => $end) {
			$x_length = $end - $start;
			$y_height = count($img_array);
			$x_start = $start;
			$part_array = slice_array($img_array, $x_length, $y_height, $x_start, $y_start=0);
			echo '<div class="single-char-arr" >';
			echo '<pre>';
			// 输出切割出来的对齐后的单个二值化数据
			print_binary_array(align_arr($part_array),'0','=' );
			echo '</pre></div>';
		}
		echo '<hr class="clear" />';
	}
}
?>
