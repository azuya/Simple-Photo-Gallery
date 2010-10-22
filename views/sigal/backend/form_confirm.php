<?php 
	echo Form::open();
	echo Form::label('Are you sure?');
	echo Form::input('confirm', 'Confirm', array('type' => 'submit'));
	echo Form::input('cancel', 'Cancel', array('type' => 'submit'));
	echo Form::close();
?>