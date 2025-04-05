<?php
namespace {{PLUGIN_NAMESPACE}}\Core;

class Activator {

	public static function activate() {
		// flush_rewrite_rules();

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // The character set and collation for the tables
        $charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . '{{PLUGIN_PREFIX}}settings';
		$sql = "CREATE TABLE $table_name (
                  id INT AUTO_INCREMENT,
                  name VARCHAR(255) NOT NULL,
				  value VARCHAR(255) NOT NULL,
				  description VARCHAR(255),
                  created_at DATETIME,
                  updated_at DATETIME,
                  PRIMARY KEY (id),
				  UNIQUE KEY `name` (`name`)
		) $charset_collate;";
		dbDelta( $sql );
		
		self::insert_default_settings();
	}

	private static function insert_default_settings() {
		global $wpdb;

		$table_name = $wpdb->prefix . '{{PLUGIN_PREFIX}}settings';
		$default_settings = array(
			array(
				  'name' => 'UPDATE_URL',
				  'value' => 'https://github.com/{{PLUGIN_AUTHOR}}/{{PLUGIN_SLUG}}',
				  'description' => 'Github Update URL',
			),
			array(
				'name' => 'UPDATE_USERNAME',
				'value' => '{{PLUGIN_AUTHOR}}',
				'description' => 'Github App Username',
		  	),
			array(
				'name' => 'UPDATE_PASSWORD',
				'value' => 'password',
				'description' => 'Github App Password',
		  	),
			array(
				'name' => 'DEBUG_ENABLED',
				'value' => '0',
				'description' => 'Debug Mode ( 0 or 1 )',
		  	),
			array(
				'name' => 'DEFAULT_IMAGE_URL',
				'value' => '/wp-content/plugins/{{PLUGIN_SLUG}}/public/img/default.png',
				'description' => 'Default image for SEO',
		  	),
			  
		);

		$wpdb->query('START TRANSACTION');

		try {
			foreach ($default_settings as $settings) {
				$query = $wpdb->prepare(
					"INSERT IGNORE INTO $table_name (
						name,
						value,
						description,
						created_at,
						updated_at
						)
					VALUES (%s, %s, %s, NOW(), NOW())",
						$settings['name'],
						$settings['value'],
						$settings['description']

				);

				if ($wpdb->query($query) === false) {
					$success = false;
					break; // Exit the loop if the query fails
				}
			}

			$wpdb->query('COMMIT');
		} catch (Exception $e) {

			$wpdb->query('ROLLBACK'); // Something went wrong, rollback the transaction
			error_log($e->getMessage());
			wp_die('Error activating plugin: ' . $e->getMessage());
		}
	}	

}
