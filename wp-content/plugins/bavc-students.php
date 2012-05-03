<?php
/**
 * @package NextGen_Students
 * @version 0.1
 */
/*
Plugin Name: NextGen Student List
Plugin URI: 
Description: NextGen Student Gallery
Author: Gabriel Dunne
Version: 0.1
Author URI: 
*/

define('NG_CONTENT_LOC', "/home/nextgen/webroot/content/");
define('NG_WEBROOT', "http://nextgen.bavc.org/");
define('NG_PARAMS', "config.json");

/**
 *	generate student gallery
 */
function student_gallery( $class_dir ) {
	
	$students_dir = NG_CONTENT_LOC . $class_dir . '/students';
 	$web_loc = NG_WEBROOT . "content/" . $class_dir;
	$student_array = glob($students_dir . '/*', GLOB_ONLYDIR);
	
	$site_config_loc = NG_CONTENT_LOC . $class_dir . '/'. NG_PARAMS;
	$site_config_str = file_get_contents($site_config_loc);

	$config =  json_decode($site_config_str);

	?>
	 <div class="student-list">
	 <ul>
	 <? foreach($config->students as $key=>$student) : ?>
	 <li>
	 <div class="thumb">
		<a href="<?=$student->url?>"><img src="<?=$web_loc . '/' . $student->profile;?>" /></a>
	 </div>
	 <p>
		<a href="/student/?id=<?=$key?>&class=<?=$class_dir?>"><?=$student->first;?></a>
	 </p>
	 </li>
	 </li>
	 <? endforeach; ?>
	 </ul>
	 </div>
	 <?
}


function student_page() {
	
	$class_dir = $_GET['class'];
	$student_id = $_GET['id'];
	
	$site_config_loc = NG_CONTENT_LOC . $class_dir . '/'. NG_PARAMS;
	$site_config_str = file_get_contents($site_config_loc);				
	$config =  json_decode($site_config_str);					
	$students = (array)$config->students;
	
	$content = $students[$student_id];
	
	print_r($content);
	
}

?>
