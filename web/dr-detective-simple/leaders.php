<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="utf-8"> 
<title>Dr. Watson game</title> 
<link href="bootstrap/css/bootstrap.css" rel="stylesheet"> 
<link href="tagmanager/bootstrap-tagmanager.css" rel="stylesheet"> 
<link href="game.css" rel="stylesheet">

<?php include "get_leaders.php"; ?>

</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">Dr. Watson</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li ><a href="index.php">Home</a></li>
              <li ><a href="game.php">Game</a></li>
              <li class="active"><a href="#">
              High Scores</a></li>
              <li><a href="#about">About</a></li>
            </ul>
            
            <ul class="nav pull-right">
              <li class="navmsg">Hi <span class="navimp" id="navuser"></span>! You scored: <span class="navimp" id="navscore"> </span> points</li>
              <li><a href="#" id="notifPopover" rel="popover" data-content="<?php echo $pop_text; ?>"><img src="img/red.png" width="25" height="25"><img src="img/blue.png" width="20" height="20"></a></li>
              <li><a href="login.php">(logout)</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
   </div>
   
	<div class="container">
		<div class="row"><br><br><br>
			<div class="span12">
				<table class="table table-striped">
					<thead>  
		        <tr>  
		          <th>#</th>  
		          <th>Username</th>  
		          <th>Score</th>
		        </tr>  
		      </thead>
		      <tbody>
		      	<?php echo $table; ?>
		      </tbody>
				</table>
			</div>
		</div>
  </div>

	<script src="jquery-2.0.2.min.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>    
	<script type="text/javascript">
			var userName = "<?php echo $wid; ?>";
			$("#navuser").text(userName);
			
			var currScore = <?php echo $current_score ; ?>;
			$("#navscore").text(currScore.toFixed(1));
			
			$(function () {
				$("#notifPopover").popover({trigger: 'hover', placement:'bottom', html : true });  
			}); 

	</script>
	
</body>
</html>
