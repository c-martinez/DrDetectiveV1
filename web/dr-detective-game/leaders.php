<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="utf-8"> 
<title>Dr. Detective Game</title> 
<link href="bootstrap/css/bootstrap.css" rel="stylesheet"> 
<link href="tagmanager/bootstrap-tagmanager.css" rel="stylesheet"> 
<link href="game.css" rel="stylesheet">

<?php include "get_leaders.php"; ?>

</head>
<body>

<?php include "header.php";?>
   
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
