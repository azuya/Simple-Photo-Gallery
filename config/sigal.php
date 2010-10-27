<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'columns' => 3,
	'items_per_page' => 9,
	'image_width' => 640,
	'image_height' => 480,
	'thumbnail_width' => 150,
	'thumbnail_height' => 150,
	'paths' => array(
		'galleries' => 'media/gallery/',
	),
	'driver' => array(
		'model'	=> 'ORM', // Automodeler, Jelly,...
		'auth'	=> 'Auth' // Auth, ACL,...
	)
);