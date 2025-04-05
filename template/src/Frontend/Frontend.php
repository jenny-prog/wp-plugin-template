<?php
namespace {{PLUGIN_NAMESPACE}}\Frontend;

class Frontend {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles() {
        wp_register_style( 
			$this->plugin_name, 
			{{PLUGIN_CONSTANT}}_ASSETS_URL . 'frontend/css/frontend.css', 
			[], 
			filemtime ({{PLUGIN_CONSTANT}}_ASSETS_PATH . 'frontend/css/frontend.css'), 
			'all' );		
	
		wp_enqueue_style( $this->plugin_name );
	}
	public function enqueue_scripts() {
        wp_register_script( 
			$this->plugin_name, 
			{{PLUGIN_CONSTANT}}_ASSETS_URL . 'frontend/js/frontend.js', 
			['jquery'], 
			filemtime( {{PLUGIN_CONSTANT}}_ASSETS_PATH . 'frontend/js/frontend.js'), 
			true );

		wp_enqueue_script( $this->plugin_name );
	}

}
