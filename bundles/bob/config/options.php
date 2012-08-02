<?php

return array(

	// the path to the default templates folder
	'template_path' 		=> Bundle::path('bob').'templates/',

	// the path to the project templates folder, templates
	// within this directory are loaded before the template_path
	'project_templates' 	=> path('app').'bob/',

	// use ansi support for terminal colors, options are :
	// 'auto' - detect support by OS
	// true - use ansi
	// false - disable ansi
	'ansi_support'			=> 'auto',

);
