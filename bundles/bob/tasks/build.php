<?php

/**
 * The main task for the Bob generator, commands are passed as
 * arguments to run()
 *
 * @package 	bob
 * @author 		Dayle Rees
 * @copyright 	Dayle Rees 2012
 * @license 	MIT License <http://www.opensource.org/licenses/mit>
 */
class Bob_Build_Task extends Task
{
	/**
	 * run() is the start-point of the CLI request, the
	 * first argument specifies the command, and sub-sequent
	 * arguments are passed as arguments to the chosen generator.
	 *
	 * @param $arguments array The command and its arguments.
	 * @return void
	 */
	public function run($arguments = array())
	{
		if (! count($arguments)) $this->_help();

		// setup ansi support
		Common::detect_windows();

		// assign the params
		$command = ($arguments[0] !== '') ? $arguments[0] : 'help';
		$args = array_slice($arguments, 1);

		switch($command)
		{
			case "controller":
			case "c":
				new Generators_Controller($args);
				break;
			case "model":
			case "m":
				new Generators_Model($args);
				break;
			case "alias":
				new Generators_Alias($args);
				break;
			case "migration":
			case "mig":
				IoC::resolve('task: migrate')->make($args);
				break;
			case "bundle":
			case "b":
				new Generators_Bundle($args);
				break;
			case "test":
			case "t":
				new Generators_Test($args);
				break;
			case "task":
			case "ta":
				new Generators_Task($args);
				break;
			case "class":
			case "cl":
				new Generators_Class($args);
				break;
			case "install":
			case "i":
				IoC::resolve('task: bundle')->install($args);
				break;
			case "config":
			case "co":
				new Generators_Config($args);
				break;
			case "view":
			case "v":
				new Generators_View($args);
				break;
			default:
				$this->_help();
				break;
		}
	}

	/**
	 * Show a short version of the documentation to hint
	 * at command names, with an example.
	 *
	 * @return void
	 */
	private function _help()
	{
		Common::log('{w}Usage :');
		Common::log("\t{w}bob {c}<command> {g}[args] {y}[options ..]\n");
		Common::log('{w}Commands :');
		Common::log("\t{c}(c)      controller");
		Common::log("\t{c}(m)      model");
		Common::log("\t{c}(v)      view");		
		Common::log("\t{c}(t)      test");
		Common::log("\t{c}(ta)     task");
		Common::log("\t{c}(mig)    migration");
		Common::log("\t{c}(i)      install");
		Common::log("\t{c}(b)      bundle");
		Common::log("\t{c}(co)     config");
		Common::log("\t{c}(cl)     class");		
		Common::log("\t{c}(a)      alias");
		Common::log("\n\n{w}Arguments :");
		Common::log("\t{g}--force\n\t{w}Force overwrite of existing files and folders.");
		Common::log("\t{g}--pretend\n\t{w}Show the result of a generation without writing to the filesystem.");																						
		exit();
	}
}
