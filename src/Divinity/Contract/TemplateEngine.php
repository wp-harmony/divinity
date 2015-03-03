<?php
namespace Harmony\Divinity\Contract;

/**
 * Divinity Template Engine
 *
 * @package Divinity
 * @author  Simon Holloway <holloway.sy@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

interface TemplateEngine
{
	/**
	 * Compile and output the template
	 * 
	 * @param string $directory
	 * @param string $path
	 * @param array  $data
	 * @return boolean
	 */
	public function render($template_dir, $template, $data);
	
	/**
	 * Compile and return the template
	 * 
	 * @param string $directory
	 * @param string $path
	 * @param array  $data
	 * @return string
	 */
	public function compile($template_dir, $template, $data);

	/**
	 * Return the file extensions this engine supports, with the leading dot
	 * 
	 * @return array
	 */
	public function get_extensions();
}