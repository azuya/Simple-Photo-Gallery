<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The controller that handles all the backend data for the images.
 *
 * @package    Sigal
 * @author     Peter Briers
 * @category   Controllers
 * @license    ???
 */

class Sigal_Controller_Backend_Image extends Controller
{
/*
	public function before()
	{
		parent::before();
		if( ! Sigal::admin_rights()) {
			throw new Kohana_Exception('No rights');
		}
	}
*/

	/**
	 * Add a new photo to the gallery
	 *
	 * @param  integer  The id of the gallery
	 */
	public function action_add($gallery_id)
	{
		$image = Sigal::factory('image');
		if ($_POST)
		{
			$merged = array_merge($_POST, $_FILES, array('gallery_id'=>$gallery_id));
			$validation = Sigal::validate_image($merged);
			if($validation->check())
			{
				//try
				//{
					$image->update_fields($merged);  // Execute this first, because it sets the filename, which is needed for uploading...
					$image->upload($merged['file']['tmp_name']); // (!) Saves the image & thumbnail
					$image->save();

				//}
				//catch(Exception $e)
				//{
				//	throw new Sigal_Exception($e . 'Something went wrong with the image uploading.');
				//}
				$this->request->redirect(Route::get('sigal-backend')->uri(array('controller' => 'gallery', 'action' => 'images', 'id'=>$gallery_id)));
			}
			else
			{
				$form = $validation->as_array();
				$errors = $validation->errors('sigal');
			}
		}
		$this->request->response = View::factory('sigal/form/image')
			->bind('errors', $errors)
			->bind('form', $form)
			->set('action', 'Create');
	}

	/**
	 * Edit an image
	 *
	 * @TODO not done yet
	 * @param  integer  The id of the image
	 */
	public function action_edit($id)
	{
		$image = Sigal::factory('image')->read_by_id($id);
		$form = $image->as_array(); // as_array() is also used in Jelly
		if ($_POST)
		{
			$validation = $image->update_fields($_POST);
			if($photo->validate())
			{
				if ( ! $_FILES['photo']['error'])
				{
					// Delete the old photo before we change it
					$photo->delete_file();
					$photo->update();
					// @TODO: This should be done in the model - Zeelot
					// Create a thumbnail and resized version
					$image = new Image($_FILES['photo']['tmp_name']);
					$image->resize(Kohana::config('photo_gallery.image_width'), Kohana::config('photo_gallery.image_height'), Image::AUTO);
					$image->save(APPPATH.'views/media/photos/'.$album_url.'/'.$_FILES['photo']['name']);
					$image->resize(Kohana::config('photo_gallery.thumbnail_width'), Kohana::config('photo_gallery.thumbnail_height'), Image::AUTO);
					$image->save(APPPATH.'views/media/photos/'.$album_url.'/thumb_'.$_FILES['photo']['name']);
				}
				$photo->save();
				Request::instance()->redirect(Route::url('sigal-backend', array('controller'=>'album', 'action'=>'view', 'id'=>$gallery_id)));
			}
			else
			{
				$errors = $user->validate()->errors();
			}
		}
		$this->request->response = View::factory('sigal/form/image')
			->bind('errors', $errors)
			->bind('photo', $photo)
			->set('action', 'Edit');
	}

	/**
	 * Delete an image
	 *
	 * @param  integer  The id of the image
	 */
	public function action_delete($id)
	{
		$image = Sigal::factory('image')->read_by_id($id);
		$gallery_id = $image->gallery->id;
		if(isset($_POST['confirm']))
		{
			$image->delete(); // (!) This also deletes the file from the drive
			$this->request->redirect(Route::get('sigal-backend')->uri(array('controller'=>'gallery', 'action'=>'images', 'id'=>$gallery_id)));
		}
		elseif(isset($_POST['cancel']))
		{
			$this->request->redirect(Route::get('sigal-backend')->uri(array('controller'=>'gallery', 'action'=>'images', 'id'=>$gallery_id)));
		}
		$this->request->response = View::factory('sigal/form/confirm');
	}
}