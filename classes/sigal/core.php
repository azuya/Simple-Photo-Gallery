<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The base class for Sigal
 *
 * @package    Sigal
 * @author     Peter Briers
 * @license    ???
 */
abstract class Sigal_Core {

	/**
	 * Creates and returns a new model, based on the model drivers
	 *
	 * @chainable
	 * @param   string  model name: 'gallery' or 'image'
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
	 * 
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
	 * Shorthand for Kohana::config('sigal.path.galleries').$image->gallery->slug.'/'
	 *
	 * @param string  The relative path to the filename
	 */
	public static function full_path($image, $thumb = FALSE)
	{
		$filename = ($thumb === TRUE) ? 'thumb_'.$image->filename : $image->filename;
		return Kohana::config('sigal.path.galleries').$image->gallery->slug.'/'.$filename;
	}

	public static function upload_file($file)
	{
		
	}

	public static function delete_file($file)
	{
		return unlink(APPPATH.'views/media/photos/'.$this->album->url_name.'/'.$this->photo_filename)
	   AND unlink(APPPATH.'views/media/photos/'.$this->album->url_name.'/thumb_'.$this->photo_filename);
	}


	public static function process_upload($file, Album_Model $album, Photo_Model $photo)
	{
		if ( ! $photo['error'])
		{
			! is_dir(APPPATH.'views/media/photos/'.$album->url_name) AND mkdir(APPPATH.'views/media/photos/'.$album->url_name);

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
	}
}