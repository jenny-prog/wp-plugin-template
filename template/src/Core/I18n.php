<?php
namespace Juzhax\Core;

class I18n {

	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			{{PLUGIN_SLUG}},
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}