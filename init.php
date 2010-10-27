<?php defined('SYSPATH') or die('No direct script access.');
// TODO: check if these can be modified, and everything still works
Route::set('sigal-backend', 'backend/gallery(/<controller>(/<action>(/<id>)))', array('controller' => 'photo|gallery'))
	->defaults(array(
		'directory'	=> 'backend',
		'controller' => 'gallery',
		'action' => 'index'));

Route::set('sigal-frontend', 'gallery(/<contoller>(/<action>(/<id>)))', array('controller' => 'photo|gallery'))
	->defaults(array(
		'directory'	=> 'frontend',
		'controller' => 'gallery',
		'action' => 'index'));
