<?php

Route::set('media', 'media/<action>/(<path>/)<file>(.<format>)', array('path' => '.*', 'format' => '\w+'))
	->defaults(array(
		'controller' => 'media',
		'action'     => 'index',
	));