<?php defined('SYSPATH') or die('No direct script access.');
/*
 * The uri's can be modified, just make sure the name & attributes stay the same.
 * 
 * Ex: To use the uri http://yoursite.com/gallery/ for sigal, you can change the frontend route to
 * Route::set('sigal-frontend', 'gallery(/<action>(/<id>))')->default(array('dir....
 */

Route::set('sigal-backend', 'backend/sigal(/<controller>(/<action>(/<id>)))', array('controller' => 'image|gallery'))
	->defaults(array(
		'directory'	=> 'backend',
		'controller' => 'gallery',
		'action' => 'index'));

Route::set('sigal-frontend', 'sigal(/<action>(/<id>))')
	->defaults(array(
		'directory'	=> 'frontend',
		'controller' => 'gallery',
		'action' => 'index'));