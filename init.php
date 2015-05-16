<?php
/**
 * Divinity - Templating Library
 *
 * Part of the Harmony Group 
 *
 * Plugin Name: Harmony Divinity
 * Depends: Harmony Mana
 * 
 * @package    Harmony
 * @subpackage Divinity
 * @author     Simon Holloway <holloway.sy@gmail.com>
 * @license    http://opensource.org/licenses/MIT MIT
 * @version    2.0.0
 */

use Harmony\Divinity\Engine\PhpEngine;
use Harmony\Divinity\Engine\MustacheEngine;
use Harmony\Divinity\Engine\TwigEngine;
use Harmony\Divinity\Engine\BladeEngine;
use Harmony\Divinity\TemplateFactory;
use Harmony\Mana\Invokable;

/**
 * Register Divinity
 * 
 * @return void
 */
function divinity_register($app)
{
	$app['autoloader']['Harmony\\Divinity'] = dirname(__FILE__) . '/src';
	require('functions.php');
}

add_action('harmony_register', 'divinity_register');

/**
 * Boot Divinity
 * 
 * @param $app
 */
function divinity_boot($app)
{
	$app['divinity.factory'] = $factory = new TemplateFactory(
		new Invokable('get_divinity_locations'), 
		new Invokable('get_divinity_engines')
	);

	confirm_divinity_cache();

	do_action('divinity_loaded', $factory);
}

add_action('harmony_boot', 'divinity_boot');

/**
 * Make sure that a cache directory is available for compiled templates
 */
function confirm_divinity_cache()
{
	// Make sure a cache directory is set up
	$upload_dir = wp_upload_dir();
	$cache = $upload_dir['basedir'] . '/cache';
	if( ! is_dir($cache) ) {
		mkdir($cache, 0775, true);
	}
}

/**
 * Get the default divinity engines
 * 
 * @return array
 */
function get_divinity_engines()
{
	$engines = app()->get('divnity.defaults.engines', []);
	
	if (empty($engines)) {

		$engines[] = new PhpEngine;

		if (class_exists('Mustache_Template')) {
			$engines[] = new MustacheEngine;
		}
		if (class_exists('Twig_Template')) {
			$engines[] = new TwigEngine;
		}
		if (class_exists('Illuminate\View\Compilers\BladeCompiler')) {
			$engines[] = new BladeEngine;
		}
	}

	app('divnity.defaults.engines', $engines);

	return apply_filters('divnity_engines', $engines);
}

/**
 * Get the default divinity locations
 * 
 * @return array
 */
function get_divinity_locations()
{
	$locations = [get_template_directory() . '/templates'];

	return apply_filters('divnity_locations', $locations);
}
