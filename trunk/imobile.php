<?php
/*
imobile valid code image decode
for i in {0..9}; do wget -O imobile/$i.gif http://captcha.imobile.com.cn/captcha.gif; sleep 2; done;
*/
define('X_START',   2);
define('X_END',    14);
define('Y_START',   6);
define('MAX_CHAR',  4);
define('MAX_WIDTH', 13);
$known_mapping = array(
    '00000000111000000111000000111000000110010000111000100000011001000000001110000000000011000000000001100000000000'=>'A',
    '11111111111100001000011000010000111001100001011110100110000000111000000000000'=>'B',
    '0001111100000100000100010000000101000000000110000000001100000000011000000000110000000001010000000100010000001000000000000'=>'C',
    '11111111111100000000011000000000110000000001100000000011000000000101000000010001000001000000111100000000000000'=>'D',
    '11111111111100001000011000010000110000100001100001000011000010000100000000000'=>'E',
    '111111111111000010000010000100000100001000001000010000000000000000'=>'F',
    '000111110000010000010001000000010100000000011000000000110000000001100000100011000001000101000010010011000101100000001100000000000000'=>'G',
    '111111111110000010000000000100000000001000001000010000000000100000111111111110100000000000000000000'=>'H',
    '1111111111100000000000'=>'I',
    '000000000100000000000100000000001000000000111111111111000000000000'=>'J',
    '1111111111100001100000000100110000010000110001000000110100000000110000000000100000000000'=>'K',
    '111111111110000000000100000000001000000000010000000000100000000000'=>'L',
    '00000011111011111000000011000000000001100000000000110000000000011000000000110000000110000000110000000110000000011111110000000000011100000000000'=>'M',
    '011111111110010000000000011000000000011000000000011000000000001000000000001001111111111100000000000'=>'N',
    '000111110000010000010001000000010100000000011000000000110000000001100000000010000000000001000000010001100001000000111000000000000000'=>'O',
    '1111111111110000100000100001000001000010000010000100000010010000000011000000000000000000'=>'P',
    '000111110000010000010001000000010100000000011000000000110000000001100000010010000000011101000000010001000001010000111000000000000000'=>'Q',
    '1111111111110000100000100001100001000010100010000100100010010000110111000000100000000000'=>'R',
    '011100000101000100000110000100001110000100110000000110000000000000'=>'S',
    '10000000000100000000001000000000011111111111100000000001000000000000000000000'=>'T',
    '111111111100000000001000000000001000000000011000000000100000000010111111111000100000000000000000000'=>'U',
    '11000000000001110000000000011000000000001110000000001100000011100000011000000011000000001000000000000000000000'=>'V',
    '11100000000000111000000000001111000000001100000011100000011000000001100000000000111000000000001110000000001110000011110000111000000000000000000'=>'W',
    '1000000001101100000110000110110000000111000000011011000011000001101000000001100000000000'=>'X',
    '1100000000001100000000000110000000000111111100011000000011000000001000000000000000000000'=>'Y',
    '100000001111000001110110001110001111100000011100000000100000000000'=>'Z',
    '00000011111011110000000011000000000001100000000000111000000000011100000000110000000100000001100000001110000000000111111100000000000100000000000'=>'M',
    '01000000000111000000000011110000000000011110000000011100000111000000110000000011000000000001110000000000011100000000011110000111100011110000000'=>'W',
    '11111111111100001000011000010000110000100001110011100110111001111000000000000'=>'B',
    '011111111110010000000000011000000000011000000000001000000000001000000000001001111111111000000000000'=>'N',
    '01000000000111000000000011110000000000011110000000011100000111000000110000000011000000000001110000000000011100000000011110000111100011110000000'=>'W',
    '0111000001010001000001100011000011000010000101000010010000000011000000000000000000000000'=>'S',
    '11100000000000111000000000000111000000001100000011000000111000000001110000000000011000000000000110000000001110000111100001110000000000000000000'=>'W',
    '000111110000010000010001000000010100000000111000000000110000000001100000110011100000111101000000111001100011010001111100000000000000'=>'Q',
    '10000000011100000011011000011000110111000001111000000010000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'=>'Z',
    '1100000000000111000000000011100000000000110000000000111000000110000001110000001110000000100000000000'=>'V',
    '1111111110000000000011000000000010000000000100000000001000000000101111111110000000000000'=>'U',
    '100000000111000000110110000110001101110000011110000000100000000000'=>'Z',
);
foreach (glob('imobile/*.gif') as $imgfile) {
    $res = imagecreatefromgif($imgfile);
    $size = getimagesize($imgfile);
    $data = $data2 = array();
    $first_line_avg = $first_line_sum = 0;
    for($i=X_START; $i < X_END; $i++) {//2向化
        // echo "$i  R  G  B\n";
        for($j=Y_START; $j < $size[0]; $j++) {
            $rgb = imagecolorat($res,$j,$i);
            $rgbarray = imagecolorsforindex($res, $rgb);
            if ($i==X_START) {
                $first_line_sum += array_sum($rgbarray);
                continue;
            }
            if (!$first_line_avg) {
                $first_line_avg = ceil($first_line_sum/$size[0]);
            }
            if ( ($first_line_avg <= 400 && $rgbarray['red'] > 160 &&  $rgbarray['green']> 150 && $rgbarray['blue'] > 150 )
            || ($first_line_avg > 400 && $rgbarray['red'] < 100 &&  $rgbarray['green']< 90 && $rgbarray['blue'] < 90 ) ) {
                $data[$i-X_START][$j-Y_START]=1;
            } else {
                $data[$i-X_START][$j-Y_START]=0;
            }
        }
    }
    foreach ($data as $k1=>$v1) {//降噪
        foreach ($v1 as $k2=>$v2) {
            if ($v2 ==1 && @$v1[$k2-1]==0 && @$v1[$k2+1]==0 && 
                @$data[$k1-1][$k2]==0 && @$data[$k1+1][$k2]==0 && 
                @$data[$k1-1][$k2-1]==0 && @$data[$k1-1][$k2+1]==0 && 
                @$data[$k1+1][$k2-1]==0 && @$data[$k1+1][$k2+1]==0 ) {
                $data[$k1][$k2]=0;
            }
            echo $data[$k1][$k2] ? '#' : '`';
        }
        echo "\n";
    }
    $x_from = 0;
    for ($char=0;$char<MAX_CHAR;$char++) {//split chars
        for ($x=$x_from;$x<min($x_from+MAX_WIDTH, count($data[5]));$x++) {
            $sum = 0;
            for($y=1;$y<count($data)+1;$y++) {
                $sum += $data[$y][$x];
                $data2[$char][$y-1][$x-$x_from] = $data[$y][$x];
            }
            if ($sum==0) {
                if (count($data2[$char][0])==1) {
                    $x_from = $x+1;
                    unset($data2[$char]);//clear left space
                    continue;
                } elseif ($gotchar = gotit($data2[$char])) {
                    $x_from = $x+1;
                    continue 2;
                } else {
                    //do nothing
                    continue;
                }
            }
        }
        $x_from = $x;
    }
    echo "\n";
    $decode = array();
    foreach ($data2 as $c) {
        foreach ($c as $k=>$x) {
                foreach ($x as $l=>$y) {
                    echo $y ? "#" : '`';
                }
            echo "\n";
        }
        $decode[] = gotit($c);
    }
    echo implode('',$decode)."\n\n";
}
function gotit($data) {
    global $known_mapping;
    $s = '';
    for ($i=0;$i<count($data[0]);$i++) {
        for ($j=0;$j<count($data);$j++) {
            $s .= $data[$j][$i];
        }
    }
    echo "\t$s\n";
    $percent=0.0;
    $r = '';
    foreach ($known_mapping as $code=>$char) {
        similar_text($s, $code, $p);
        if ($p>90) {
            if ($p>$percent) {
                $r = $char;
                $percent = $p;
            }
        }
    }
    return $r;
}