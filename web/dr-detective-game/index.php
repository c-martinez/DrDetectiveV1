<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="utf-8"> 
<title>Dr. Detective Game</title> 
<link href="bootstrap/css/bootstrap.css" rel="stylesheet"> 
<link href="tagmanager/bootstrap-tagmanager.css" rel="stylesheet"> 
<link href="game.css" rel="stylesheet">

<?php include "game_options.php"; ?>

</head>
<body>

<?php include "header.php"?>
   
   <div class="container" style="float: none; margin: 0 auto;">
   
   <div class="row">
   	<div class="span12">br><br><br>
   		<h3>Welcome to the <span class="important">Dr. Detective</span> game!</h3>
   		
   		<h4>
   		Read medical case reports, find clues for diagnosing patients, and get points for:<br/><br/>
   		<ul>
   			<li>the difficulty of the case</li><br/>
   			
   			<li>playing consecutive games</li><br/>

   			<li>picking a popular clue </li><br/>

   			<li>someone else picking a clue you discovered</li><br/>
   		</ul>
   		
   		</h4>
   		
   		<form action="game.php">
   			<button class="btn btn-large btn-primary">Start Game</button>
   		</form>
   		
   	</div>
   </div>
   

	<script src="jquery-2.0.2.min.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>    
	<script type="text/javascript">
			var userName = "<?php echo $wid; ?>";
			$("#navuser").text(userName);
			
			var currScore = <?php echo $current_score ; ?>;
			$("#navscore").text(currScore.toFixed(1));
			
			var domainsArray = Array("Hematology/Oncology", "Nephrology", "Primary Care/Hospitalist/Clinical Practice", "Viral Infections");
			var domainsSolved = Array(false, false, false, false);
			
			$(function () {
				$("#notifPopover").popover({trigger: 'hover', placement:'bottom', html : true });  
			}); 
	</script>
	
	<?php
		echo "<script type=\"text/javascript\">\n";
		echo "var totDom = Array(";
		for ($i = 0; $i < 4; $i++) {
			if ($i > 0) echo ",\n";
			echo "Array(";
			for ($j = 0; $j < 3; $j++) {
				if ($j > 0) echo ", ";
				echo "'".$domain_count[$i][$j]."'";
			}
			echo ")";
		}
		echo ");\n";
		
		echo "var solvedDom = Array(";
		for ($i = 0; $i < 4; $i++) {
			if ($i > 0) echo ",\n";
			echo "Array(";
			for ($j = 0; $j < 3; $j++) {
				if ($j > 0) echo ", ";
				echo "'".$domain_count_solved[$i][$j]."'";
			}
			echo ")";
		}
		echo ");\n";
		echo "</script>";
	?>
	
	
</body>
</html>
