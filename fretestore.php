<?php
/**
 * Plugin name:         FreteStore
 * Plugin uri:              https://github.com/Devmunds/FreteStore
 * Description:           Plugin para calculo de frete com api Devmunds
 * Version:                 1.0.0
 * Requires at least:   4.1.0
 * WC tested up to:    4.1.0
 * Author:                   Devmunds
 * Author uri:              https://devmunds.com.br/
 * License:                  GPLv2 or later
 * License uri:             https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 **/

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    $pluginDir = plugin_dir_path(__FILE__);

    require_once("/includes/functions.php");
    require_once("/includes/variables.php");

    function fs_shipping_methods(){
        if(!lcass_exists('Fs_shipping_method')){
            class Fs_shipping_method extends WC_Shipping_Method{

                public function __construct(){
                    global $pluginId, $pluginName, $pluginDescription;

                    $this->id = $pluginId;
                    $this->method_title = $pluginName;
                    $this->method_description = __($pluginDescription);
                    $this->enabled = "yes" ;
                    $this->title = "RTE Rodonaves"; 


                    $this->init();
                }

                //
                function init(){

                    $this->init_form_fields(); 
                    $this->init_settings(); 

                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }

            }
        }
    }



}