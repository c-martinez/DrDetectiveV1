<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<button type="button" class="btn btn-navbar" data-toggle="collapse"
				data-target=".nav-collapse">
				<span class="icon-bar"></span> <span class="icon-bar"></span> <span
					class="icon-bar"></span>
			</button>
			<a class="brand" href="#">Dr.Detective</a>
			<div class="nav-collapse collapse">
				<ul class="nav">
					<li class="active"><a href="index.php">Home</a></li>
					<li><a href="game.php">Game</a></li>
					<li><a href="leaders.php"> High Scores</a></li>
					<li><a href="about.php">About</a></li>
				</ul>

				<ul class="nav pull-right">
					<li class="navmsg">Hi <span class="navname" id="navuser"></span>!
						You scored: <span class="navimp" id="navscore"> </span> points
					</li>
					<li><a href="#" id="notifPopover" rel="popover"
						data-content="<?php echo $pop_text; ?>"><img src="img/red.png"
							width="25" height="25"><img src="img/blue.png" width="20"
							height="20"> </a></li>
					<li><a href="logout.php">(logout)</a></li>
				</ul>
				<?php if(isset($levelVerb) & isset($domains)) {?>
				<br/>
            <div class="menudet pull-left">Level: <span class="menufont"><?php echo $levelVerb;?></span>, Domain: <span class="menufont"><?php echo $domains;?></span></div>
				<?php }?>
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>
</div>
