<?php defined('SYSPATH') or die('No direct script access.');

abstract class Sigal_Core {

	/**
	 * Check if the user has admin-rights
	 * 
	 * @return mixed
	 */
	public static function admin_rights() 
	{
		switch (Kohana::config('sigal.drivers.auth')) {
			case 'Auth':
				return (Auth::instance()->logged_in('admin'));
				break;

			default:
				return false;
				break;
		}
	}

}