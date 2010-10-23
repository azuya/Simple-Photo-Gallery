<?php defined('SYSPATH') or die('No direct script access.');
// TODO: check if these can be modified, and everything still works
Route::set('sigal-backend', 'backend/gallery(/<controller>(/<action>(/<id>)))', array('controller' => 'photo|album'))
	->defaults(array(
		'directory'	=> 'backend',
		'controller' => 'album',
		'action' => 'index'));

Route::set('sigal-frontend', 'gallery(/<contoller>(/<action>(/<id>)))', array('controller' => 'photo|album'))
	->defaults(array(
		'directory'	=> 'frontend',
		'controller' => 'album',
		'action' => 'index'));
