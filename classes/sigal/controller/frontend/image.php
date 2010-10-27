<?php defined('SYSPATH') or die('No direct script access.');
/*
 * (!) NOT USED
 * Is this controller even necessary? (maybe good for caching?)
 * Why not just link to the actual path?
 * If used, make sure it can show .jpgs, .pngs, and gifs.
 */
class Sigal_Controller_Frontend_Image extends Controller
{
	public function action_thumbnail($gallery_slug = NULL, $filename = NULL)
	{
		header('Content-Type: image/jpeg');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: '.filesize(DOCROOT.Kohana::config('sigal.paths.galleries').$gallery_slug.'/thumb_'.$filename));
		readfile(DOCROOT.Kohana::config('sigal.paths.galleries').$gallery_slug.'/thumb_'.$filename);
		exit;
	}

	public function action_view($gallery_slug = NULL, $filename = NULL) //filename needs to change to id
	{
		header('Content-Type: image/jpeg');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: '.filesize(DOCROOT.Kohana::config('sigal.paths.galleries').$gallery_slug.'/'.$filename));
		readfile(DOCROOT.Kohana::config('sigal.paths.galleries').$gallery_slug.'/'.$filename);
		exit;
	}
}