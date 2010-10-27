<?php 
echo View::factory('sigal/form_errors')->set('errors', $errors);

echo Form::open(NULL, array('enctype' => 'multipart/form-data'));
echo	'<div>',
		Form::label('name', __('Name')),
		Form::input('name', Arr::get($form, 'photo'), array('id' => 'name')),
		'</div>';
echo	'<div>',
		Form::label('photo', __('Photo')),
		Form::input('photo', Arr::get($form, 'photo'), array('id' => 'photo')),
		'</div>';
echo Form::submit('submit', __('Submit'));
echo Form::close();

?>