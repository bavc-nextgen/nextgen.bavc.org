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

# full filesystem path to this directory
define('NG_PLUGIN_DIR', dirname(__FILE__));
# full filesystem path to content dir
define('NG_CONTENT_LOC', "/home/nextgen/webroot/");
define('NG_CONTENT_DIR', "content/");
define('NG_WEBROOT', "http://nextgen.bavc.org/");
define('NG_PARAMS', "config.json");

/**
 *  generate student gallery
 */
function ng_student_gallery( $class_dir ) {

	// get config file from class root
    $config = ng_get_config($class_dir);
    
    // create $web_loc variable from constants and class_dir
    $web_loc = NG_WEBROOT . "content/" . $class_dir;

	// create student list
    ?>
     <div class="student-list">
     <ul>
     <? if (isset($config->students)) :
        foreach( (array) $config->students as $key => $student) :
        $student_page_url = '/student/?id=' . $key . '&class=' . $class_dir; ?>
     <li>
     <div class="thumb"><a href="<?=$student_page_url?>"><img class="profile" src="<?=$web_loc . '/' . $student->profile;?>" /><?php if (isset($student->profile_hover)) : ?><img class="hover" src="<?=$web_loc . '/' . $student->profile_hover;?>" /><?else:?><img class="hover" src="<?=$web_loc . '/' . $student->profile;?>" /><?endif;?></a>
     </div><span class="name"><a href="<?=$student_page_url?>"><?=$student->first;?></a>
     </span>
     </li>
     <? endforeach; endif; ?>
     </ul>
     </div>
     <?

	// print out CSS
    ng_css();
}

function ng_student_profile( $class_dir, $student_id ) {

    $student = ng_get_student_data( $class_dir, $student_id);

    ?>
	
	<!-- profile image -->
	<div class="student_profile_image">
    <img src="<?=NG_WEBROOT . NG_CONTENT_DIR . $class_dir .  $student->profile ?>" />
    </div>

    <?php if (isset($student->links) && sizeof((array)$student->links) > 0) : ?>
    <div class="student_profile_links">
    <h3>Links</h3>
    <ul>
    <?php foreach($student->links as $k => $link) : ?>
    <li><a href="<?=$link;?>"><?=$k;?></a></li>
    <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    </div>

    <?php if (isset($student->embed) && sizeof((array)$student->embed) > 0) : ?>
    <?php foreach($student->embed as $k=>$embed) : ?>
    <div>
        <h3><?php echo $k;?></h3>
        <?php echo $embed; ?>
    </div>
    <?php endforeach; endif; ?>

    <?php

    ng_css();
}


function ng_get_config($class_dir) {
    $config = null;
    try {
        $site_config_loc = NG_CONTENT_LOC . NG_CONTENT_DIR . $class_dir . '/'. NG_PARAMS;
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
    max-width:250px;
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
		overflow:hidden;
		background:#aaa;
	}
	.student-list .thumb:hover img.profile {
		display:none;
	}
	.student-list .thumb:hover img.hover {
		display:block;
	}
	.student-list img {
		width:150px;
		border:0;
	}
	.student-list .name {
		font-size:18px;
	}
	
.student_profile_links { }
.student_profile_links ul, 
.student_profile_links li {
    list-style-type:square;
}
.student_profile_image {
	max-width:200px;
}
</style>
END;
}
/*
function ng_admin_sidebar() {

    echo '  <div id="excludepagediv" class="new-admin-wp25">';
    echo '      <div class="outer"><div class="inner">';
    echo '      <p><label for="ep_this_page_included" class="selectit">';
    echo '      <input ';
    echo '          type="checkbox" ';
    echo '          name="ep_this_page_included" ';
    echo '          id="ep_this_page_included" ';
    if ( ep_this_page_included() )
        echo 'checked="checked"';
    echo ' />';
    echo '          '.__( 'Include this page in lists of pages', EP_TD ).'</label>';
    echo '      <input type="hidden" name="ep_ctrl_present" value="1" /></p>';
    // If there are custom menus (WP 3.0+) then we need to clear up some
    // potential confusion here.
    if ( ep_has_menu() ) {
        echo '<p id="ep_custom_menu_alert"><em>';
        if ( current_user_can( 'edit_theme_options' ) )
            printf( __( 'N.B. This page can still appear in explicitly created <a href="%1$s">menus</a> (<a id="ep_toggle_more" href="#ep_explain_more">explain more</a>)', EP_TD),
                "nav-menus.php" );
        else
            _e( 'N.B. This page can still appear in explicitly created menus (<a id="ep_toggle_more" href="#ep_explain_more">explain more</a>)', EP_TD);
        echo '</em></p>';
        echo '<div id="ep_explain_more"><p>';
        if ( current_user_can( 'edit_theme_options' ) )
            printf( __( 'WordPress provides a simple function for you to maintain your site <a href="%1$s">menus</a>. If you create a menu which includes this page, the checkbox above will not have any effect on the visibility of that menu item.', EP_TD),
                "nav-menus.php" );
        else
            _e( 'WordPress provides a simple function for you to maintain the site menus, which your site administrator is using. If a menu includes this page, the checkbox above will not have any effect on the visibility of that menu item.', EP_TD);
        echo '</p><p>';
        echo _e( 'If you think you no longer need the Exclude Pages plugin you should talk to your WordPress administrator about disabling it.', EP_TD );
        echo '</p></div>';
    }
    echo '      </div><!-- .inner --></div><!-- .outer -->';
    echo '  </div><!-- #excludepagediv -->';
}

function ng_init() {
    // Call this function on the get_pages filter
    // (get_pages filter appears to only be called on the "consumer" side of WP,
    // the admin side must use another function to get the pages. So we're safe to
    // remove these pages every time.)
    //add_filter('get_pages','ep_exclude_pages');
    // Load up the translation gear
    //$locale = get_locale();
    //$folder = rtrim( basename( dirname( __FILE__ ) ), '/' );
    //$mo_file = trailingslashit( WP_PLUGIN_DIR ) . "$folder/locale/" . EP_TD . "-$locale.mo";
    //load_textdomain( EP_TD, $mo_file );
}

function ng_admin_init() {
    global $wp_version;
    add_meta_box('ng_admin_meta_box',
        __( 'NextGen Students', 'nextgen-students' ),
        'ng_admin_sidebar',
        'page',
        'side',
        'low');
}

add_action( 'init' , 'ng_init' );
add_action( 'admin_init', 'ng_admin_init' );
*/


?>




