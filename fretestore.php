<?php
/**
 * Plugin name:         FreteStore
 * Plugin uri:              https://github.com/Devmunds/FreteStore
 * Description:           Cálculo do frete com o serviço da web Frete Store
 * Version:                 1.0.0
 * Requires at least:   4.1.0
 * WC tested up to:    5.5.1
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

    require_once("includes/functions.php");
    require_once("includes/hooks.php");
    require_once("includes/variables.php");
    //
    function fs_shipping_methods(){
        if(! class_exists('Fs_shipping_method')){
            class Fs_shipping_method extends WC_Shipping_Method{

				/**
				 * @access public
				 * @return void
				 */
                //
                public function __construct($instance_id = 0){
                    global $fs_pluginId, $fs_pluginName, $fs_pluginDescription, $fs_pluginSupports;

                    $this->instance_id = absint( $instance_id );
                    $this->id = $fs_pluginId;
                    $this->method_title = __($fs_pluginName);
                    $this->method_description = __($fs_pluginDescription);
                    $this->enabled = "yes" ;
                    $this->title = $fs_pluginName; 
                    $this->availability = 'including';

                    $this->countries = array('BR') ;

					$this->supports = $fs_pluginSupports;

                    $this->init();
                }
				/**
				 * @access public
				 * @return void
				 */
                //
                function init(){

                    $this->init_form_fields(); 
                    $this->init_settings(); 

                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }
                //
                function init_form_fields() {
					$this->instance_form_fields = array(
						'FS_IS_ACTIVE' => array(
							'title' => __( 'Status Frete Store' ),
							'type' => 'checkbox',
							'description' => __( 'Para ativar os métodos de entrega frete store você deve checar essa opção.' ),
							'label' => 'Ativar  Frete Store como Método de Entrega ?'
                        )
                    );
                }
                //
                public function is_available( $package ){
					return true;
			  	}
                //
                function fs_calc_shipping(){
                    return 50;
                }    
                /**
				 * @access public
				 * @param mixed $package
				 * @return void
				 */
                //
                public function calculate_shipping($package = array()) {
                        $fs_rate = array(
                            'id'      => 'rte-rodonaves',
                            'label' => 'RTE Rodonaves',
                            'cost'  => $this->fs_calc_shipping(),
                            'calc_tax' => 'per_item'
                        );
                        $this->add_rate( $fs_rate );                  
					
				}
            }
        }
    }
    add_action( 'woocommerce_shipping_init', 'fs_shipping_methods' );

	function add_fs_shipping_methods( $methods ) {
		$methods['fretestore'] = 'Fs_shipping_method';
		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'add_fs_shipping_methods' );

}