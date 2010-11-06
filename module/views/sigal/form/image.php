<?php 
echo View::factory('sigal/errors')->set('errors', $errors);

echo Form::open(NULL, array('enctype' => 'multipart/form-data'));

echo	'<div>',
		Form::label('name', __('Name')),
		Form::input('name', Arr::get($form, 'name'), array('id' => 'name')),
		'</div>';

echo	'<div>',
		Form::label('description', __('Description')),
		Form::textarea('description', Arr::get($form, 'description'), array('id' => 'description')),
		'</div>';

if(Arr::get($form, 'filename') == NULL)
{
	echo '<div>',
		Form::label('file', __('File')),
		Form::file('file', array('id' => 'file')),
		'</div>';
}
else
{
	echo HTML::image(Sigal::image_path($image, TRUE));
}


echo	'<div>',
		Form::label('order', __('Order')),
		Form::input('order', Arr::get($form, 'order'), array('id' => 'order')),
		'</div>';

echo Form::submit('submit', __('Submit'));
echo Form::close();
?>