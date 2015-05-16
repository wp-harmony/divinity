<?php
/**
 * Divinity Functions
 *
 * @package    Harmony
 * @subpackage Divinity
 * @author     Simon Holloway <holloway.sy@gmail.com>
 * @license    http://opensource.org/licenses/MIT MIT
 */

if ( ! function_exists('template') )
{
	/**
	 * Build a new template object
	 * 
	 * @param  string   			$request 
	 * @param  Traversable|array	$data
	 * @return Harmony\Divinity\Template
	 */
	function template($request, $data = [])
	{
		return app('divinity.factory')->create_template($request, $data);
	}	
}

if ( ! function_exists('compile_template') )
{
	/**
	 * Compile a template then returns it as a string
	 * 
	 * Pass a path and a dataset to this function and template hooks will 
	 * attempt to interpret the path into a real file path, then the file is 
	 * included and the data array is extracted in to the templates scope.
	 * The contence of the file is then returned
	 * 
	 * @param  string   $request 
	 * @param  array	$data
	 * @return void|string
	 */
	function compile_template($request, array $data = [])
	{
		return app('divinity.factory')->create_template($request, $data)->compile();
	}	
}

if ( ! function_exists('render_template') )
{
	/**
	 * Render a template from the templates directory
	 * 
	 * Pass a path and a dataset to this function and template hooks will 
	 * attempt to interpret the path into a real file path, then the file is 
	 * included and the data array is extracted in to the templates scope.
	 * The contence of the file is then outputted
	 * 
	 * @param  string   $request 
	 * @param  array	$data
	 * @return void|string
	 */
	function render_template($request, array $data = [])
	{
		return app('divinity.factory')->create_template($request, $data)->render();
	}
}

if ( ! function_exists('flatten_attributes') ) 
{
	/**
	 * Flatten an array of attributes into a string that can be placed in a HTML tag
	 * 
	 * @param  array $attributes attributes to be flattened
	 * @return void
	 */
	function flatten_attributes($attributes)
	{
		$attrs = [];
		foreach ($attributes as $key => $value) {
			$value = is_array($value) ? implode(' ', $value) : $value ;
			$attrs[] = $key . '="' . $value . '"';
		}
		return implode(' ', $attrs);
	}
}

