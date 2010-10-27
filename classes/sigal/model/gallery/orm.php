<?php defined('SYSPATH') or die('No direct script access.');

class Sigal_Model_Gallery_ORM extends ORM implements Sigal_Gallery {

	protected $_has_many = array(
		'images' => array(
			'model' => 'image_orm',
			'foreign_key' => 'gallery_id'
	));

	protected $_table_name = 'galleries';

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

	public function read_all()
	{
		return $this->find_all();
	}

	public function read_images()
	{
		return $this->images->find_all();
	}

	public function thumbnail()
	{
		return $this->images->find();
	}

	public function find_by_slug($slug)
	{
		return $this->where('slug', '=', $slug)->find();
	}

	public function update_fields($values) {
		$this->values($values);
	}

	public function save() {
		//$this->slug = URL::title($this->name, '-', TRUE);
	}




	public function find_images($page_number)
	{
		$per_page = Kohana::config('gallery.items_per_page');
		return $this->images->limit($per_page)->offset($page_number*$per_page-$per_page)->find_all();
	}


}