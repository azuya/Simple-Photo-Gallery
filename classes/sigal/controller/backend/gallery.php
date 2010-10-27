<?php defined('SYSPATH') or die('No direct script access.');

class Sigal_Controller_Backend_Gallery extends Controller
{
	public function _before()
	{
		parent::before();
		echo 'Auth:', Kohana::debug(Sigal::admin_rights());
		if( ! Sigal::admin_rights()) {
			throw new Kohana_Exception('No rights');
		}
	}

	public function action_index()
	{
		$this->request->response = View::factory('sigal/backend/index')
			->set('galleries', Sigal::factory('gallery')->read_all());
	}

	public function action_create()
	{
		if ($_POST)
		{
			$gallery = Sigal::factory('gallery')->update_fields($_POST);
			if($gallery->validate()) {
				$gallery->save();
				$this->request->redirect(Route::get('sigal-backend')->uri(array('controller' => 'album', 'action' => 'index')));
			}
			else {
				$form = $_POST;
				$errors = $gallery->validate()->errors('sigal');
			}
		}
		$this->request->response = View::factory('sigal/backend/form_gallery')
			->bind('gallery', $gallery)
			->bind('errors', $errors)
			->bind('form', $form)
			->set('action', 'Create');
	}

	public function action_edit($id = NULL)
	{

		$gallery = Gallery::factory($id);
		$old_slug = $gallery->slug;
		$path = Kohana::config('gallery.path.photos');

		if ($_POST)
		{
			$gallery->update_fields($_POST);
			$gallery->slug = URL::title($gallery->name, '-', TRUE);
			if($gallery->validate())
			{
				$gallery->save();
				// @TODO: This should be done in the model, with save...
				if($old_slug != $gallery->slug)
				{
					if(file_exists($path.URL::title($old_slug)))
						rename($path.URL::title($old_slug), $path.$gallery->slug);
					else
						mkdir($path.URL::title($gallery->slug));
				}
				$this->request->redirect(Route::get('sigal-backend')->uri(array('controller' => 'gallery', 'action' => 'index')));
			}
			else
			{
				$form = $_POST;
				$errors = $gallery->validate()->errors('sigal');
			}
		}
		$this->request->response = View::factory('sigal/backend/form_album')
			->bind('gallery', $gallery)
			->bind('errors', $errors)
			->bind('form', $form)
			->set('action', 'Edit');
	}

	public function delete($id = NULL)
	{
		$gallery = Gallery::factory($id);
		if(isset($_POST['confirm']))
		{
			$gallery->delete(); // This also deletes related photo DB rows!
			$this->request->redirect(Route::get('sigal-backend')->uri(array('controller'=>'gallery', 'action'=>'index')));
		}
		elseif(isset($_POST['cancel']))
		{
			$this->request->redirect(Route::get('sigal-backend')->uri(array('controller'=>'gallery', 'action'=>'index')));
		}
		$this->request->response = View::factory('sigal/backend/form_confirm');
	}

	public function reorder($id = NULL)
	{
		$this->request->response = View::factory('sigal/backend/photo_reorder')
			->set('album', Album::factory($id));
	}

	/**
	 * TODO
	 */
	public function reorder_process()
	{
		if (Session::instance()->get('image_csrf', NULL) == Input::instance()->get('csrf_token', ''))
		{
			Gallery::factory('photo')->batch_reorder($_GET['photo']);
			die('<p>Reorder Successful!</p>');
		}
		else
			die('<p>Unexpected Error Occurred. Please Try Again.</p>');
	}

}