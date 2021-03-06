<?php
namespace Harmony\Divinity;

use Harmony\Divinity\Contract\TemplateEngine;
use Harmony\Mana\Map;


/**
 * Class wrapper for the render and compile template functions
 * 
 * @package Divinity
 * @author  Simon Holloway <holloway.sy@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

class Template extends Map
{	
	/**
	 * Template directory where a collection of templates are stored
	 * 
	 * @var boolean|string
	 */
	public $template_directory = false;
	
	/**
	 * Template location within the template directory
	 * 
	 * @var boolean|string
	 */
	public $template = false;
	
	/**
	 * Template engine for the template
	 * 
	 * @var TemplateEngine
	 */
	protected $engine;

	/**
	 * Setup the template with required properties
	 *
	 * @param string          $template_directory
	 * @param string          $template
	 * @param TemplateEngine  $engine
	 * @param array           $data
	 */
	public function __construct($template_directory, $template, TemplateEngine $engine, array $data = [])
	{
		$this->template_directory = $template_directory;
		$this->template = $template;
		$this->data = $data;
		$this->engine = $engine;
	}

	/**
	 * Set the template engine
	 * 
	 * @param TemplateEngine $template
	 * @return self
	 */
	public function set_engine(TemplateEngine $engine)
	{
		$this->engine = $engine;
		return $this;
	}

	/**
	 * Get the template engine
	 * 
	 * @return string
	 */
	public function get_engine()
	{
		return $this->engine;
	}

	/**
	 * Set the template data from an array
	 * 
	 * @param string $data data to set 
	 * @return self
	 */
	public function bulk_set(array $data)
	{
		$data = array_dot($data);
		foreach ($data as $index => $item) {
			$this->set($index, $item);
		}
		return $this;
	}

	/**
	 * Render the template markup
	 * 
	 * @return void
	 */
	public function render()
	{
		$this->notify();

		return $this->engine->render(
			$this->template_directory, 
			$this->template, 
			$this->data
		);
	}

	/**
	 * Compile and return the template markup
	 * 
	 * @return string
	 */
	public function compile()
	{
		$this->notify();
		
		return $this->engine->compile(
			$this->template_directory, 
			$this->template, 
			$this->data
		);	
	}

	/**
	 * Compile this template and return as a string
	 * 
	 * @param string|interger $index
	 */
	public function __toString()
	{
		return $this->compile();
	}

	protected function notify()
	{
		list($template) = explode('.', $this->template);
		do_action('divinity_bind', $this);
		do_action('divinity_bind_' . $template, $this);
	}

	public function apply_defaults($defaults)
	{
		foreach ($defaults as $key => $value) {
			if ( ! isset($this[$key]) ) {
				$this[$key] = $value;
			}
		}
	}
}
