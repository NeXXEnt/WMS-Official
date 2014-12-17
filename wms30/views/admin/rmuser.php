<div class="background"></div>
<div class="span-13 modal notice">
	<div class="close-modal">
		<a class="button negative" href="/admin/user"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h3>Are you sure you want to remove the user: <strong><u><?= $rmUser->userName ?></u></strong>? </h3>
	<form action="/admin/rmuser/<?= $rmUser->user_id ?>" method="post">
		<input type="hidden" name="confirmed" value="1">
		<input type="submit" class="button positive" value="Yes">
	</form>
</div>