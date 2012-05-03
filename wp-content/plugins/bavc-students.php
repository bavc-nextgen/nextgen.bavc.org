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
define('NG_CONTENT', "content/");
define('NG_WEBROOT', "http://nextgen.bavc.org/");
define('NG_PARAMS', "config.json");

/**
 *	generate student gallery
 */
function ng_student_gallery( $class_dir ) {

	$config = ng_get_config($class_dir);
	$web_loc = NG_WEBROOT . "content/" . $class_dir;
	
	?>
	 <div class="student-list">
	 <ul>
	 <? foreach( (array) $config->students as $key => $student) : 
	 	$student_page_url = '/student/?id=' . $key . '&class=' . $class_dir; ?>
	 <li>
	 <div class="thumb">
		<a href="<?=$student_page_url?>"><img src="<?=$web_loc . '/' . $student->profile;?>" /></a>
	 </div><span class="name"><a href="<?=$student_page_url?>"><?=$student->first;?></a>
	 </span>
	 </li>
	 <? endforeach; ?>
	 </ul>
	 </div>
	 <?
	 ng_css();
}


function ng_get_config($class_dir) {
	$config = null;
	try {
		$site_config_loc = NG_CONTENT_LOC . $class_dir . '/'. NG_PARAMS;
		if (!file_exists($site_config_loc)) {
			throw new Exception('JSON does not exist.');
		}
		$site_config_str = file_get_contents($site_config_loc);				
		$config = json_decode($site_config_str);
	    if (!$config) {
        	throw new Exception('JSON is malformed');
	    }
	} catch (Exception $e) {
	    echo 'Exception: ',  $e->getMessage(), "\n";
	}
	return $config;
}


/* get student info */
function ng_get_student_data( $class_dir, $student_id = null) {
	$config = null;
	$student = null;
	try {
		$config = ng_get_config( $class_dir );
		$students =  (array)$config->students;
		if (!$students[$student_id]) {
			throw new Exception('Student doesn\'t exist.');
		}
		$student = $students[$student_id];
		$student->classInfo = $config;
	} catch (Exception $e) {
	    echo 'Exception: ',  $e->getMessage(), "\n";
	}
	return $student;
}



function ng_css() {
	echo <<<END
	<style type="text/css" media="screen">
	
.student_profile {
	width:100px;
	height:100px; 
}
	
/* student work list */
.student-list {
	margin:0;
	padding:0;
}
.student-list ul {
	margin:0;
	padding:0;
}
.student-list li {
    margin:0 30px 30px 0;
    height:190px;
    display:inline-block;
}
.student-list .thumb, 
.student-list .thumb a {
	width:150px;
	height:150px;
	display:block;
	background:#aaa;
}
.student-list img {
    width:150px;
	height:150px;    
    border:0;
}
.student-list .name {
    font-size:18px;
}
</style>
END;
}


?>
