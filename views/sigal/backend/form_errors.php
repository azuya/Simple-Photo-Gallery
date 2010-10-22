<?php if(!empty($errors)) : ?>
	<div class="errors">
	<ul>
	<?php foreach ($errors as $field => $error)	{
		printf('<li><label for="%s">%s</label></li>', $field, $error);
	} ?>
	</ul>
	</div>
<?php endif; ?>