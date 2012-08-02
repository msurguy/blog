<?php

/**
 * Generate a view.
 *
 * @package 	bob
 * @author 		Dayle Rees
 * @copyright 	Dayle Rees 2012
 * @license 	MIT License <http://www.opensource.org/licenses/mit>
 */
class Generators_View extends Generator
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

		// we need a view name
		if ($this->class == null)
			Common::error('You must specify a view name.');

		// set switches
		$this->_settings();

		// start the generation
		$this->_view_generation();

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
	private function _view_generation()
	{
		$prefix = ($this->bundle == DEFAULT_BUNDLE) ? '' : Str::classify($this->bundle).'_';
		$view_prefix = ($this->bundle == DEFAULT_BUNDLE) ? '' : $this->bundle.'::';

		// set up the markers for replacement within source
		$markers = array(
			'#LOWERFULL#'	=> $view_prefix.Str::lower(str_replace('/','.', $this->class_path).$this->lower)
		);

		// loud our view template
		$template = Common::load_template('view/view.tpl');

		// added the file to be created
		$this->writer->create_file(
			'View',
			$this->class_path.$this->lower.$this->_view_extension,
			$this->bundle_path.'views/'.$this->class_path.$this->lower.$this->_view_extension,
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
		if(Common::config('blade')) $this->_view_extension = BLADE_EXT;
	}
}
