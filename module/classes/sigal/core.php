<?php defined('SYSPATH') or die('No direct script access.');

/**
 * All the basic functions for Sigal
 *
 * @package    Sigal
 * @category   Helpers
 * @author     Peter Briers
 * @license    ???
 * @uses       Validation, Auth
 */

abstract class Sigal_Core {

	/**
	 * Creates and returns a new model, based on the model drivers
	 *
	 * @chainable
	 * @param string model name: gallery or image
	 * @return  mixed
	 */
	public static function factory($model)
	{
		// Load the type from the configuration
		$type = Kohana::config('sigal.driver.model');
		// Set class name
		$class = 'Sigal_Model_'.ucfirst($model).'_'.ucfirst($type);
		return new $class();
	}

	/**
	 * Check if the user has admin-rights
	 * TODO: Add different Authentications
	 *
	 * @uses Auth
	 * @return mixed
	 */
	public static function admin_rights() 
	{
		switch (Kohana::config('sigal.driver.auth')) {
			case 'auth':
				return (Auth::instance()->logged_in('admin'));
				break;
			
			default:
				return false;
				break;
		}
	}

	/**
	 * Shorthand for (DOCROOT.)Kohana::config('sigal.path.galleries').$image->gallery->slug.'/'.$image->filename
	 * Used in the views.
	 *
	 * @param  object  The image object
	 * @param  boolean  Create a path to the thumbnail or not
	 * @param  boolean  Return the absolute path
	 * @return string
	 */
	public static function image_path($image, $thumb = FALSE, $absolute = FALSE)
	{
		$filename = ($thumb === TRUE) ? 'thumb_'.$image->filename : $image->filename;
		if($absolute)
			return DOCROOT.Kohana::config('sigal.path.galleries').$image->gallery->slug.'/'.$filename;
		else
			return Kohana::config('sigal.path.galleries').$image->gallery->slug.'/'.$filename;
	}

	/**
	 *
	 * @param  array  One file of $_FILES variable
	 * @param  Image  The image object that needs to be uploaded
	 */
	public static function process_upload($path, $imgobj)
	{
		// process image
		$image = self::process_image($path);
		$image->save(Sigal::image_path($imgobj, FALSE, TRUE), 80);
		// process thumbnail
		$thumb = self::process_thumbnail($path);
		$thumb->save(Sigal::image_path($imgobj, TRUE, TRUE), 70);
	}

	protected static function process_image($path)
	{
		$image = Image::factory($path);
		$ratio = $image->width / $image->height;
		$constraint = ($ratio > 1) ? Image::HEIGHT : Image::WIDTH;
		$image->resize(Kohana::config('sigal.image.width'), Kohana::config('sigal.image.heigth'), $constraint);
		return $image;
	}

	protected static function process_thumbnail($path)
	{
		$image = Image::factory($path);
		$ratio = $image->width / $image->height;
		$constraint = ($ratio > 1) ? Image::HEIGHT : Image::WIDTH;
		$image->resize(Kohana::config('sigal.thumbnail.width'), Kohana::config('sigal.thumbnail.heigth'), $constraint);
		$image->crop(Kohana::config('sigal.thumbnail.width'), Kohana::config('sigal.thumbnail.height'));
		return $image;
	}

	/**
	 * Validates a gallery
	 *
	 * @param array  The array to validate
	 * @return  boolean
	 */
	public static function validate_gallery($array)
	{
		return $validation = Validate::factory($array)
			->filter(TRUE, 'trim')
			->rule('name', 'not_empty');
	}

	/**
	 * Validates an image
	 *
	 * @param array  The array to validate
	 * @return  boolean
	 */
	public static function validate_image($array)
	{
		return $validation = Validate::factory($array)
			->filter('name', 'trim')
			->rule('name', 'not_empty')
			->rule('gallery_id', 'digit')
			->rule('file', 'Upload::valid')
			->rule('file', 'Upload::type', array(array('jpg', 'png', 'gif')))
			->callback('file', 'Sigal::valid_filename', array($array));
	}

	/**
	 * If no slug is currently set, it creates a slug for the gallery, and create a path.
	 * If the name of the gallery changed, it will created a new slug, and rename the path.
	 *
	 * @param  object  Gallery-object
	 * @return  string  The new slug
	 */
	public static function set_slug(& $gallery)
	{
		$path = DOCROOT.Kohana::config('sigal.path.galleries');
		$old_slug = $gallery->slug;
		$new_slug = URL::title($gallery->name, '-', TRUE);
		if($old_slug != $new_slug)
		{
			if(file_exists($path.$old_slug) && !empty($old_slug))
				rename($path.$old_slug, $path.$new_slug);
			else
				mkdir($path.$new_slug);
		}
		return $new_slug;
	}

	/**
	 * When the order is left blank, it sets the order of the gallery/image.
	 *
	 * @param Gallery/Image  The Gallery or Image you want to get an order from
	 * @return integer  The order
	 */
	public static function set_order($object)
	{
		// If the order is empty, create one, depending on what kind of object it is
		if(empty($object->order))
		{
			if($object instanceof Sigal_Interface_Gallery)
			{
				$table = Kohana::config('sigal.table.galleries');
				$highest = DB::select('order')->from($table)->order_by('order', 'desc')->execute()->current();
			}
			elseif($object instanceof Sigal_Interface_Image)
			{
				$table = Kohana::config('sigal.table.images');
				$highest = DB::select('order')->from($table)->where('gallery_id', '=', $object->gallery_id)->order_by('order', 'desc')->execute()->current();
			}
			return (int) $highest['order'] + 1;
		}
		// Otherwise change nothing
		else {
			return $object->order;
		}
	}

	/**
	 * Deletes a folder
	 *
	 * @param  string  the folder name of gallery
	 * @return boolean
	 */
	public static function delete_folder($folder)
	{
		$path = Kohana::config('sigal.path.galleries');
		return rmdir(DOCROOT.$path.$folder);
	}

	/**
	 * Deletes a file
	 *
	 * @param  string  the file that needs to be deleted.
	 * @return boolean
	 */
	public static function delete_file($filename)
	{
		// If there is an error, there is a high probability the file didn't exist.
		return @unlink(Sigal::image_path($filename, FALSE, TRUE)) && @unlink(Sigal::image_path($filename, TRUE, TRUE));
	}

	
	/**
	 * CALLBACK
	 *
	 * @param Validation array
	 */
	public static function valid_filename(Validate $array)
	{
		$filename = $array['file']['name'];
		$filename_exists = (bool) DB::select('name')->from(Kohana::config('sigal.table.images'))->where('gallery_id', '=', $array['gallery_id'])->where('filename', '=', $filename)->execute()->count();
		if($filename_exists)
		{
			$array->error('file', 'Filename exists');
		}
	}
}