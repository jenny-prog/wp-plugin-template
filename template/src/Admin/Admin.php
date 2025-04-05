<?php
namespace {{PLUGIN_NAMESPACE}}\Admin;

class Admin {

	private $plugin_name;
	private $version;

	private $slug;
	private $plugin_screen_hook_suffix;


	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->slug = '{{PLUGIN_SLUG}}';

		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_styles'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_scripts'] );

	}

	public function enqueue_styles() {
	
		$current_screen = get_current_screen();
		
		// Ensure $current_screen is not null before proceeding
		if ($current_screen && isset($current_screen->base)) {
			// Use strict comparison === to check if strpos() returns false
			if (strpos($current_screen->base, $this->plugin_name) === false) {
				return; // If not on the desired screen, bail early
			}
		}

		wp_register_style( 'datatables','https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css', array(),'2.2.2', 'all' );
		wp_register_style( 'datatables-editor',  plugins_url('assets/Editor-2.4.1/css/editor.dataTables.min.css', dirname(__FILE__)), array(), '2.4.1', 'all' );
		
		wp_register_style( 'datatables-buttons','https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css', array(),'3.2.2', 'all' );
		wp_register_style( 'datatables-select','https://cdn.datatables.net/select/3.0.0/css/select.dataTables.css', array(),'3.0.0', 'all' );

		wp_register_style( 'datatables-dateTime','https://cdn.datatables.net/datetime/1.5.5/css/dataTables.dateTime.min.css', array(),'1.5.5', 'all' );

		wp_register_style( 'datatables-dateTime','https://cdn.datatables.net/datetime/1.4.0/css/dataTables.dateTime.min.css', array(),'1.4.0', 'all' );
		// wp_register_style( 'selectize','https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.9.0/css/selectize.css', array(),'0.9.0', 'all' );
		// wp_register_style( 'selectize','https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.min.css', array(),'0.15.2', 'all' );

		// wp_register_style( 'datatables-selectize',  plugins_url('assets/Editor-2.4.1/css/editor.selectize.css', WP_JUZHAX_PLUGIN_FILE), array(), '2.4.1', 'all' );

		// wp_register_style( 'datatables-rowgroup','https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css', array(),'1.2.0', 'all' );
		// wp_register_style( 'datatables-colreorder','https://cdn.datatables.net/colreorder/1.6.2/css/colReorder.dataTables.min.css', array(),'1.6.2', 'all' );
		// wp_register_style( 'datatables-fixedcolumns','https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css', array(),'4.2.2', 'all' );

		// wp_register_style( 'fira-code-font', '<link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">', array(), '', 'all' );

		// wp_register_style( 'pdf-viewer','https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf_viewer.min.css', array(),'2.6.347', 'all' );


		wp_register_style(
			$this->plugin_name,
			WP_{{PLUGIN_CONSTATNT}}_ASSETS_URL . 'admin/css/admin.css',
			[],
			filemtime( WP_{{PLUGIN_CONSTATNT}}_ASSETS_PATH . 'admin/css/admin.css' ),
			'all'
		);

		// wp_enqueue_style( 'datatables-editor' );
		// wp_enqueue_style( 'datatables-buttons' );
		// wp_enqueue_style( 'datatables-select' );
		// wp_enqueue_style( 'datatables-dateTime' );

		// wp_enqueue_style( 'selectize' );
		// wp_enqueue_style( 'datatables-selectize' );

		// wp_enqueue_style( 'datatables-fixedcolumns' );
		// wp_enqueue_style( 'fira-code-font' );			
		wp_enqueue_style($this->plugin_name);

	}

	public function enqueue_scripts() {
	
		$current_screen = get_current_screen();
		
		// Ensure $current_screen is not null before proceeding
		if ($current_screen && isset($current_screen->base)) {
			// Use strict comparison === to check if strpos() returns false
			if (strpos($current_screen->base, $this->plugin_name) === false) {
				return; // If not on the desired screen, bail early
			}
		}

		wp_register_script( 'datatables','https://cdn.datatables.net/2.2.2/js/dataTables.js',array('jquery'),'2.2.2', true );
		wp_register_script( 'datatables-editor', plugins_url('assets/Editor-2.4.1/js/dataTables.editor.min.js', dirname(__FILE__)), array( 'datatables' ), '2.4.1', true );
	
		wp_register_script( 'datatables-buttons', 'https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js', array( 'datatables' ), '3.2.2', true );
		wp_register_script( 'buttons-datatables', 'https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js', array( 'datatables' ), '3.2.2', true );


		wp_register_script( 'datatables-select', 'https://cdn.datatables.net/select/3.0.0/js/dataTables.select.js', array( 'datatables' ), '3.0.0', true );
		wp_register_script( 'select-datatables', 'https://cdn.datatables.net/select/3.0.0/js/select.dataTables.js', array( 'datatables' ), '3.0.0', true );

		wp_register_script( 'datatables-datetime', 'https://cdn.datatables.net/datetime/1.5.5/js/dataTables.dateTime.min.js', array( 'datatables' ), '1.5.5', true );

		wp_register_script( 'selectize', 'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.9.0/js/standalone/selectize.js', array( 'jquery' ), '0.9.0', true );
		// // wp_register_script( 'selectize', 'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js', array( 'jquery' ), '0.15.2', true );
		
		// // wp_register_script( 'datatables-selectize', plugins_url('assets/Editor-2.4.1/js/editor.selectize.js', WP_JUZHAX_PLUGIN_FILE), array( 'jquery' ), '2.4.1', true );
		// wp_register_script( 'datatables-ellipsis', 'https://cdn.datatables.net/plug-ins/1.12.1/dataRender/ellipsis.js', array( 'jquery' ), '1.12.1', true );
		// wp_register_script( 'datatables-colreorder', 'https://cdn.datatables.net/colreorder/1.6.2/js/dataTables.colReorder.min.js', array( 'jquery' ), '1.6.2', true );
		// wp_register_script( 'datatables-fixedcolumns', 'https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js', array( 'jquery' ), '4.2.2', true );

		// wp_register_script( 'datatables-rowgroup', 'https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js', array( 'jquery' ), '1.2.0', true );
		// wp_register_script( 'papaparse', 'https://cdnjs.cloudflare.com/ajax/libs/PapaParse/4.6.3/papaparse.min.js', array( 'jquery' ), '4.6.3', true );
		// wp_register_script( 'jszip', 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js', array( 'jquery' ), '3.1.3', true );
		// wp_register_script( 'pdfmake', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js', array( 'jquery' ), '0.1.53', true );
		// wp_register_script( 'pdfmake-vfs-fonts', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js', array( 'jquery' ), '0.1.53', true );
		// wp_register_script( 'datatables-button-colvis', 'https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js', array( 'jquery' ), '2.3.6', true );
		// wp_register_script( 'datatables-button-html5', 'https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js', array( 'jquery' ), '2.3.6', true );
		// wp_register_script( 'datatables-button-print', 'https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js', array( 'jquery' ), '2.3.6', true );
		// wp_register_script( 'markdown-it','https://cdnjs.cloudflare.com/ajax/libs/markdown-it/13.0.1/markdown-it.min.js',array('jquery'),'13.0.1', true );
		// wp_register_script( 'pdf','https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js',array('jquery'),'2.6.347', true );
		// wp_register_script( 'moment','https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js',array('jquery'),'2.29.2', true );
		// wp_register_script( 'datatables-moment','https://cdn.datatables.net/plug-ins/1.13.4/dataRender/datetime.js',array('jquery'),'1.13.4', true );



		wp_register_script(
			$this->plugin_name,
			WP_{{PLUGIN_CONSTANT}}_ASSETS_URL . 'admin/js/admin.js',
			['jquery'],
			filemtime( WP_{{PLUGIN_CONSTANT}}_ASSETS_PATH . 'admin/js/admin.js'),
			true
		);
		
		wp_enqueue_script( $this->plugin_name );

		$var["nonce"] = wp_create_nonce('wp_rest');

		wp_localize_script({{PLUGIN_SLUG}}, {{PLUGIN_SLUG}}, $var);			
	}

	public function add_menu_page() {
	//add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )

		$icon_svg = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDIwMDEwOTA0Ly9FTiIKICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiB3aWR0aD0iNTAwLjAwMDAwMHB0IiBoZWlnaHQ9IjUwMC4wMDAwMDBwdCIgdmlld0JveD0iMCAwIDUwMC4wMDAwMDAgNTAwLjAwMDAwMCIKIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIG1lZXQiPgo8bWV0YWRhdGE+CkNyZWF0ZWQgYnkgcG90cmFjZSAxLjE1LCB3cml0dGVuIGJ5IFBldGVyIFNlbGluZ2VyIDIwMDEtMjAxNwo8L21ldGFkYXRhPgo8ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLjAwMDAwMCw1MDAuMDAwMDAwKSBzY2FsZSgwLjEwMDAwMCwtMC4xMDAwMDApIgpmaWxsPSIjMDAwMDAwIiBzdHJva2U9Im5vbmUiPgo8cGF0aCBkPSJNMTgwMCA0MDE1IGwwIC0zNjUgMzYwIDAgMzYwIDAgMCAtMTA5NSAwIC0xMDk1IC03MzAgMCAtNzMwIDAgMCAzNjUKMCAzNjUgLTM2MCAwIC0zNjAgMCAwIC0zNjUgMCAtMzY1IDM2MCAwIDM2MCAwIDAgLTM2NSAwIC0zNjUgNzMwIDAgNzMwIDAgMAozNjUgMCAzNjUgMzYwIDAgMzYwIDAgMCAxMDkwIDAgMTA5MCAxMjAgMCAxMjAgMCAwIDEyNSAwIDEyNSAxMjUgMCAxMjUgMCAwCi0xMjUgMCAtMTI1IDI0MCAwIDI0MCAwIDAgMTI1IDAgMTI1IDEyNSAwIDEyNSAwIDAgMTIwIDAgMTIwIDEyMCAwIDEyMCAwIDAKMTIwIDAgMTIwIC0xMjAgMCAtMTIwIDAgMCAtMTIwIDAgLTEyMCAtMTI1IDAgLTEyNSAwIDAgMTIwIDAgMTIwIC0xMjAgMCAtMTIwCjAgMCAtMTIwIDAgLTEyMCAtMTIwIDAgLTEyMCAwIDAgMTI1IDAgMTI1IC05NjUgMCAtOTY1IDAgMCAtMzY1eiBtMjQxMCAtNSBsMAotMTIwIC0xMjAgMCAtMTIwIDAgMCAxMjAgMCAxMjAgMTIwIDAgMTIwIDAgMCAtMTIweiIvPgo8L2c+Cjwvc3ZnPgo=';
		add_menu_page(
			__( {{PLUGIN_NAME}}, {{PLUGIN_SLUG}} ),
			__( {{PLUGIN_NAME}}, {{PLUGIN_SLUG}}  ),
			'manage_options',
			$this->plugin_name,
			'',
			$icon_svg,
			1
		);

	}

	public function remove_submenu_page() {
		remove_submenu_page($this->plugin_name, $this->plugin_name);

		// remove_submenu_page($this->slug, $this->slug);
		// remove_submenu_page($this->slug, $this->slug.'-hidden-page');
	}		

}
