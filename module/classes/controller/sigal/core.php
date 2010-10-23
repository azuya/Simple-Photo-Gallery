<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Sigal extends Controller_Sigal_Core {

	// Sigal instances
	protected static $_instance;

	/**
	 * Singleton pattern
	 *
	 * @return Sigal
	 */
	public static function instance()
	{
		if ( ! isset(Sigal::$_instance))
		{
			// Load the configuration for this type
			$config = Kohana::config('sigal');

			if ( ! $type = $config->get('driver'))
			{
				$type = 'ORM';
			}

			// Set the session class name
			$class = 'Auth_'.ucfirst($type);

			// Create a new session instance
			Auth::$_instance = new $class($config);
		}

		return Auth::$_instance;
	}

	/**
	 * Create an instance of Auth.
	 *
	 * @return  Auth
	 */
	public static function factory($config = array())
	{
		return new Auth($config);
	}

	public function action_index()
	{
		$data['albums'] = ORM::factory('album')->order_by('order')->find_all();
		$data['user'] = Auth::instance()->get_user();
		$this->request->response = View::factory('album/index', $data);
	}

	public function action_view($album_name = NULL)
	{
		try
		{
			$album = ORM::factory('album', array('name' => $album_name));
		} 
		catch (Exception $exc)
		{
			trigger_error ('Album does not exist...');
		}
		$page_num = Arr::get($_GET, 'page', 1);
		$data['title'] = $album->album_name;
		$data['photos'] = $album->find_photos($page_num);
		$data['num_pages'] = ceil(count($album->find_related('photos')) / Kohana::config('gallery.photos_per_page'));
		$data['user'] = Auth::instance()->get_user();
		$this->request->response = View::factory('album/view', $data);
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