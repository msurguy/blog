<?php

/**
 * Generate a Task class, and individual methods.
 *
 * @package 	bob
 * @author 		Dayle Rees
 * @copyright 	Dayle Rees 2012
 * @license 	MIT License <http://www.opensource.org/licenses/mit>
 */
class Generators_Task extends Generator
{
	/**
	 * Start the generation process.
	 *
	 * @return void
	 */
	public function __construct($args)
	{
		parent::__construct($args);

		// we need a task name
		if ($this->class == null)
			Common::error('You must specify a task name.');

		// set switches
		$this->_settings();

		// start the generation
		$this->_task_generation();

		// write filesystem changes
		$this->writer->write();
	}

	/**
	 * This method is responsible for generation all
	 * source from the templates, and populating the
	 * files array.
	 *
	 * @return void
	 */
	private function _task_generation()
	{
		$prefix = ($this->bundle == DEFAULT_BUNDLE) ? '' : Str::classify($this->bundle).'_';
		$view_prefix = ($this->bundle == DEFAULT_BUNDLE) ? '' : $this->bundle.'::';

		// set up the markers for replacement within source
		$markers = array(
			'#CLASS#'		=> $prefix.$this->class_prefix.$this->class,
			'#LOWER#'		=> $this->lower,
			'#LOWERFULL#'	=> $view_prefix.Str::lower(str_replace('/','.', $this->class_path).$this->lower)
		);

		// loud our task template
		$template = Common::load_template('task/task.tpl');

		// holder for methods source, and base template for methods
		$methods_source 	= '';
		$method_template 	= Common::load_template('task/method.tpl');

		// loop through our methods
		foreach ($this->arguments as $method)
		{
			// add the current method to the markers
			$markers['#METHOD#'] = Str::lower($method);

			// append the replaces source
			$methods_source .= Common::replace_markers($markers, $method_template);
		}

		// add a marker to replace the methods stub in the task
		// template
		$markers['#METHODS#'] = $methods_source;

		// added the file to be created
		$this->writer->create_file(
			'Task',
			$markers['#CLASS#'].'_Task',
			$this->bundle_path.'tasks/'.$this->class_path.$this->lower.EXT,
			Common::replace_markers($markers, $template)
		);
	}

	/**
	 * Alter generation settings from artisan
	 * switches.
	 *
	 * @return void
	 */
	private function _settings()
	{

	}
}
