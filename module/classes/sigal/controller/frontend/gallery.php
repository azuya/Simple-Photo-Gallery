<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The controller that handles the frontend.
 *
 * @package    Sigal
 * @author     Peter Briers
 * @category   Controllers
 * @license    ???
 */

class Sigal_Controller_Frontend_Gallery extends Controller {

	public function action_index()
	{
		$this->request->response = View::factory('sigal/galleries')
			->set('galleries', Sigal::factory('gallery')->read_all());
	}

	public function action_view($slug = NULL)
	{
		$gallery = Sigal::factory('gallery')->read_by_slug($slug);
		$pagination =  Pagination::factory(array(
			'view'			 => 'sigal/pagination',
			'total_items'    => count($gallery->read_images()),
			'items_per_page'    => Kohana::config('sigal.items_per_page'),
		));
		$this->request->response = View::factory('sigal/images')
			->set('title', $gallery->name)
			->set('images', $gallery->read_images($pagination->offset))
			->set('pages', $pagination);
	}
}