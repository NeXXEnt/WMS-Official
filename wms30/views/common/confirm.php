<div class="background"></div>
<div class="modal notice">
	<div class="close-modal">
		<a class="button negative" href="<?= $returnLink ?>"><img src="/pics/icons/cross.png">Close</a>
	</div>
	<h3><?= $question ?></h3>
	<form action="<?= $forwardLink ?>" method="post">
		<input type="hidden" name="confirmed" value="1">
		<input type="submit" class="button positive" value="Yes">
	</form>
</div>