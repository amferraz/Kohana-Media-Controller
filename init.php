<?php

Route::set('media', 'media/<action>/<path>(.<format>)', array('path' => '.*', 'format' => '\w+'))
	->defaults(array(
		'controller' => 'media',
		'action'     => 'index',
	));