<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Sigal_Frontend_Gallery extends Controller {

	public function action_index()
	{
		$this->request->response = View::factory('sigal/user/galleries')
			->set('galleries', Gallery::factory()->read_all());
	}

	public function action_view($slug = NULL)
	{
		try
		{
			$album = Gallery::factory()->find_by_slug($slug);
		} 
		catch (Exception $exc)
		{
			throw new Kohana_Exception($exc);
		}
		$paging =  Pagination::factory(array(
			'view'			 => 'sigal/pagination',
			'total_items'    => Gallery::factory()->read_related_photos->count_all(),
			'items_per_page'    => Kohana::config('sigal.photos_per_page'),
		));
		$this->request->response = View::factory('album/view')
			->set('title', $album->album_name)
			->set('photos', $album->read_related_photos())
			->set('pages', $paging);
	}
}