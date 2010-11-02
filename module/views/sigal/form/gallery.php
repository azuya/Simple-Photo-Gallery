<?php
echo View::factory('sigal/errors')->set('errors', $errors);

echo	Form::open();
echo	'<div>',
		Form::label('name', __('Name')),
		Form::input('name', Arr::get($form, 'name'), array('id' => 'name')),
		'</div>';
echo	'<div>',
		Form::label('order', __('Order')),
		Form::input('order', Arr::get($form, 'order'), array('id' => 'order')),
		'</div>';

echo Form::submit('submit', __('Submit'));
echo Form::close();
?>
