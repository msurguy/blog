<?php

/**
 * Generate a Config file with options.
 *
 * @package 	bob
 * @author 		Dayle Rees
 * @copyright 	Dayle Rees 2012
 * @license 	MIT License <http://www.opensource.org/licenses/mit>
 */
class Generators_Config extends Generator
{
	/**
	 * Start the generation process.
	 *
	 * @return void
	 */
	public function __construct($args)
	{
		parent::__construct($args);

		// we need a config name
		if ($this->class == null)
			Common::error('You must specify a config name.');

		// set switches
		$this->_settings();

		// start the generation
		$this->_config_generation();

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
	private function _config_generation()
	{
		$prefix = ($this->bundle == DEFAULT_BUNDLE) ? '' : Str::classify($this->bundle).'_';
		$view_prefix = ($this->bundle == DEFAULT_BUNDLE) ? '' : $this->bundle.'::';

		// loud our config template
		$template = Common::load_template('config/config.tpl');

		// holder for options source, and base template for options
		$options_source 	= '';
		$option_template 	= Common::load_template('config/option.tpl');

		// loop through our options
		foreach ($this->arguments as $option)
		{
			// add the current option to the markers
			$markers['#OPTION#'] = Str::lower($option);

			// append the replaces source
			$options_source .= Common::replace_markers($markers, $option_template);
		}

		// add a marker to replace the options stub in the config
		// template
		$markers['#OPTIONS#'] = $options_source;

		// added the file to be created
		$this->writer->create_file(
			'Config',
			$this->lower.EXT,
			$this->bundle_path.'config/'.$this->class_path.$this->lower.EXT,
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
