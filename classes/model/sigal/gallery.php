<?php defined('SYSPATH') or die('No direct script access.');

class Model_Sigal_Gallery extends ORM {

	protected $_has_many = array('photos' => array());

	protected $_table_columns = array(
		"id" => array("type" => "int"),
		"name" => array("type" => "string"),
		"slug" => array("type" => "string"),
		"order" => array("type" => "int")
	);

	protected $_rules = array(
		'name' => array(
			'not_empty' => NULL
		),
		'slug' => array(
			'alpha_dash' => NULL
		),
		'order' => array(
			'not_empty' => NULL,
			'numeric' => NULL
		)
	);

	protected $_filters = array(
		TRUE => array(
			'trim'	=> NULL
		)
	);

	// TODO: Add some function that converts the slug to an alpha_dash thingie
	// this can be done by resetting the __set function (or a callback?)


	public function find_photos($page_number)
	{
		$per_page = Kohana::config('gallery.photos_per_page');
		return $this->photos->limit($per_page)->offset($page_number*$per_page-$per_page)->find_all();
	}

	public function find_related($model)
	{
		return $this->photos->find_all();
	}

	public function find_thumbnail()
	{
		$photos = $this->photos;
		return $photos->order_by('order')->limit(1)->find();
	}

}