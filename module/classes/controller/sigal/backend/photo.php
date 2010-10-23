<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Gallery_Backend_Photo extends Controller
{
	public function _before()
	{
		parent::before();
		if( ! Sigal::admin_rights()) {
			throw new Kohana_Exception('No rights');
		}
	}

	public function add($gallery_id)
	{
		if ($_POST)
		{
			$gallery = Gallery::factory($gallery_id);
			$merged = array_merge($_POST, $_FILES);
			$photo = Photo::factory()->update_fields($merged);
			$photo->gallery_id = $gallery_id;
			$photo->order = $gallery->get_related_photos()->count() + 1;
			if($photo->validate())
			{
				$photo->save();
				$photo->upload();
				if($photo->saved() && $photo->uploaded())
				{
					Request::instance()->redirect(Route::url('sigal-backend', array('controller'=>'album', 'action'=>'view', 'id'=>$gallery_id)));
				}
			}
			else
			{
				$errors = $user->validate()->errors();
			}
		}
		$this->request->response = View::factory('sigal/backend/form_photo')
			->bind('errors', $errors)
			->set('action', 'Create');
	}

	
	public function edit($id)
	{
		$photo = Photo::factory($id);
		if( ! $photo->loaded())
		{
			throw new Kohana_Exception('Photo not found');
		}
		if ($_POST)
		{
			$photo->update_fields($_POST);
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
		$this->request->response = View::factory('sigal/backend/form_photo')
			->bind('errors', $errors)
			->bind('photo', $photo)
			->set('action', 'Edit');
	}

	// TODO: this is not done yet.
	public function delete($id)
	{
		$photo = Photo::factory($id);
		if( ! $photo->loaded())
		{
			throw new Kohana_Exception('Photo not found');
		}
		if( isset($_POST['confirm']))
		{
			//$photo->replace_order($photo, 'down');
			$url = Auto_Modeler_ORM::factory('album', $photo->album_id)->url_name;
			$photo->delete();
			Request::instance()->redirect(Route::url('sigal-backend', array('controller'=>'album', 'action'=>'view', 'id'=>$gallery_id)));
		}
		elseif( isset($_POST['cancel']))
			Request::instance()->redirect(Route::url('sigal-backend', array('controller'=>'album', 'action'=>'view', 'id'=>$gallery_id)));

		$this->request->response = View::factory('sigal/backend/form_confirm');
	}
}