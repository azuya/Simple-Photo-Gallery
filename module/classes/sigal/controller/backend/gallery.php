<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The controller that handles all the backend data for the galleries.
 *
 * @package    Sigal
 * @author     Peter Briers
 * @category   Controllers
 * @license    ???
 */
class Sigal_Controller_Backend_Gallery extends Controller
{
	/*
	public function before()
	{
		parent::before();
		echo 'Auth:', Kohana::debug(Sigal::admin_rights());
		if( ! Sigal::admin_rights()) {
			throw new Kohana_Exception('No rights');
		}
	}
	*/

	/**
	 * Show all the galleries
	 *
	 * @return void
	 */
	public function action_index()
	{
		$this->request->response = View::factory('sigal/edit_galleries')->set('galleries', Sigal::factory('gallery')->read_all());
	}

	/**
	 * Create a new gallery
	 *
	 * @return void
	 */
	public function action_create()
	{
		$gallery = Sigal::factory('gallery');
		if ($_POST)
		{
			$validation = Sigal::validate_gallery($_POST);
			if($validation->check())
			{
				$gallery->update_fields($_POST);
				$gallery->save(); // (!) This also handles the creation of folders
				$this->request->redirect(Route::get('sigal-backend')->uri(array('controller' => 'gallery', 'action' => 'index')));
			}
			else {
				$form = $validation->as_array();
				$errors = $validation->errors('sigal');
			}
		}
		$this->request->response = View::factory('sigal/form/gallery')
			->bind('gallery', $gallery)
			->bind('errors', $errors)
			->bind('form', $form)
			->set('action', 'Create');
	}

	/**
	 * Edit a gallery (name & order)
	 * 
	 * @param   integer   gallery id
	 * @return void
	 */
	public function action_edit($id = NULL)
	{
		$gallery = Sigal::factory('gallery')->read_by_id($id);
		$form = $gallery->as_array(); // as_array() is also used in Jelly
		if ($_POST)
		{
			$validation = Sigal::validate_gallery($_POST);
			if($validation->check())
			{
				$gallery->update_fields($_POST);
				$gallery->save();
				$this->request->redirect(Route::get('sigal-backend')->uri(array('controller' => 'gallery', 'action' => 'index')));
			}
			else
			{
				$form = $validation->as_array();
				$errors = $validation->errors('sigal');
			}
		}
		$this->request->response = View::factory('sigal/form/gallery')
			->bind('gallery', $gallery)
			->bind('errors', $errors)
			->bind('form', $form)
			->set('action', 'Edit');
	}

	/**
	 * Delete a gallery
	 *
	 * @param   integer   gallery id
	 * @return void
	 */
	public function action_delete($id = NULL)
	{
		$gallery = Sigal::factory('gallery')->read_by_id($id);
		if(isset($_POST['confirm']))
		{
			$gallery->delete(); // (!) This deletes the folder and related images from the drive and database
			$this->request->redirect(Route::get('sigal-backend')->uri(array('controller'=>'gallery', 'action'=>'index')));
		}
		elseif(isset($_POST['cancel']))
		{
			$this->request->redirect(Route::get('sigal-backend')->uri(array('controller'=>'gallery', 'action'=>'index')));
		}
		$this->request->response = View::factory('sigal/form/confirm');
	}

	/**
	 * See all the images of a gallery, to edit them
	 *
	 * @param   integer   gallery id
	 * @return void
	 */
	public function action_images($id = NULL)
	{
		$gallery = Sigal::factory('gallery')->read_by_id($id);
		$this->request->response = View::factory('sigal/edit_images')
			->set('gallery', $gallery);
			//->set('images', $gallery->read_images());
	}
}