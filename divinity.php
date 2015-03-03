<?php
/**
 * Divinity - Templating Libary
 *
 * Part of the Harmony Group
 *
 * A template loader module that loads a template from dynamic locations
 * (usually the themes TEMPLATES_PATH) and sets up variables to be 
 * include in the templates scope. Using the render_template action the path 
 * and variables (data) can be changed/overridden. 
 *
 * @package Divinity
 * @subpackage Harmony
 * @author  Simon Holloway <holloway.sy@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @version 2.0.0
 */

if ( ! defined('DIVINITY_AUTOLOADED') ) {
	require('autoloader.php');
}

require('helpers.php');

/**
 * Add the divinity oop structure to the psr-0 class loader and register engines
 * 
 * @return void
 */
function divinity_init()
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

	$directories = array(get_template_path());

	divinity_factory(new Harmony\Divinity\TemplateFactory($directories, $engines));

	// Make sure a cache directory is set up
	$upload_dir = wp_upload_dir();
	$cache = $upload_dir['basedir'] . '/cache';
	if( ! is_dir($cache) ) {
		mkdir($cache, 0755, true);
	}

	do_action('divinity_loaded', $factory);
}

divinity_init();
