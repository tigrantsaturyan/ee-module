<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<table class="table table-hover">
	<thead>
		<tr>
			<th>id</th>
			<th><?=lang('first_name')?></th>
			<th><?=lang('last_name')?></th>
			<th><?=lang('birthday')?></th>
			<th><?=lang('email')?></th>
			<th><?=lang('avatar')?></th>
			<th><?=lang('ip</')?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($users as $user) : ?>
		<tr>
			<td><?=$user->id?></td>
			<td><?=$user->first_name?></td>
			<td><?=$user->last_name?></td>
			<td><?=$user->birthday?></td>
			<td><?=$user->login?></td>
			<td><img src="images/avatar/<?=$user->id?>/<?=$user->avatar?>" width="100"></td>
			<td><?=$user->ip?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>