<?php
/* shell_study_config.php
 * liuzhengyi 2013-05-27
 *
 * 记录学习参数
 * 根据分析结果手动编辑
 */

// pps
define("PPS", TRUE);
$pps_learn_path = '../images/learn-sample/pps';
$pps_test_path = '../images/test/pps';
$pps_type = 'png';
$pps_avg = '66';
$pps_relation = '==';
$njaumy_flags['align'] = false;

// njaumy
define("NJAUMY", TRUE);
$njaumy_learn_path = '../images/learn-sample/njaumy';
$njaumy_test_path = '../images/test/njaumy';
$njaumy_type = 'jpg';
$njaumy_avg = '66';
$njaumy_relation = '<';
$njaumy_flags['align'] = true;

// njnu
define("NJNU", TRUE);
$njnu_learn_path = '../images/learn-sample/njnu';
$njnu_test_path = '../images/test/njnu';
$njnu_type = 'png';
$njnu_avg = '66';
$njnu_relation = '>';
$njnu_flags['align'] = false;

// nju
define("NJU", TRUE);
$nju_learn_path = '../images/learn-sample/nju';
$nju_test_path = '../images/test/nju';
$nju_type = 'jpg';
$nju_avg = '130';
$nju_relation = '<';
$nju_flags['align'] = false;

?>
