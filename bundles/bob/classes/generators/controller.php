<?php

/**
 * Generate a controller, its actions and associated views.
 *
 * @package 	bob
 * @author 		Dayle Rees
 * @copyright 	Dayle Rees 2012
 * @license 	MIT License <http://www.opensource.org/licenses/mit>
 */
class Generators_Controller extends Generator
{
	/**
	 * The view file extension, can also be blade.php
	 *
	 * @var string
	 */
	private $_view_extension = EXT;

	/**
	 * Start the generation process.
	 *
	 * @return void
	 */
	public function __construct($args)
	{
		parent::__construct($args);

		// we need a controller name
		if ($this->class == null)
			Common::error('You must specify a controller name.');

		// set switches
		$this->_settings();

		// start the generation
		$this->_controller_generation();

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
	private function _controller_generation()
	{
		$prefix = ($this->bundle == DEFAULT_BUNDLE) ? '' : Str::classify($this->bundle).'_';
		$view_prefix = ($this->bundle == DEFAULT_BUNDLE) ? '' : $this->bundle.'::';

		// set up the markers for replacement within source
		$markers = array(
			'#CLASS#'		=> $prefix.$this->class_prefix.$this->class,
			'#LOWER#'		=> $this->lower,
			'#LOWERFULL#'	=> $view_prefix.Str::lower(str_replace('/','.', $this->class_path).$this->lower)
		);

		// loud our controller template
		$template = Common::load_template('controller/controller.tpl');

		// holder for actions source, and base templates for actions and views
		$actions_source 	= '';
		$action_template 	= Common::load_template('controller/action.tpl');
		$view_template 		= Common::load_template('controller/view.tpl');

		$restful = (strstr(implode(' ', $this->arguments), ':')) ? true : false;

		array_unshift($this->arguments, 'index');

		// loop through our actions
		foreach ($this->arguments as $action)
		{
			$verb = ($restful) ? 'get' :'action';

			if(strstr($action, ':'))
			{
				$parts = explode(':', $action);

				if (count($parts) == 2)
				{
					$verb = Str::lower($parts[0]);
					$action = Str::lower($parts[1]);
				}
			}

			// add the current action to the markers
			$markers['#ACTION#'] = Str::lower($action);
			$markers['#VERB#'] = $verb;

			// append the replaces source
			$actions_source .= Common::replace_markers($markers, $action_template);

			$file_prefix = ($restful) ? $verb.'_' :'';


			// add the file to be created
			$this->writer->create_file(
				'View',
				$this->class_path.$this->lower.'/'.$file_prefix.Str::lower($action).$this->_view_extension,
				$this->bundle_path.'views/'.$this->class_path.$this->lower.'/'.$file_prefix.Str::lower($action).$this->_view_extension,
				Common::replace_markers($markers, $view_template)
			);
		}

		// add a marker to replace the actions stub in the controller
		// template
		$markers['#ACTIONS#'] = $actions_source;
		$markers['#RESTFUL#'] = ($restful) ? "\n\tpublic \$restful = true;\n" : '';

		// added the file to be created
		$this->writer->create_file(
			'Controller',
			$markers['#CLASS#'].'_Controller',
			$this->bundle_path.'controllers/'.$this->class_path.$this->lower.EXT,
			Common::replace_markers($markers, $template)
		);

		$this->writer->append_to_file(
			$this->bundle_path.'routes.php',
			"\n\n// Route for {$markers['#CLASS#']}_Controller\nRoute::controller('{$markers['#LOWERFULL#']}');"
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
		if(Common::config('blade')) $this->_view_extension = BLADE_EXT;
	}
}
