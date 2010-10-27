<?php
$backend = Route::get('gallery-backend');
$frontend = Route::get('gallery-frontend');
$button = array('class' => 'button');
?>
<p class="button">
<?php
echo html::anchor(
	$backend->uri(array(
		'controller' => 'album',
		'action' => 'create')),
	__('Create album'));
?>
</p>
<ul>
	<?php foreach ($albums as $album): ?>
		<li>
		<?php
			echo HTML::anchor(
				$frontend->uri(array(
					'controller' => 'album',
					'action' => 'view')),
				$album->name);

			echo HTML::anchor(
				$backend->uri(array(
					'controller' => 'album',
					'action' => 'delete',
					'id' => $album->id)),
				__('Delete'),
				$button);
 
			echo HTML::anchor(
				$backend->uri(array(
					'controller' => 'album',
					'action' => 'edit',
					'id' => $album->id)),
				__('Edit'),
				$button);
		?>
		</li>
	<?php endforeach; ?>
</ul>