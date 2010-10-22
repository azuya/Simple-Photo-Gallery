<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Photo extends Controller
{
	public function action_thumbnail($album_url = NULL, $filename = NULL)
	{
		header('Content-Type: image/jpeg');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: '.filesize(APPPATH.'/views/media/photos/'.$album_url.'/thumb_'.$filename));

		readfile(APPPATH.'/views/media/photos/'.$album_url.'/thumb_'.$filename);
		exit;
	}

	public function action_view($album_url = NULL, $filename = NULL)
	{
//		$photo = new Photo_Model($album_url.'/'.$filename);
//
//		if ( ! $photo->id)
//			Event::run('system.404');

		header('Content-Type: image/jpeg');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: '.filesize(APPPATH.'/views/media/photos/'.$album_url.'/'.$filename));

		readfile(APPPATH.'/views/media/photos/'.$album_url.'/'.$filename);
		exit;
	}
}