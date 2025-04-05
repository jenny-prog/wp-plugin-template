<?php
namespace {{PLUGIN_NAMESPACE}};

use DataTables\Database;
use {{PLUGIN_NAMESPACE}}\Core\Loader;
use {{PLUGIN_NAMESPACE}}\Core\I18n;
use {{PLUGIN_NAMESPACE}}\Core\Updater;


class {{PLUGIN_NAMESPACE}}
{

	protected $loader;
    protected $plugin_name;
	protected $version;

	protected $db;

    
	public function __construct( $plugin_name, $plugin_version ) {

		$this->plugin_name = $plugin_name;
        $this->version = $plugin_version;		

		$this->load_updater();
		// $this->load_dependencies();


		$this->loader = new Loader();

		$this->load_db();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_frontend_hooks();

		

	}

	private function load_db() {
		$sql_details = array(
			"type" => "Mysql",          // Database type: "Mysql", "Postgres", "Sqlserver", "Sqlite" or "Oracle"
			"user" => DB_USER,          // Database user name
			"pass" => DB_PASSWORD,      // Database password
			"host" => DB_HOST,          // Database host
			"port" => "",               // Database connection port (can be left empty for default)
			"db"   => DB_NAME,          // Database name
			"dsn"  => "charset=utf8mb4",// PHP DSN extra information. Set as `charset=utf8mb4` if you are using MySQL
			"pdoAttr" => array()        // PHP PDO attributes array. See the PHP documentation for all options
		);
		
		$this->db = new Database( $sql_details );
	}	

	private function load_updater() {
		global $wpdb;

		if (!is_admin()) return;

		static $table_exists = null;

		// Check table existence only once per request
		if ( is_null( $table_exists ) ) {
			$table_name = $wpdb->prefix . '{{PLUGIN_PREFIX}}settings';
			$table_exists = (bool) $wpdb->get_var( $wpdb->prepare(
				"SHOW TABLES LIKE %s", $table_name
			) );
		}

		// If the table doesn't exist, return false
		if ( !$table_exists ) {
			$this->display_admin_error("<strong>WP Solver Plugin database table missing</strong>: Please deactivate and reactiave the plugin");
			return false;
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-solver-updater.php';

		$github_username = $this->get('UPDATE_USERNAME');
		$token = $this->get('UPDATE_PASSWORD');

		if (empty($github_username) || empty($token)) return;
	
		$updater = new Updater (
			plugin_dir_path( dirname( __FILE__ ) ) . '{{PLUGIN_SLUG}}.php',
			$github_username,
			{{PLUGIN_SLUG}}
		);

		$updater->authorize($token);

	}		  
	
	private function set_locale() {

		$plugin_i18n = new I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new Admin\Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_page');
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'remove_submenu_page', 80);
		// $this->loader->add_action( 'wp_loaded', $plugin_admin, 'wp_loaded');

		$plugin_settings = new Admin\Settings( $this->get_plugin_name(), $this->get_version(), $this->get_db() );
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_settings, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_settings, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_settings, 'create_menu');

	}

	private function define_frontend_hooks() {

		$plugin_frontend = new Frontend\Frontend ( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_frontend, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_frontend, 'enqueue_scripts' );

		$plugin_sample = new Frontend\Sample( $this->get_plugin_name(), $this->get_version(), $this->get_db() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_sample, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_sample, 'enqueue_scripts' );

	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

	public function get_db() {
		return $this->db;
	}		
	
	public static function get( $name = "" ) {
		global $wpdb;
	
		if ( !$name ) return false;
	
		// Prepare and execute the query
		$query = $wpdb->prepare(
			"SELECT value FROM {$wpdb->prefix}{{PLUGIN_PREFIX}}_settings WHERE `name` = %s;", $name
		);
	
		$result = $wpdb->get_var( $query );
	
		// Return the result or false if it's null
		return $result !== null ? $result : false;
	}

	public static function debug($var) {
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}		

	public static function display_admin_error( $message ) {
		// Output the error message via an admin notice
		add_action( 'admin_notices', function() use ( $message ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php echo wp_kses_post( $message ); ?></p>
			</div>
			<?php
		});
	}	
	
}