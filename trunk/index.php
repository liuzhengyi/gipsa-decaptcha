<?php

/*
$dir = './images/pps/*';
$files = glob($dir);
echo '<h2>pps</h2><ol>';
foreach($files as $file) { echo '<li><img src="'.$file.'"></li>'; }
echo '</ol>';

$dir = './images/phpwind/*';
$files = glob($dir);
echo '<h2>phpwind</h2><ol>';
foreach($files as $file) { echo '<li><img src="'.$file.'"></li>'; }
echo '</ol>';

$dir = './images/17php/*';
$files = glob($dir);
echo '<h2>17php</h2><ol>';
foreach($files as $file) { echo '<li><img src="'.$file.'"></li>'; }

$dir = './images/njaumy/*';
$files = glob($dir);
echo "\n".'<h2>glob('.$dir.')</h2><ol>'."\n";
foreach($files as $file) { echo '<li><img src="'.$file.'"></li>'; }
*/

?>

<div id="origin_image">
<form action="#" method="post">
请选择验证码来源：<br /><select>
<option value="pps" name="pps">pps</option>
<option value="njaumy" name="njaumy" >njaumy</option>
<option value="phpwind" name="phpwind" >phpwind</option>
</select>
</form>
</div>

<div id="">
<label for="">Directory:<input type="text" name="directory" id="directory"/></label>
