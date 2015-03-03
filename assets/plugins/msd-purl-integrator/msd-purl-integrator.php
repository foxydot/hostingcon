<?php
/*
Plugin Name: MSD Purl Integrator
Description: Custom plugin for DataCenterWorld
Author: Catherine Sandrick
Version: 0.0.1
Author URI: http://msdlab.com
*/

global $msdlab_purl;

/*
 * Pull in some stuff from other files
*/
if(!function_exists('requireDir')){
    function requireDir($dir){
        $dh = @opendir($dir);

        if (!$dh) {
            throw new Exception("Cannot open directory $dir");
        } else {
            while($file = readdir($dh)){
                $files[] = $file;
            }
            closedir($dh);
            sort($files); //ensure alpha order
            foreach($files AS $file){
                if ($file != '.' && $file != '..') {
                    $requiredFile = $dir . DIRECTORY_SEPARATOR . $file;
                    if ('.php' === substr($file, strlen($file) - 4)) {
                        require_once $requiredFile;
                    } elseif (is_dir($requiredFile)) {
                        requireDir($requiredFile);
                    }
                }
            }
        }
        unset($dh, $dir, $file, $requiredFile);
    }
}
if (!class_exists('MSDPurlIntegrator')) {
    class MSDPurlIntegrator {
        //Properites
        /**
         * @var string The plugin version
         */
        var $version = '0.0.1';
        
        /**
         * @var string $localizationDomain Domain used for localization
         */
        var $localizationDomain = "msdlab_purl_integrator";
        
        /**
         * @var string $pluginurl The path to this plugin
         */
        var $plugin_url = '';
        /**
         * @var string $pluginurlpath The path to this plugin
         */
        var $plugin_path = '';
        
        //Methods
        /**
        * PHP 4 Compatible Constructor
        */
        function MSDPurlIntegrator(){$this->__construct();}
        
        /**
        * PHP 5 Constructor
        */        
        function __construct(){
            //"Constants" setup
            $this->plugin_url = plugin_dir_url(__FILE__).'/';
            $this->plugin_path = plugin_dir_path(__FILE__).'/';
            //get sub-packages
            //requireDir(plugin_dir_path(__FILE__).'/lib/inc');
            //add stock scripts
            add_action('wp_enqueue_scripts', array(&$this,'add_scripts'));
            //add initialization script
            add_action('wp_print_footer_scripts', array(&$this,'print_init'), 20);
            //add options
            add_action('admin_menu', array(&$this,'settings_options'));
        }
        
        function add_scripts(){
            global $is_IE;
            if(!is_admin()){
                if(is_front_page()){
                    wp_deregister_script('jquery');
                    wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', false, '1.7.1');
                    wp_enqueue_script('jquery');
                    wp_enqueue_script('perlhubapi','http://api.purlhub.com/JSAPI/purlServiceApi.min.js?vz=1500',array('jquery'),FALSE);
                    wp_enqueue_script('perlhubdemo',$this->plugin_url.'/lib/js/assetscript.js',array('jquery','perlhubapi'));
                }
            }
        }
        
        function print_init(){
            $post = get_post();
            $script = '
<!-- purlHub service initialization is required. http://support.purlhub.com for configuration options. -->
<script type="text/javascript">
    var states = {
    AL: "Alabama", 
    AK: "Alaska", 
    AZ: "Arizona", 
    AR: "Arkansas", 
    CA: "California", 
    CO: "Colorado", 
    CT: "Connecticut", 
    DE: "Delaware", 
    DC: "District Of Columbia", 
    FL: "Florida", 
    GA: "Georgia", 
    HI: "Hawaii", 
    ID: "Idaho", 
    IL: "Illinois", 
    IN: "Indiana", 
    IA: "Iowa", 
    KS: "Kansas", 
    KY: "Kentucky", 
    LA: "Louisiana", 
    ME: "Maine", 
    MD: "Maryland", 
    MA: "Massachusetts", 
    MI: "Michigan", 
    MN: "Minnesota", 
    MS: "Mississippi", 
    MO: "Missouri", 
    MT: "Montana",
    NE: "Nebraska",
    NV: "Nevada",
    NH: "New Hampshire",
    NJ: "New Jersey",
    NM: "New Mexico",
    NY: "New York",
    NC: "North Carolina",
    ND: "North Dakota",
    OH: "Ohio", 
    OK: "Oklahoma", 
    OR: "Oregon", 
    PA: "Pennsylvania", 
    RI: "Rhode Island", 
    SC: "South Carolina", 
    SD: "South Dakota",
    TN: "Tennessee", 
    TX: "Texas", 
    UT: "Utah", 
    VT: "Vermont", 
    VA: "Virginia", 
    WA: "Washington", 
    WV: "West Virginia", 
    WI: "Wisconsin", 
    WY: "Wyoming"
};
    var user;
    var thestate;
    var config = {
        /* landing page name to display in tracking and reporting */
        pageName: \''.$post->post_title.'\',
        /* don\'t auto-redirect on newly created purl profiles */
        suppressPurlRedirect: true,
        /* This enables purlCode detection via the 3rd to the left sub-domain name */
        subDomainMode: true,
        /* required campaign token (bound to ACL granting this URL access). */
        serviceToken: \''.get_option('msdlab_purl_integrator_campaign_code').'\',
        /* event callbacks - http://support.purlhub.com for API documentation */
        onRecordSave: displayRecord,
        onAnonymous: function() {
            jQuery("body").hide();
            console.log("Anonymous");
            jQuery("hProfileData-FirstName,hProfileData-LastName").hide();
            //window.location.href = "'.get_bloginfo('siteurl').'";
        },
        onPurlLoad: function(data, pRespObj, event) {
            jQuery("body").show();
            var user = pRespObj.purlProfile;
        },
        onPurlLoadError: function(err) {
            jQuery("body").hide();
            console.log("PurlLoadError");
            console.log(err);
            //window.location.href = "'.get_bloginfo('siteurl').'";
        }
    };
    purlService.init(config); // required** initializes the service with your config
    
    
    jQuery(document).ready(function($) {
        if(user !== null && typeof user === "object"){
            jQuery("#input_1_1").val(user.Full_Name);
            jQuery("#input_1_2").val(user.Email);
            
            jQuery("#input_2_1").val(user.Full_Name);
            jQuery("#input_2_2").val(user.Title);
            jQuery("#input_2_3").val(user.Company);
            jQuery("#input_2_4_1").val(user.MailingStreet);
            jQuery("#input_2_4_2").val(user.MailingStreet2);
            jQuery("#input_2_4_3").val(user.MailingCity);
            thestate = states[user.MailingStateProvince];
            jQuery("#input_2_4_4 option.placeholder").removeAttr("selected");
            jQuery("#input_2_4_4 option[value="+thestate+"]").attr("selected",true);
            jQuery("#input_2_4_5").val(user.MailingZipPostalCode);
            jQuery("#input_2_5").val(user.Email);
        }
    });
</script>
<!-- end purl hub --> 
            ';
            print $script;
        }

        function settings_options(){
                if ( count($_POST) > 0 && isset($_POST['msdlab_purl_integrator_settings']) )
                {
                    $options = array (
                    'campaign_code',
                    );
                    
                    foreach ( $options as $opt )
                    {
                        delete_option ( 'msdlab_purl_integrator_'.$opt, $_POST[$opt] );
                        add_option ( 'msdlab_purl_integrator_'.$opt, $_POST[$opt] ); 
                    }           
                     
                }
                add_submenu_page('options-general.php',__('Settings'), __('Purl Integration Settings'), 'administrator', 'msdlab_purl_integrator_options', array(&$this,'msdlab_purl_integrator_settings'));
        }
        function msdlab_purl_integrator_settings()
        {
                ?>
            <style>
                span.note{
                    display: block;
                    font-size: 0.9em;
                    font-style: italic;
                    color: #999999;
                }
                body{
                    background-color: transparent;
                }
                .input-table .description{display:none}
                .input-table li:after{content:".";display:block;clear:both;visibility:hidden;line-height:0;height:0}
                .input-table label{display:block;font-weight:bold;margin-right:1%;float:left;width:14%;text-align:right}
                .input-table label span{display:inline;font-weight:normal}
                .input-table span{color:#999;display:block}
                .input-table .input{width:85%;float:left}
                .input-table .input .half{width:48%;float:left}
                .input-table textarea,.input-table input[type='text'],.input-table select{display:inline;margin-bottom:3px;width:90%}
                .input-table .mceIframeContainer{background:#fff}
                .input-table h4{color:#999;font-size:1em;margin:15px 6px;text-transform:uppercase}
            </style>
            <div class="wrap">
                <h2>MSDLAB Purl Integration Settings</h2>
                
            <form method="post" action="">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#campaign" data-toggle="tab">Campaign</a></li>
                </ul>
                
                <!-- Tab panes -->
                <div class="tab-content">
                  <div class="tab-pane active" id="campaign">
                      <h2>Campaign</h2>
                      <ul class="input-table">
                          <li>
                              <label for="campaign_code">Campaign Code</label>
                              <div class="input">
                                <input name="campaign_code" type="text" id="campaign_code" value="<?php echo get_option('msdlab_purl_integrator_campaign_code'); ?>" class="regular-text" />
                              </div>
                          </li>
                      </ul>
                  </div>
                </div>
                    <p class="submit">
                    <input type="submit" name="Submit" class="button-primary" value="Save Changes" />
                    <input type="hidden" name="msdlab_purl_integrator_settings" value="save" style="display:none;" />
                    </p>
            </form>
            </div>
            <?php }
  } //End Class
} //End if class exists statement

//instantiate
$msdlab_purl = new MSDPurlIntegrator();