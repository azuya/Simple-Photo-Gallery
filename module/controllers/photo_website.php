<?php

class Photo_Website_Controller extends Website_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (request::is_ajax())
		{
			$this->template = new View('blank');
		}
	}
}