<?php
namespace Harmony\Divinity\Contract;

/**
 * Divinity Template Factory
 *
 * @package Divinity
 * @author  Simon Holloway <holloway.sy@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

interface TemplateFactory
{
	/** 
	 * Build a new template object
	 * 
	 * @param  string   			$path 
	 * @param  Traversable|array	$data
	 * @return Harmony\Divinity\Template
	 */
	public function create_template($request, $data = []);
}