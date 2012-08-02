<?php

/**
 * Common is a utility class for the bob
 * generation system.
 *
 * @package 	bob
 * @author 		Dayle Rees
 * @copyright 	Dayle Rees 2012
 * @license 	MIT License <http://www.opensource.org/licenses/mit>
 */
class Common
{
	/**
	 * Use ANSI coloring for output.
	 *
	 * @var mixed
	 */
	private static $_ansi = true;

	/**
	 * Log a message to the CLI with a \r\n
	 *
	 * @param $message string The message to display.
	 * @return void
	 */
	public static function log($message, $echo = true)
	{
		// swap colors for codes, will add a windows conditional later
		$colors = array(
			'{r}' 	=> chr(27).'[31m',
			'{c}' 	=> chr(27).'[36m',
			'{y}' 	=> chr(27).'[33m',
			'{g}' 	=> chr(27).'[32m',
			'{w}' 	=> chr(27).'[37m'
		);

		if(static::$_ansi)
		{
			foreach($colors as $key => $color)
			{
				$message = str_replace($key, $color, $message);
			}
		}
		else
		{
			foreach($colors as $key => $color)
			{
				$message = str_replace($key, '', $message);
			}
		}



		if ($echo == true)
		{
			echo $message . PHP_EOL;
		}
		else
		{
			return $message . PHP_EOL;
		}
	}

	/**
	 * Show an error message to the CLI in the form
	 * of an exception.
	 */
	public static function error($message)
	{
		throw new \Exception(static::log('{r}'.$message.PHP_EOL.'{c}-- Apparently not! :( --', false));
	}

	/**
	 * Load the source from a template file and return it
	 * as a string.
	 *
	 * @param $template_name string The file name of the template.
	 * @return string The template content.
	 */
	public static function load_template($template_name)
	{
		// first look in the project templates this way
		// the user can have project-specific templating
		if(File::exists($source = Config::get('bob::options.project_templates').$template_name))
		{
			return File::get($source);
		}
		elseif(File::exists($source = Config::get('bob::options.template_path').$template_name))
		{
			return File::get($source);
		}
		else
		{
			static::error('A generation template could not be found for this object.');
		}
	}

	/**
	 * Use a key-value array to replace markers within
	 * a source template with their appropriate value.
	 *
	 * @param $markers array Markers to value array.
	 * @param $template string The source containing markers.
	 * @return string The processed template with values inserted.
	 */
	public static function replace_markers($markers, $template)
	{
		// the array key hold the marker (#NAME#) which
		// is globaly replaced with the array value
		foreach ($markers as $marker => $value)
		{
			$template = str_replace($marker, $value, $template);
		}

		return $template;
	}

	/**
	 * Retrieve a command line switch, as false of not set,
	 * true if set with no value, and string if given a value.
	 *
	 * @param string The switch name to query, lowercase.
	 * @return mixed Bool or value.
	 */
	public static function config($key)
	{
		if(isset($_SERVER['CLI'][Str::upper($key)]))
		{
			return ($_SERVER['CLI'][Str::upper($key)] == '') ? true : $_SERVER['CLI'][Str::upper($key)];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Set the appropriate ANSI setting, disabled for windows.
	 *
	 * @return void
	 */
	public static function detect_windows()
	{
		static::$_ansi = Config::get('bob::options.ansi_support');

		if(static::$_ansi === 'auto')
		{
			static::$_ansi = true;
			if(defined(PHP_OS) and strstr(PHP_OS, 'WINNT')) static::$_ansi = false;
			if(strstr(php_uname(), 'Windows')) static::$_ansi = false;
		}
	}
}
