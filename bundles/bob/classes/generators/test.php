<?php

/**
 * Generate a new controller class, including actions
 * and views for each action.
 *
 * @package 	bob
 * @author 		Dayle Rees
 * @copyright 	Dayle Rees 2012
 * @license 	MIT License <http://www.opensource.org/licenses/mit>
 */
class Generators_Test extends Generator
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
		if ($this->standard == null)
			Common::error('You must specify a task name.');

		// load any command line switches
		$this->_settings();

		// start the generation
		$this->_test_generation();

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
	private function _test_generation()
	{

		// set up the markers for replacement within source
		$markers = array(
			'#CLASS#'		=> 'Test'.$this->standard
		);

		// loud our test case template
		$template = Common::load_template('test/test_case.tpl');

		// holder for tasks source, and base templates for tasks
		$tasks_source 	= '';
		$task_template 	= Common::load_template('test/test.tpl');

		// loop through our tests
		foreach ($this->arguments as $test)
		{
			// add the current task to the markers
			$markers['#NAME#'] = $test;

			// append the replaced source
			$tasks_source .= Common::replace_markers($markers, $task_template);
		}

		// add a marker to replace the test stub in the test case
		// template
		$markers['#TESTS#'] = $tasks_source;

		// add the generated file to the writer
		$this->writer->create_file(
			'Test',
			'Test'.$this->standard,
			$this->bundle_path.'tests/'.$this->class_path.$this->lower.'.test'.EXT,
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
