<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The ORM Gallery Model.
 *
 * @package    Sigal
 * @category   Models
 * @author     Peter Briers
 * @uses       ORM
 * @license    ???
 */
class Sigal_Model_Gallery_ORM extends ORM implements Sigal_Interface_Gallery {

	// (!) Validation is handled in class 'Sigal' 

	protected $_has_many = array(
		'images' => array(
			'model' => 'image_orm',
			'foreign_key' => 'gallery_id'
	));

	protected $_table_name = 'sigal_galleries'; // (!) See __construct()

	protected $_table_columns = array(
		"id" => array("type" => "int"),
		"name" => array("type" => "string"),
		"slug" => array("type" => "string"),
		"order" => array("type" => "int")
	);

	public function __construct($id = NULL)
	{
		$this->_table_name = Kohana::config('sigal.table.galleries');
		parent::__construct();
	}

	public function read_all()
	{
		return $this->order_by('order', 'desc')->find_all();
	}

	public function read_by_id($id)
	{
		return $this->find($id);
	}

	public function read_by_slug($slug)
	{
		return $this->where('slug', '=', $slug)->find();
	}

	public function read_images($page = NULL)
	{
		if($page == NULL)
			return $this->images->find_all();
		else
			return $this->images->offset($page)->find_all();
	}

	public function thumbnail()
	{
		return $this->images->find();
	}


	public function update_fields($values)
	{
		$this->values($values);
		$this->slug = Sigal::set_slug($this); // (!) This also creates the gallery folder
		$this->order = Sigal::set_order($this);
	}

	public function save()
	{
		parent::save();
	}

	// Delete also deletes the related images
	public function delete($id = NULL)
	{
		//$this->images->delete_all(); //does not delete the file?
		Sigal::delete_folder($this->slug);
		return parent::delete();
	}
}