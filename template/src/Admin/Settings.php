<?php
namespace {{PLUGIN_NAMESPACE}}\Admin;

Use
    DataTables\Editor,
    DataTables\Editor\Field,
    DataTables\Editor\Format,
    DataTables\Editor\Mjoin,
    DataTables\Editor\Options,
    DataTables\Editor\Upload,
    DataTables\Editor\Validate,
    DataTables\Editor\ValidateOptions;

use {{PLUGIN_NAMESPACE}}\Core\Utils;

class Settings {

	private $plugin_name;

	private $version;

	private $slug;

	private $db;


	public function __construct( $plugin_name, $version, $db ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->slug = '{{PLUGIN_SLUG}}-settings';
		$this->db = $db;
		
		add_action( 'rest_api_init', function () {
			register_rest_route( '{{PLUGIN_SLUG}}/v1', '/settings', array(
				'methods' => 'GET,POST',
				'callback' =>  array($this, 'editor'),
				'permission_callback' => function () {
					return Utils::check_user_role(['administrator']);
				}	
			) );					
		});		

		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_styles'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_scripts'] );

	}

	public function enqueue_styles() {

		if (substr(get_current_screen()->base, -strlen($this->slug)) !== $this->slug) return;
		
		wp_enqueue_style( 'datatables' );
		wp_enqueue_style( 'datatables-editor' );
		wp_enqueue_style( 'datatables-buttons' );
	}

	public function enqueue_scripts( ) {

		if (substr(get_current_screen()->base, -strlen($this->slug)) !== $this->slug) return;

		wp_register_script( 
			$this->slug, 
			{{PLUGIN_CONSTANT}}_ASSETS_URL . 'admin/js/settings.js', 
			array( 'jquery' ), 
			filemtime( {{PLUGIN_CONSTANT}}_ASSETS_PATH . 'admin/js/settings.js' ), 
			true 
		);				
		
		wp_enqueue_script( 'datatables' );
		wp_enqueue_script( 'datatables-editor' );
		wp_enqueue_script( 'datatables-buttons' );
		wp_enqueue_script( $this->slug );
	}

	public function create_menu() {
		add_submenu_page(
			$this->plugin_name,
			__( 'Settings', '{{PLUGIN_SLUG}}' ),
			__( 'Settings', '{{PLUGIN_SLUG}}' ),
			'publish_posts',
			$this->slug, 
			array( $this, 'display' ),
			20,
		);		
	}

    public function display() {
		$version = $this->version;
		include_once {{PLUGIN_CONSTANT}}_ASSETS_PATH . 'admin/partials/datatables.php';
    }

	public function editor() {
		global $wpdb;
		$data = Editor::inst( $this->db, $wpdb->prefix . '{{PLUGIN_PREFIX}}_settings AS settings', 'id')
			->fields(
					Field::inst( 'settings.id', 'id' )->set(false),
					Field::inst( 'settings.name', 'name' ),
					Field::inst( 'settings.value', 'value' ),
					Field::inst( 'settings.description', 'description' ),
					Field::inst( 'settings.created_at', 'created_at' )
						->set( Field::SET_CREATE )
						->setValue( date( "Y-m-d H:i:s" ) ),
					Field::inst( 'settings.updated_at', 'updated_at' )
						->set( Field::SET_BOTH )
						->setValue( date( 'Y-m-d H:i:s' ) )
			)
			// ->debug(true)
			->process( $_POST )
			->json( false );

		return json_decode($data);
	}

}