<div class="sidebar span-6">
	<p id="side-bar-title">Actions</p>
	<ul class="side-bar-menu" id="side-bar-menu">
		<?php
			foreach($sideBarLink as $title => $link)
				echo "<li><a class='side-bar-menu' href='$link'>$title</a></li>";
	    ?>
	</ul>
</div>