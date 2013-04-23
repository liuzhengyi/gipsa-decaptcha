<?php
ini_set("display_errors", E_ALL);

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

// todo !! 为什么此处的glob无法得到期望的结果 ??
$dir = './images/91/*';
$files = glob($dir, GLOB_ERR);
echo "\n".'<h2>glob('.$dir.')</h2><ol>'."\n";
foreach($files as $file) { echo '<li><img src="'.$file.'"></li>'; }

$dir = './images/njaumy/*';
$files = glob($dir);
echo "\n".'<h2>glob('.$dir.')</h2><ol>'."\n";
foreach($files as $file) { echo '<li><img src="'.$file.'"></li>'; }

?>
<!--
<form action="#" method="post">
<label for="">Directory:<input type="text" name="directory" id="directory"/></label>
<label for="">Type:<input type="text" name="type" id="type"/></label>
<label for="">Directory:<input type="text" name="directory" id="directory"/></label>
</form>
-->
