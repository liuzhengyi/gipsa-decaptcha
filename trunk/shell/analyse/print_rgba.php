<?php
ini_set("display_errors", E_ALL);
include('../func.inc.php');

if(3 > $argc) {
	echo "Usage: php ".basename(__FILE__)." image_path image_type [rgba]\n";
	exit();
}
$path = $argv[1];
$type = $argv[2];
$rgba = (4 == $argc) ? ($argv[3]) : ('rgba');
//$rgba = $argv[3];
analyse_files($path, $type, $rgba);		// 分析目录下的图片文件的像素
//learn($path, $type, $avg, $relation);	// 识别目录下的图片文件

function analyse_files($path, $type, $rgba='rgba') {
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
	}
}

?>
