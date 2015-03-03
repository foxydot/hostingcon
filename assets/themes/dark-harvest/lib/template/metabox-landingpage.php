<?php global $wpalchemy_media_access; ?>
<?php 

$postid = is_admin()?$_GET['post']:$post->ID;
$template_file = get_post_meta($postid,'_wp_page_template',TRUE);
  // check for a template type
if (is_admin() && ($template_file == 'front-page.php' || $template_file == 'landing-page.php' || $post->ID == get_option('page_on_front'))) { ?>
<style>
    .landingpage_meta_control .table {display: block; width: 100%;}
    .landingpage_meta_control .row {display: block;cursor: move;border-bottom: 1px solid #333;}
    .landingpage_meta_control .row:before,
.landingpage_meta_control .row:after {
    content: " "; /* 1 */
    display: table; /* 2 */
}

.landingpage_meta_control .row:after {
    clear: both;
}

/**
 * For IE 6/7 only
 * Include this rule to trigger hasLayout and contain floats.
 */
.landingpage_meta_control .row {
    *zoom: 1;
}
.landingpage_meta_control .cell {display: block; clear: both;margin-left: 1rem;}
    .even {background: #eee;}
    .odd {background: #fff;}
    .file input[type="text"] {width: 75%}
    .landingpage_meta_control label{ display:block; font-weight:bold; margin-right: 1%;float: left; width: 14%; text-align: right;}
 .input_container{width: 85%;float: left;}
.landingpage_meta_control textarea, .landingpage_meta_control input[type='text'], .landingpage_meta_control select,.landingpage_meta_control .wp-editor-wrap
{ display:inline;margin-bottom:3px; width: 90%;
     }
     .landingpage_meta_control .file input[type='text']{width: 76%;}
</style>
<div class="landingpage_meta_control">
 <p id="warning" style="display: none;background:lightYellow;border:1px solid #E6DB55;padding:5px;">Order has changed. Please click Save or Update to preserve order.</p>
    <div class="table">
    <?php $i = 0; ?>
    <?php while($mb->have_fields_and_multi('sections',array('limit' => 6))): ?>
    <?php $mb->the_group_open(); ?>
    <div class="row <?php print $i%2==0?'even':'odd'; ?>">
        <div class="cell">
            <?php $mb->the_field('title'); ?>
            <label>Section Title</label>            
            <div class="input_container">
                <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
            </div>
        </div>
        <div class="cell">
            <?php $mb->the_field('class'); ?>
            <label>Section CSS Classes</label>            
            <div class="input_container">
                <input type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>"/>
            </div>
        </div>
        <div class="cell">
            <label>Section Content</label>
            <div class="input_container">
                <?php 
                $mb->the_field('content');
                $mb_content = html_entity_decode($mb->get_the_value(), ENT_QUOTES, 'UTF-8');
                $mb_editor_id = sanitize_key($mb->get_the_name());
                $mb_settings = array('textarea_name'=>$mb->get_the_name(),'textarea_rows' => '20',);
                wp_editor( $mb_content, $mb_editor_id, $mb_settings );
                ?>
            </div>    
        </div>
        <div class="cell">
            <a href="#" class="dodelete button alignright">Remove Tab</a>
        </div>
    </div>
    <?php $i++; ?>
    <?php $mb->the_group_close(); ?>
    <?php endwhile; ?>
        </div>
    <p style="margin-bottom:15px; padding-top:5px;"><a href="#" class="docopy-sections button">Add Sections</a>
    <a href="#" class="dodelete-sections button">Remove All Sections</a></p>
</div>
<script>
jQuery(function($){
    $("#wpa_loop-tabs").sortable({
        change: function(){
            $("#warning").show();
        }
    });
    $("#postdivrich").after($("#_landingpage_metabox")).hide();
});</script>
<?php } else {
    print "Select \"Landing Page\" template and save to activate.";
} ?>
