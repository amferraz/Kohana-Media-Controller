<?php

Route::set('media', 'media/<action>/<path>(.<format>)', array('format' => '\w+'))
	->defaults(array(
		'controller' => 'media',
		'action'     => 'index',
	));