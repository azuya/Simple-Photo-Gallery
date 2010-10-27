<?php
$backend = Route::get('sigal-backend');
$frontend = Route::get('sigal-frontend');
$button = array('class' => 'button');
?>
<p class="button">
<?php
echo html::anchor(
	$backend->uri(array(
		'controller' => 'gallery',
		'action' => 'create')),
	__('Create album'));
?>
</p>
<ul>
	<?php foreach ($galleries as $gallery): ?>
		<li>
		<?php
			echo HTML::anchor(
				$frontend->uri(array(
					'controller' => 'gallery',
					'action' => 'view',
					'id' => $gallery->slug)),
				$gallery->name);

			echo HTML::anchor(
				$backend->uri(array(
					'controller' => 'album',
					'action' => 'delete',
					'id' => $gallery->slug)),
				__('Delete'),
				$button);
 
			echo HTML::anchor(
				$backend->uri(array(
					'controller' => 'album',
					'action' => 'edit',
					'id' => $gallery->slug)),
				__('Edit'),
				$button);
		?>
		</li>
	<?php endforeach; ?>
</ul>