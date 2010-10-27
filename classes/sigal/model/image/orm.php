<?php defined('SYSPATH') or die('No direct script access.');

class Sigal_Model_Image_ORM extends ORM implements Sigal_Image {

	protected $_belongs_to = array(
		'gallery' => array(
			'model' => 'gallery_orm',
	));

	protected $_table_name = 'images';

	protected $_table_columns = array(
		'id' => array(),
		'name' => array(),
		'description' => array(),
		'filename' => array(),
		'order' => array(),
		'gallery_id' => array(),
	);

	protected $_rules = array(
		'name' => array('required'),
		'filename' => array('required'), // needs to be a filename!
		'album_id' => array('required', 'numeric')
	);

	protected $_callbacks = array(
			'filename' => 'valid_filename',
			'filename' => 'upload'

	);

	public function count_all()
	{
		return $this->count_all();
	}

	protected function delete_file()
	{
		return unlink(APPPATH.'views/media/photos/'.$this->album->url_name.'/'.$this->photo_filename)
		       AND unlink(APPPATH.'views/media/photos/'.$this->album->url_name.'/thumb_'.$this->photo_filename);
	}

	public function delete($id = NULL)
	{
		if ($this->delete_file())
			return parent::delete($id);
		return FALSE;
	}

	public function replace_order($photo, $direction)
	{
		if ($direction === 'up') // This is used for insertion
		{
			db::query('UPDATE `'.$this->table_name.'` SET `photo_order` = `photo_order` + 1 WHERE `photo_order` >= :photo_order AND `album_id` = :album_id')->value(':photo_order', $photo->photo_order)->value(':album_id', $photo->album_id)->execute($this->db);
			//$this->db->query('UPDATE `'.$this->table_name.'` SET `photo_order` = `photo_order` + 1 WHERE `photo_order` >= ? AND `album_id` = ?', array($photo->photo_order, $photo->album_id));
		}
		elseif ($direction === 'down') // This is used for deleting
		{
			db::query('UPDATE `'.$this->table_name.'` SET `photo_order` = `photo_order` - 1 WHERE `photo_order` >= :photo_order AND `album_id` = :album_id')->value(':photo_order', $photo->photo_order)->value(':album_id', $photo->album_id)->execute($this->db);
			//$this->db->query('UPDATE `'.$this->table_name.'` SET `photo_order` = `photo_order` - 1 WHERE `photo_order` >= ? AND `album_id` = ?', array($photo->photo_order, $photo->album_id));
		}
		//die(Kohana::debug($this->db->last_query()));
	}

	public function batch_reorder($photo_array)
	{
		foreach ($photo_array as $order => $photo_id)
		{
			db::build()->update($this->table_name, array('photo_order' => $order+1), array('id', '=', $photo_id))->execute($this->db);
		}
	}

	public function valid_filename(Validation $array, $field)
	{
		// This kinda sucks
		if ( $_FILES AND ! $_FILES['photo']['error'])
		{
			$filename_exists = (bool) db::build()->from($this->table_name)->select('photo_filename')->where(array(array('album_id', '=', $this->album_id), array('photo_filename', '=', $this->photo_filename)))->execute($this->db)->count();

			if ($filename_exists)
				$array->add_error($field, 'filename_exists');
		}
	}


	/*
	 * Upload photo
	 */

	public static function process_upload(Validation $array, $field)
	{
		$path = Kohana::config('gallery.path.photos');
		Upload::not_empty($file);

		! mkdir(APPPATH.$path.$album->slug);

		// Create a thumbnail and resized version
		$image = new Image($file['tmp_name']);

		$image->resize(Kohana::config('photo_gallery.image_width'), Kohana::config('photo_gallery.image_height'), Image::AUTO);
		$resized_status = $image->save(APPPATH.'views/media/photos/'.$album->url_name.'/'.$file['name']);

		$ratio = $image->width / $image->height;
		if ($ratio > 1)
		{
			$image->resize(Kohana::config('photo_gallery.thumbnail_width')*$ratio, Kohana::config('photo_gallery.thumbnail_height'), Image::HEIGHT);
			$image->crop(Kohana::config('photo_gallery.thumbnail_width'), Kohana::config('photo_gallery.thumbnail_height'));
		}
		else
		{
			$image->resize(Kohana::config('photo_gallery.thumbnail_width'), Kohana::config('photo_gallery.thumbnail_height')/$ratio, Image::WIDTH);
			$image->crop(Kohana::config('photo_gallery.thumbnail_width'), Kohana::config('photo_gallery.thumbnail_height'), 'top');
		}

		$thumb_status = $image->quality(65)->save(APPPATH.'views/media/photos/'.$album->url_name.'/thumb_'.$file['name']);
	}

	public function _upload_photo(Validate $array, $field)
	{
		if (Upload::not_empty($array['picture']))
		{
			$filename = Upload::save($array['picture']);
			$image = Image::factory($filename);
			if($image->width > 640 || $image->height > 480)
			{
				$image->resize(640, 480, Image::WIDTH);
			}
			$image->save(NULL, 80);
			$old_file = DOCROOT.'upload/'.$this->picture;
			if(file_exists($old_file))
			{
				unlink($old_file);
			}
			$array['picture'] = basename($filename);
		}
		else
		{
			$array['picture'] = $this->picture;
		}
	}

// Save the photo
//	gallery::process_upload($_FILES['photo'], $album, $photo);

}