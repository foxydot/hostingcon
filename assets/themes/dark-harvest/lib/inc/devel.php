<?php
/*
* A useful troubleshooting function. Displays arrays in an easy to follow format in a textarea.
*/
if(!function_exists('ts_data')){
	function ts_data($data){
		$ret = '<textarea class="troubleshoot" rows="20" cols="100">';
		$ret .= print_r($data,true);
		$ret .= '</textarea>';
		print $ret;
	}
}
/*
* A useful troubleshooting function. Dumps variable info in an easy to follow format in a textarea.
*/
if(!function_exists('ts_var')){
	function ts_var($var){
		ts_data(var_export( $var , true ));
	}
}
/*
 * A hook test to see what's running on a hook
 */
 if(!function_exists('hook_test')){
    function hook_test($hook){
        global $wp_filter;
        ts_var( $wp_filter[$hook] );
    }
}
