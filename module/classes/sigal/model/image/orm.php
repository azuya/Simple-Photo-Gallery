<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The ORM Image Model.
 *
 * @package    Sigal
 * @category   Models
 * @author     Peter Briers
 * @uses       ORM
 * @license    ???
 */
class Sigal_Model_Image_ORM extends ORM implements Sigal_Interface_Image {

	// (!) Validation is handled in class 'Sigal' (validate_image)

	protected $_belongs_to = array(
		'gallery' => array(
			'model' => 'gallery_orm',
	));

	protected $_table_name = 'sigal_images'; // (!) See __construct()

	protected $_table_columns = array(
		'id' => array(),
		'name' => array(),
		'description' => array(),
		'filename' => array(),
		'order' => array(),
		'gallery_id' => array(),
	);

	public function __construct($id = NULL)
	{
		$this->_table_name = Kohana::config('sigal.table.images');
		parent::__construct();
	}

	public function read_by_id($id)
	{
		return $this->find($id);
	}

	public function count_all()
	{
		return $this->count_all();
	}

	public function delete($id = NULL)
	{
		Sigal::delete_file($this);
		return parent::delete($id);	
	}

	public function update_fields($values)
	{
		$this->values($values);
		if(empty($this->filename))
			$this->filename = $values['file']['name']; // (!) This must be set in order to ...
		$this->order = Sigal::set_order($this);
	}

	public function upload($path)
	{
		Sigal::process_upload($path, $this);
	}

	public function save()
	{
		parent::save();
	}
}