WordPress Plugin Boilerplate Generator

A powerful WordPress Plugin Boilerplate Generator that creates customizable, modular plugins with Composer, PSR-4 autoloading, DataTables Editor, Parsedown, and GitHub update integration.

Features
	•	📂 Modular structure with src/ directories for Admin, Frontend, Shortcodes, Widgets, etc.
	•	🔄 Composer support with PSR-4 autoloading.
	•	📦 DataTables Editor and Parsedown integration.
	•	🌐 Automatic GitHub update integration.
	•	📌 Customizable constants, namespaces, and prefixes.

⸻

Installation
	1.	Clone this repository:

    git clone https://github.com/juzhax/wp-plugin-template.git

	2.	Navigate to the directory:

    cd wp-plugin-template

	3.	Install Composer dependencies:

    composer install



⸻

Usage
	1.	Run the generator script:

    php generate-plugin.php

	2.	Follow the prompts to enter your plugin details:
	•	Plugin Name
	•	Plugin Slug (e.g., wp-sample)
	•	Plugin URL
	•	Author Name
	•	Author Email
	•	Author URL
	•	Plugin Description
	3.	The generator will:
	•	Copy the template files to a new folder named after your plugin slug.
	•	Replace placeholders with your provided details.
	•	Rename all relevant files (e.g., plugin-name.php to wp-sample.php).
	•	Set up namespaces and constants.

⸻

Generated Structure

wp-sample/
├── assets/
├── composer.json
├── index.php
├── wp-sample.php
├── src/
│   ├── Admin/
│   ├── Core/
│   ├── Frontend/
│   └── WPSample.php
├── vendor/



⸻

Requirements
	•	PHP 8.3 or higher
	•	Composer

⸻

License

This project is licensed under the GPL-2.0-or-later license.