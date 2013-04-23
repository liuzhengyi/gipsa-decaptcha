<?php
/*
91 valid code image decode
for i in {0..9}; do wget -O 91/$i.gif "https://reg.91.com/vcode.gif.ashx?pid=1"; sleep 2; done;
*/
define('X_START',  0);
define('X_END',   54);
define('Y_START',  0);
define('MAX_CHAR', 4);
$known_mapping = array(
    'BCDEFGHCCCCCCCCCEFGJKJHFEEC'       =>'2',
    'CEFIGGFAA@@ACCDDEDFHPQSOIHFC'      =>'3',
    'BFGJLHGJCFCBBCCDDEDFHPQSPIHFCAAB'  =>'3',
    'BCDDDDCDCAAABBBHT^WTMFAAA'         =>'4',
    'ACCCCABBAAABBBHT^WTMFAAA'          =>'4',
    'AEOJDCCDCDDDEEFHMTPJ'              =>'5',
    'CJNQTLHGEDDBCDCCCCBBCHOROKCB'      =>'6',
    'AAAAAEGIKMFEDDDDEEDDDEEEFEDCB'     =>'7',
    'AAAABFHJKNGEDDDDEEDDDEEEFEDCB'     =>'7',
    'BHJLHEDCCCBBBCCCEFFHIPOLIHEAAB'    =>'8',
    'BHJLHEDCCCBBCDCCFGGIJQPNKIE'       =>'8',
    'BHJLHEDCCCBBBCCCEFFHIPNLIGD'       =>'8',
    'FILMHEEFGECBBBACCCEDFGHKLPQNKE'    =>'9',
    'ABCECCBDJFGJMNNMLHDAA'             =>'A',
    'ABCEDDDDJGIKMNNMLHDAA'             =>'A',
    'AACHKMNCAAAAAAFIME'                =>'B',
    'EGIGFEEDB@@AAAAA@@@@AAB'           =>'C',
    'BPE@@@ABBCEMO'                     =>'D',
    'HRVLEBAAABIBD'                     =>'E',
    'AAEINNKDA@@@@@@ABEA@ABCCA'         =>'F',
    'FKMQJGEDBAAA@@@@@FEE'              =>'G',
    'FKMQJGJGDAAA@@@@@GGE'              =>'G',
    'AAABDFGHKA@@AMGFGFCA'              =>'H',
    'AACFGHKA@@AKFFGDBA'                =>'H',
    'AACFGHKA@@ALGFHECA@AAA'            =>'H',
    'DEA@@@@@EQQH'                      =>'J',
    'A\]DBBDCCEEDB'                     =>'K',
    'CGGEEEEDBBBBPK'                    =>'M',
    'AAAAECCDDDDCCDCCCCCDID'            =>'N',
    'AGMTOFAA@@@@@ABBGEC'               =>'P',
    'PUEFBBABDDCCKG'                    =>'Q',
    'AAEIOPG@@@ABEFHJDEDDDCCCBB'        =>'R',
    'AAEIOPG@BAABEFHJEEDDCBB'           =>'R',
    'BBFJOPG@@@ABEHIJDDCCCCCAA'         =>'R',
    'CEDCAAABAABCCDFJJDF'               =>'S',
    'AAUU@@@@@@AACC'                    =>'T',
    'AA@@@@AAUU@@@AAAAADD'              =>'T',
    'AAUU@@@@@@AACC'                    =>'T',
    'LNBA@@@@@@@AABDGEA'                =>'U',
    'BDFFFGEBBBBBBBBBA'                 =>'V',
    'BEGFFGECCCBCCBBBA'                 =>'V',
    'BFFFFDCCFGJKIDDCCCCAA'             =>'W',
    'ABDEEEEDDFFFHEDAB'                 =>'X',
    'BDDCCDKJB@ABABDBAAA'               =>'Y',
    'ACECEDCCBDDEA'                     =>'Z',
);
foreach (glob('91/?.gif') as $imgfile) {
    $res = imagecreatefrompng($imgfile);
    $size = getimagesize($imgfile);
    $data = array();
    for ($i=X_START; $i < X_END; $i++) {//2向化
        for ($j=Y_START; $j < $size[0]; $j++) {
            $rgb = imagecolorat($res, $j, $i);
            $rgbarray = imagecolorsforindex($res, $rgb);
            if ( abs($rgbarray['red']-71) < 10 && (abs($rgbarray['green']-16) < 15 || abs($rgbarray['blue']-22) < 15 )) {
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
    echo "[".decode($data)."]\n\n";
}
function decode ($data) {
    global $known_mapping;
    $s = '';
    for ($i=0;$i<count($data[0]);$i++) {
        $a = 0;
        for ($j=0;$j<count($data);$j++) {
            $a += $data[$j][$i];
        }
        $s .= chr(64+$a);
    }
    echo "$s\n";
    $r = '';
    while (strlen($r) < MAX_CHAR) {
        $s = preg_replace('/^@+/','',$s); 
        $percent=0.0;
        $temp = "";
        $temp_code_len = 1;
        foreach ($known_mapping as $code=>$char) {
            similar_text(substr($s,0,strlen($code)), $code, $p);
            $fa = strlen($code) > 20 ? 85 : 70;
            if ($p>$fa && $p>$percent) {
                $percent = $p;
                $temp = $char;
                $temp_code_len = strlen($code);
            }
        }
        $r .= $temp; 
        $s = substr($s, $temp_code_len);
        if (strlen($s)<5) break;
    }
    return $r;
}
