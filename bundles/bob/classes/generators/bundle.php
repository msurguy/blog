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
class Generators_Bundle extends Generator
{
	/**
	 * Start the generation process.
	 *
	 * @return void
	 */
	public function __construct($args)
	{
		parent::__construct($args);

		// we need a controller name
		if ($this->lower == null)
			Common::error('You must specify a bundle name.');

		// add the directory copy to the writer
		$this->writer->copy_directory(
			'Bundle',
			$this->lower,
			Bundle::path('bob').'templates/bundle', /** TODO add override path */
			path('bundle').$this->lower
		);

		$this->writer->write();
	}
}
