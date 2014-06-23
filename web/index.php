<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta charset="utf-8">
<title>Dr. Detective Game</title>
<link href="dr-detective-simple/bootstrap/css/bootstrap.css"
	rel="stylesheet">
</head>

<body>

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<button type="button" class="btn btn-navbar" data-toggle="collapse"
					data-target=".nav-collapse">
					<span class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="brand" href="#">Dr.Detective</a>
			</div>
		</div>
	</div>

	<?php include "header.php"; ?>

	<div class="container">
		<div class="span6" style="float: none; margin: 0 auto;">
			<br />
			<br />
			<br />
			<div class="container-fluid" style="vertical-align: middle;">

				<h1>Dr. Detective</h1>
				<p>Dr. Detective is an online game for annotating medical texts.
					The game is designed with the purpose of engaging medical experts
					into solving annotation tasks on medical case reports, tailored to
					capture disagreement between annotators. It incorporates incentives
					such as learning features, to motivate a continuous involvement of
					the expert crowd.</p>
				<p>The game was designed to identify expressions valuable for
					training natural language processing (NLP) tools, and interpret
					their relation in the context of medical diagnosing. In this way,
					we can resolve the main problem in gathering ground truth from
					experts that the low inter-annotator agreement is typically caused
					by different interpretations of the text.</p>
				<p>
					For further details on Dr. Detective game, please refer to <a
						href="http://ceur-ws.org/Vol-1030/paper-02.pdf">"Dr.
						Detective": combining gamification techniques and crowdsourcing to
						create a gold standard in medical text</a>. A snapshot of game statistics
						taken on June 2014 can be found <a href="stats-static/">here</a>. 
						Up-to-date game statistics can be found <a href="game-stats/">here</a>. 
				</p>
			</div>
		</div>
	</div>

</body>
</html>
