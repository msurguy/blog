<?php

/**
 * A simple way of setting up an alias for using
 * the shorter bob syntax.
 *
 * @package 	bob
 * @author 		Dayle Rees
 * @copyright 	Dayle Rees 2012
 * @license 	MIT License <http://www.opensource.org/licenses/mit>
 */
class Generators_Alias extends Generator
{
	/**
	 * Start the generation process.
	 *
	 * @return void
	 */
	public function __construct($args)
	{
		parent::__construct($args);

		Common::log('{g}Next Version! {w}I promise! {y} ;)');
	}
}
