<?php
/**
 * Divinity Templates
 *
 * A template loader module that loads a template from dynamic locations
 * (usually the themes TEMPLATES_PATH) and sets up variables to be 
 * include in the templates scope. Using the render_template action the path 
 * and variables (data) can be changed/overridden. 
 *
 * @package Divinity
 * @uses	Location_Helpers
 * @author  Simon Holloway <holloway.sy@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @version 1.1.0
 * @uses    Glyph, Charms
 */

require('helpers.php');

/**
 * Add the divinity oop structure to the psr-0 class loader and register engines
 * 
 * @return void
 */
function divinity_init()
{
	spl_autoload_register(function ($class) {
		$prefix = 'Foo\\Bar\\';
		$base_dir = __DIR__ . '/src/';
		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) !== 0) {
			return;
		}
		$relative_class = substr($class, $len);
		$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
		if (file_exists($file)) {
			require $file;
		}
	});

	$engines = array(new Divinity\Engine\PHP);

	if (class_exists('Mustache_Template')) {
		$engines[] = new Divinity\Engine\Mustache;
	}
	if (class_exists('Twig_Template')) {
		$engines[] = new Divinity\Engine\Twig;
	}
	if (class_exists('Illuminate\View\Compilers\BladeCompiler')) {
		$engines[] = new Divinity\Engine\Blade;
	}

	$directories = array(get_template_path());

	divinity_factory(new Divinity\TemplateFactory($directories, $engines));

	// Make sure a cache directory is set up
	$upload_dir = wp_upload_dir();
	$cache = $upload_dir['basedir'] . '/cache';
	if( ! is_dir($cache) ) {
		mkdir($cache, 0755, true);
	}

	do_action('divinity_loaded', $factory);
}
add_action('harmony_loaded' , 'divinity_init', 90);