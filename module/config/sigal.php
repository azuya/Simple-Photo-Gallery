<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'columns' => 3,
	'items_per_page' => 9,
	'image' => array(
		'width' => 640,
		'height' => 480,
	),
	'thumbnail' => array(
		'width' => 150,
		'height' => 150,
	),
	'path' => array(
		'galleries' => 'media/gallery/', // Make sure this ends with a slash
	),
	'table' => array(
		'galleries' => 'sigal_galleries',
		'images'	=> 'sigal_images'
	),
	'driver' => array(
		'model'	=> 'ORM', // Automodeler, Jelly,...
		'auth'	=> 'Auth' // Auth, ACL,...
	)
);