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
					<li class="active"><a href="#" onclick="nextPatient('index')">Task</a>
					</li>
					<li><a href="about.php">About</a></li>
				</ul>

				<ul class="nav pull-right">
					<li class="navmsg">Hi <span class="navimp" id="navuser"></span>!
						You scored: <span class="navimp" id="navscore"> </span> points
					</li>
					<li><a href="login.php">(logout)</a></li>
				</ul>
				<?php if(isset($domains)) {?>
				<br /> <br />
				<div class="menudet pull-left">
					Domain: <span class="menufont"><?php echo $domains;?> </span>
				</div>
				<?php }?>
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>
</div>
