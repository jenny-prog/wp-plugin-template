<?php

function renameFiles($outputDir, $pluginSlug, $pluginNamespace)
{
    // Define all files that need renaming
    $filesToRename = [
        // Key = Old file path, Value = New file path with replacements
        "plugin-name.php" => "$pluginSlug.php",
        "languages/plugin-name.pot" => "languages/{$pluginSlug}.pot",
        "src/PluginName.php" => "src/{$pluginNamespace}.php",
        
        // "src/Admin/PluginNameAdmin.php" => "src/Admin/{$pluginNamespace}Admin.php",
        // "src/Frontend/PluginNameFrontend.php" => "src/Frontend/{$pluginNamespace}Frontend.php",
        // "src/Shortcodes/PluginNameShortcodes.php" => "src/Shortcodes/{$pluginNamespace}Shortcodes.php",
        // "src/Widgets/PluginNameWidget.php" => "src/Widgets/{$pluginNamespace}Widget.php",
        // "src/Core/PluginNameLoader.php" => "src/Core/{$pluginNamespace}Loader.php",
    ];

    foreach ($filesToRename as $oldFile => $newFile) {
        $oldPath = "$outputDir/$oldFile";
        $newPath = "$outputDir/$newFile";

        if (file_exists($oldPath)) {
            rename($oldPath, $newPath);
            echo "Renamed: $oldPath ➔ $newPath\n";
        } else {
            echo "File not found, skipping: $oldPath\n";
        }
    }
}

function prompt($message, $default = null)
{
    echo $message;
    if ($default) {
        echo " [$default]";
    }
    echo ": ";

    $input = trim(fgets(STDIN));
    return $input === '' && $default ? $default : $input;
}

function copyDirectory($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst, 0777, true);

    while (false !== ($file = readdir($dir))) {
        if ($file != '.' && $file != '..') {
            if (is_dir("$src/$file")) {
                copyDirectory("$src/$file", "$dst/$file");
            } else {
                copy("$src/$file", "$dst/$file");
            }
        }
    }
    closedir($dir);
}

function replacePlaceholdersInFile($filePath, $replacements)
{
    $content = file_get_contents($filePath);

    foreach ($replacements as $key => $value) {
        $content = str_replace("{{{$key}}}", $value, $content);
    }

    file_put_contents($filePath, $content);
}

function replacePlaceholdersRecursively($dir, $replacements)
{
    $allowedExtensions = ['php', 'json', 'md', 'css', 'js', 'html', 'txt'];
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

    foreach ($files as $file) {
        if ($file->isFile() && (in_array($file->getExtension(), $allowedExtensions) || $file->getExtension() === '')) {
            replacePlaceholdersInFile($file->getPathname(), $replacements);
        }
    }
}

// User Inputs
$pluginName = prompt("Enter Plugin Name", "WP Sample");
$pluginSlug = prompt("Enter Plugin Slug (e.g., my-plugin)", "wp-sample");
$pluginNamespace = str_replace('-', '', ucwords($pluginSlug, '-')); // Converts 'wp-sample' to 'WpSample'
$pluginConstant = strtoupper(str_replace('-', '_', $pluginSlug)); // Converts 'wp-sample' to 'WP_SAMPLE'
$pluginPrefix = strtolower(str_replace('-', '_', $pluginSlug)) . '_'; // Converts 'wp-sample' to 'wp_sample_'
$pluginFunctionSlug = strtolower(str_replace('-', '_', $pluginSlug)); // Converts 'wp-sample' to 'wp_sample'
$pluginURL = prompt("Enter Plugin URL", "https://github.com/juzhax/wp-sample");
$authorName = prompt("Enter Author Name", "juzhax");
$authorEmail = prompt("Enter Author Email", "juzhax@gmail.com");
$authorURL = prompt("Enter Author URL", "https://juzhax.com");
$pluginDescription = prompt("Enter Plugin Short Description", "A WordPress Plugin that work on everything.");


echo "\nYour Inputs:\n";
echo "Plugin Name: $pluginName\n";
echo "Plugin Slug: $pluginSlug\n";
echo "Plugin Namespace: $pluginNamespace\n";
echo "Plugin URL: $pluginURL\n";
echo "Plugin Constant: $pluginConstant\n";
echo "Plugin Prefix: $pluginPrefix\n";
echo "Plugin Function Slug: $pluginFunctionSlug\n";
echo "Author Name: $authorName\n";
echo "Author Email: $authorEmail\n";
echo "Author URL: $authorURL\n";
echo "Plugin Description: $pluginDescription\n";


// Prepare replacements
$replacements = [
    "PLUGIN_NAME" => $pluginName,
    "PLUGIN_SLUG" => $pluginSlug,
    "PLUGIN_NAMESPACE" => $pluginNamespace,
    "PLUGIN_CONSTANT" => $pluginConstant,
    "PLUGIN_PREFIX" => $pluginPrefix,
    "PLUGIN_FUNCTION_SLUG" => $pluginFunctionSlug,
    "PLUGIN_URL" => $pluginURL,
    "AUTHOR_NAME" => $authorName,
    "AUTHOR_EMAIL" => $authorEmail,
    "AUTHOR_URL" => $authorURL,
    "PLUGIN_DESCRIPTION" => $pluginDescription,
];

// Copy the template directory to the new plugin directory
$templateDir = __DIR__ . '/template';
$outputDir = __DIR__ . "/$pluginSlug";

copyDirectory($templateDir, $outputDir);

// Replace placeholders
replacePlaceholdersRecursively($outputDir, $replacements);

// Rename the main plugin file and the main class file
renameFiles($outputDir, $pluginSlug, $pluginNamespace);

echo "Plugin generated successfully at: $outputDir\n";