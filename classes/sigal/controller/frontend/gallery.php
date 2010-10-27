<?php defined('SYSPATH') or die('No direct script access.');

class Sigal_Controller_Frontend_Gallery extends Controller {

	public function action_index()
	{
		$this->request->response = View::factory('sigal/frontend/galleries')
			->set('galleries', Sigal::factory('gallery')->read_all());
	}

	public function action_view($slug = NULL)
	{
		try
		{
			$gallery = Sigal::factory('gallery')->find_by_slug($slug);
		} 
		catch (Exception $exc)
		{
			throw new Kohana_Exception($exc);
		}
		$paging =  Pagination::factory(array(
			'view'			 => 'sigal/pagination',
			'total_items'    => count($gallery->read_images()),
			'items_per_page'    => Kohana::config('sigal.items_per_page'),
		));
		$this->request->response = View::factory('sigal/frontend/gallery')
			->set('title', $gallery->name)
			->set('images', $gallery->read_images())
			->set('pages', $paging);
	}
}