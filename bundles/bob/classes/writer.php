<?php

/**
 * The writer class is used by Generator's to store arrays of filesystem
 * changes that can be written at the same time.
 *
 * @package 	bob
 * @author 		Dayle Rees
 * @copyright 	Dayle Rees 2012
 * @license 	MIT License <http://www.opensource.org/licenses/mit>
 */
class Writer
{
	/**
	 * Store a list of new files to be written.
	 *
	 * @var array
	 */
	private $_files = array();

	/**
	 * Store a list of new strings to be appended to files.
	 *
	 * @var array
	 */
	private $_append = array();

	/**
	 * Store a list of directories to be copied.
	 *
	 * @var array
	 */
	private $_dircopy = array();

	/**
	 * Store a list of files to be copied.
	 *
	 * @var array
	 */
	private $_filecopy = array();

	/**
	 * Add a file to the creation array.
	 *
	 * @param string The type of file to be created.
	 * @param string The friendly name of the file to be created.
	 * @param string The destination for the new file.
	 * @param string The contents of the new file.
	 * @return void
	 */
	public function create_file($type, $name, $location, $contents)
	{
		$new = array(
			'type' => $type,
			'name' => $name,
			'location' => $location,
			'contents' => $contents
		);

		$this->_files[] = $new;
	}

	/**
	 * Add a new string to be appended to a file.
	 *
	 * @param string The destination of the file to be appended.
	 * @param string The string to be appended.
	 * @return void
	 */
	public function append_to_file($existing_file, $contents)
	{
		$new = array(
			'file' => $existing_file,
			'contents' => $contents
		);

		$this->_append[] = $new;
	}

	/**
	 * Add a directory to be copied at write time.
	 *
	 * @param string The type of content to be copied.
	 * @param string The friendly name of the directory to be copied.
	 * @param string The source of the directory.
	 * @param string The destination of the directory.
	 * @return void
	 */
	public function copy_directory($type, $name, $source, $destination)
	{
		$new = array(
			'type' => $type,
			'name' => $name,
			'source' => $source,
			'destination' => $destination
		);

		$this->_dircopy[] = $new;
	}

	/**
	 * Add a file to be copied at write time.
	 *
	 * @param string The type of file to be copied.
	 * @param string The friendly name of the file to be copied.
	 * @param string The source for the file.
	 * @param string The destination for the file.
	 * @return void
	 */
	public function copy_file($type, $name, $source, $destination)
	{
		$new = array(
			'type' => $type,
			'name' => $name,
			'source' => $source,
			'destination' => $destination
		);

		$this->_filecopy[] = $new;
	}

	/**
	 * Write all changes to the filesystem.
	 *
	 * @return void
	 */
	public function write()
	{
		// notifications for switches
		if (Common::config('pretend')) 	Common::log('{c}[ {y}PRETEND MODE ACTIVE {c}]');
		if (Common::config('force')) 	Common::log('{c}[ {y}FORCING OVERWRITE {c}]');

		// can we, can we?
		Common::log('{c}-- Can we build it? --');

		// do all filesystem changes
		$this->_write_files();
		$this->_append_files();
		$this->_copy_dirs();
		$this->_copy_files();

		// hell yes we can
		Common::log('{c}-- Yes we can! --');
	}

	/**
	 * Iterate through a files array, creating files in
	 * different locations where neccessary.
	 *
	 * <code>
	 * $this->_files[] = array(
	 * 		'type' 		=> 'View',
	 *   	'name' 		=> 'Descriptive identifier shown to terminal.',
	 *    	'location' 	=> 'location/to/save/the/file.php',
	 *     	'contents' 	=> 'the string content of the file'
	 * );
	 * </code>
	 *
	 * @return void
	 */
	private function _write_files()
	{
		// for each file in the files array
		foreach($this->_files as $file)
		{
			// force the directory creation if not in pretend mode
			if(! Common::config('pretend')) @mkdir(dirname($file['location']) , 0777, true);

			// check for the --force switch for overwrite
			if(@File::exists($file['location']) && (Common::config('force') == false))
			{
				Common::log("{c}({y}~{c}) {y}{$file['type']}\t\t{w}{$file['name']} (Exists - Skipped)");
				continue;
			}

			// if we have pretend enabled, we don't want to write files
			if(! Common::config('pretend'))
			{
				// try to create the file
				if(@File::put($file['location'], $file['contents']))
				{
					// log something pretty to the terminal
					Common::log("{c}({g}~{c}) {y}{$file['type']}\t\t{w}{$file['name']}");
				}
				else
				{
					// permissions error?
					Common::error('Could not write to location : ' .$file['location']);
				}
			}
			else
			{
				// log something pretty to the terminal for pretend mode
				Common::log("{c}({g}~{c}) {y}{$file['type']}\t\t{w}{$file['name']}");
			}
		}
	}

	private function _append_files()
	{
		foreach($this->_append as $file)
		{
			if (! File::exists($file['file']))  File::append($file['file'], "<?php\n");

			File::append($file['file'], $file['contents']);
		}
	}

	/**
	 * Copy a directory of templates to a destination.
	 *
	 * <code>
	 * $this->_dircopy[] = array(
	 * 		'type' 			=> 'View',
	 *   	'name' 			=> 'Descriptive identifier shown to terminal.',
	 *    	'source' 		=> 'the/location/to/copy/from',
	 *     	'destination' 	=> 'the/location/to/copy/to'
	 * );
	 * </code>
	 *
	 * @return void
	 */
	private function _copy_dirs()
	{
		// loop through dirs to copy
		foreach ($this->_dircopy as $dir)
		{
			// if force is set we overwrite anyway
			if(! is_dir($dir['destination']) and (Common::config('force') == false))
			{
				if(! Common::config('pretend')) File::cpdir($dir['source'], $dir['destination']);

				// log something pretty to the terminal
				Common::log("{c}({g}~{c}) {y}{$dir['type']}\t\t{w}{$dir['name']}");
			}
			else
			{
				// we cant copy if its already there
				Common::error('The directory \''.$dir['name'].'\' already exists.');
			}
		}
	}

	private function _copy_files()
	{

	}


}
