<?php
if(!class_exists('WPAlchemy_MetaBox')){
    include_once (WP_CONTENT_DIR.'/wpalchemy/MetaBox.php');
}
global $wpalchemy_media_access;
if(!class_exists('WPAlchemy_MediaAccess')){
    include_once (WP_CONTENT_DIR.'/wpalchemy/MediaAccess.php');
}
$wpalchemy_media_access = new WPAlchemy_MediaAccess();
add_action('init','add_landingpage_metaboxes');
add_action('admin_footer','landingpage_footer_hook');
//add_action( 'admin_print_scripts', 'landingpage_metabox_styles' );

function add_landingpage_metaboxes(){
    global $post,$landingpage_metabox;
    $landingpage_metabox = new WPAlchemy_MetaBox(array
    (
        'id' => '_landingpage',
        'title' => 'Landing Page Sections',
        'types' => array('post','page'),
        'context' => 'normal', // same as above, defaults to "normal"
        'priority' => 'high', // same as above, defaults to "high"
        'template' => get_stylesheet_directory() . '/lib/template/metabox-landingpage.php',
        'autosave' => TRUE,
        'mode' => WPALCHEMY_MODE_EXTRACT, // defaults to WPALCHEMY_MODE_ARRAY
        'prefix' => '_msdlab_', // defaults to NULL
    ));
}

function landingpage_footer_hook()
{
    ?><script type="text/javascript">
        jQuery('#postdivrich').after(jQuery('#_landingpage_metabox'));
    </script><?php
}
/* eof */