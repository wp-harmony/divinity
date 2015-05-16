<?php
namespace Harmony\Divinity;

use Harmony\Divinity\Contract\TemplateFactory as TemplateFactoryInterface;
use Harmony\Divinity\Contract\TemplateEngine;
use Harmony\Divinity\Template;

/**
 * Builds Divinity Templates
 * 
 * @package Divinity
 * @author  Simon Holloway <holloway.sy@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

class TemplateFactory implements TemplateFactoryInterface
{
	private $directories = [];

	private $engines = [];

	public function __construct(callable $directory_accessor, callable $engine_accessor)
	{
		$this->directories = $directory_accessor;
		$this->engines = $engine_accessor;
	}

	/** 
	 * Build a new template object
	 * 
	 * @param  string   			$path 
	 * @param  Traversable|array	$data
	 * @return Divinity_Template
	 */
	public function create_template($request, $data = [])
	{
		$request = str_replace(':', '/', $request);
		if ($template = $this->itterate_directories($request)) {
			$template->bulk_set($this->parse_data($data));
			return $template;
		} else {
			return null;
		}
	}

	private function parse_data($data)
	{
		if (is_object($data) && $data instanceof Traversable) {
			$data = iterator_to_array($data);
		}

		return $data;
	}

	private function itterate_directories($request)
	{
		foreach (call_user_func($this->directories) as $prefix => $directory) {

			$directory = trailingslashit($directory);

			if (is_string($prefix) && $prefix === substr($request, 0, strlen($prefix))) {
				$path = substr($request, strlen($prefix) + 1);	
			} else {
				$path = $request;
			}

			if($template = $this->itterate_engines($directory, $path)) {
				return $template;
			}
		}
	}

	private function itterate_engines($directory, $path)
	{
		foreach (call_user_func($this->engines) as $engine) {
			foreach ($engine->get_extensions() as $ext) {
				if (file_exists($directory . $path . $ext)) {
					return new Template($directory, $path . $ext, $engine);
				}
			}
		}
	}
}
