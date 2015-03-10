<?php
/**
 * Divinity - Templating Library
 *
 * Part of the Harmony Group 
 *
 * Plugin Name: Harmony Divinity
 * Depends: Harmony Runes
 * 
 * @package    Harmony
 * @subpackage Divinity
 * @author     Simon Holloway <holloway.sy@gmail.com>
 * @license    http://opensource.org/licenses/MIT MIT
 * @version    2.0.0
 */

// Register the autoloader and retrive the on ready handler
$onReady = require('autoloader.php');

/**
 * Initialize Divinity
 * 
 * Add the divinity oop structure to the psr-0 class loader and register engines
 * 
 * @return void
 */
$onReady(function()
{
	$engines = array(new Harmony\Divinity\Engine\PhpEngine);

	if (class_exists('Mustache_Template')) {
		$engines[] = new Harmony\Divinity\Engine\MustacheEngine;
	}
	if (class_exists('Twig_Template')) {
		$engines[] = new Harmony\Divinity\Engine\TwigEngine;
	}
	if (class_exists('Illuminate\View\Compilers\BladeCompiler')) {
		$engines[] = new Harmony\Divinity\Engine\BladeEngine;
	}

	$directories = array(get_theme_root() . '/harmony/templates');

	registery('divinity.factory', new Harmony\Divinity\TemplateFactory($directories, $engines));

	// Make sure a cache directory is set up
	$upload_dir = wp_upload_dir();
	$cache = $upload_dir['basedir'] . '/cache';
	if( ! is_dir($cache) ) {
		mkdir($cache, 0755, true);
	}

	define('DIVINITY_LOADED', true);
	do_action('divinity_loaded', $factory);
});

