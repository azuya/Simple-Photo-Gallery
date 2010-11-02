<?php 
	echo Form::open();
	echo Form::label(__('Are you sure?'));
	echo Form::submit('confirm', 'Confirm');
	echo Form::submit('cancel', 'Cancel');
	echo Form::close();
?>