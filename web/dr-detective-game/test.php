<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="utf-8"> 
<title>Dr. Detective - Text Annotation Tool</title> 
<link href="bootstrap/css/bootstrap.css" rel="stylesheet"> 
<link href="tagmanager/bootstrap-tagmanager.css" rel="stylesheet"> 
<link href="game.css" rel="stylesheet"> 

<?php include 'get_data.php'; ?>

</head>
<body>

<div id="clearFactorsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="clearFactorsLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="clearFactorsModalLabel">Wait a sec</h3>
  </div>
  <div class="modal-body">
    <p>You've started working on this task, but have not saved your answers. Moving on means losing your work, are you sure you want to proceed?</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Go Back</button>
    <button class="btn btn-primary" onclick="clearAnswers()">Discard Answers</button>
  </div>
</div>

<div id="answersModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="answersModal" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="clearFactorsModalLabel">Solution</h3>
  </div>
  <div class="modal-body">
    <p id="feedbackAnswers"></p>
  </div>
</div>

<div id="overModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="overModal" aria-hidden="true">
  <div class="modal-header">
    <h3 id="overModalLabel">Game Over</h3>
  </div>
  <div class="modal-body">
    You have completed all the tasks.
    <button class="btn btn-primary" onclick="document.location.href='game.php'">OK</button>
  </div>
</div>

   <?php include "header.php"?>
      
<div class="container">

<div class="row">
<div class="span12">
<h4> <br/><br/><br/>
In the following text, find all the clues that could help diagnose <span class="important"><?php echo $diagnosis; ?></span>.
</h4>
</div>
</div>

<div class="row">
<div class="span3">
<h5>
<b>Step 1:</b> Select the type of clue you are looking for.
</h5>
</div>

<div class="span9">

<div class="dial">
			<div class="btn-group btn-hover">
          <button class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            <div id="selectedFactorDropdown" class="selectedFactorDropdown"></div>
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" id="factorTypesDropdown">
          </ul>
        </div>
<div class="imgpop"><a href="#" id="factorTypeHelp" rel="popover"><img src="img/question.png" width="15" height="15"></a></div></div></h5>

      <h5>  
		<?php
		if (strcmp($fac_or_rel, "factors") == 0) {
			if (strcmp($worker_role, "annotate") != 0) {
				echo "Checkup their solution, by removing the terms you think are incorrect from the list below.";
			}
		}
		?>
</div>
</div>

<div class="row">
	<div class="span3">
	<h5>
		<?php 
		if (strcmp($fac_or_rel, "factors") == 0) 
			if (strcmp($worker_role, "annotate") == 0) 
			echo  "<b>Step 2:</b> To pick a clue, highlight all the words that describe it by clicking on them. <span class='imgpop'><a href='#' id='factorTypeEx' rel='popover'><img src='img/question.png' width='15' height='15'></a><span>";
		?>
	</h5>
	</div>
	
	<div class="span9">
	<div class="paragraph">
	<p id="par"><?php echo $text; ?> <span></span> </p> 
	</div>
	</div>
</div>

<div class="row">	
	<div class="span3">
	</div>
	<div class="span9">
	<button class="btn" onclick="showOtherSubmissions(false); return false;" id="otherSubmissions">Show clues by others</button>
	<span id="answersTagList" class="tagsOthers">
	</span>
	</div>
</div>
	


<div class="row">	<br/>
	<div class="span3">
	<h5>
		<?php 
		if (strcmp($fac_or_rel, "factors") == 0) 
			if (strcmp($worker_role, "annotate") == 0) 
			echo  "<b>Step 3:</b> After all the words in the clue are highlighted, save the clue.";
		?>
	</h5>
	</div>
	<div class="span9" id="listButtonSpan">
	<p id="listPar">
	<button class="btn" onclick="listProcess()" id="listButton"></button> 
	<input type="hidden" name="answerTags" placeholder="Tags" class="tm-input" id="answerTags"/>
	</p> 
	</div>
</div>

<form id="submitAnswer" action="submit.php?task=<?php echo $fac_or_rel;?>&role=<?php echo $worker_role; ?>" method="POST">

<div class="row">
<div class="span3">
	<h5>
		<?php 
		if (strcmp($fac_or_rel, "factors") == 0) 
			if (strcmp($worker_role, "annotate") == 0) 
			echo  "<b>Step 4:</b> After you found all the clues for <span id='ftype'></span>, submit them.";
		?>
	</h5>
</div>

<div class="span9" id="submitFormButtons">
<br/>

<input type="hidden" name="worker_role" value="<?php echo $worker_role; ?>" />
<input type="hidden" name="relation_role" value="<?php echo $rel_role; ?>" />
<input type="hidden" name="pid" value="<?php echo $pid; ?>" />
<input type="hidden" name="domains" value="<?php echo $domains; ?>" />
<input type="hidden" name="time_start" id="timeStartForm" />
<input type="hidden" name="time_create" id="timeCreateForm" />
<input type="hidden" name="fac_rel" value="<?php echo $fac_or_rel;?>" />
<input type="hidden" name="results" id="resultsForm" />
<input type="hidden" name="score" id="scoreForm" />
<input type="hidden" name="nextURL" id="nextURL" />

<button class="btn" onclick="submitAnswers(); return false;" id="submitAnswersButton">
Submit your clues for <div class="submitTaskButton"></div></button>

<br><br>

</div>
</div>

<div class="row">
<div class="span3">
	<h5>
		<?php 
		if (strcmp($fac_or_rel, "factors") == 0) 
			if (strcmp($worker_role, "annotate") == 0) 
			echo  "<b>Step 5:</b> Go back to step 1 and select another clue type, or move on to the next diagnosis.";
		?>
	</h5>
</div>

<div class="span9"> <br/>
<button class="btn" onclick="nextPatient('submit');">Next diagnosis &gt;&gt;</button>
</div>
</div>


</form>

	
	<script src="jquery-2.0.2.min.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>
	<script src="tagmanager/bootstrap-tagmanager.js"></script>
	<script src="dropdown.js"></script>

	<script type="text/javascript">
			var userName = "<?php echo $wid; ?>";
			$("#navuser").text(userName);
			
			var currScore = <?php echo $current_score ; ?>;
			$("#navscore").text(currScore.toFixed(1));
			var docScore = <?php echo $doc_score ; ?>;
			var answList = new Array();
			var answVal = new Array();
			var answScore = 0;
			var consScore = <?php echo $consecutive ; ?>;
	
			var selectedWords = new Array();
			
			var enableHighlighting = true;
			var submission;
			
			var factors = new Array();
			var relations = new Array();
			var removedRelations = new Array();
			var removedFactors = new Array();
			var removedFactorsExpl = new Array();
			var factorNumbers = new Array();
			var factorTimes = new Array();
			var factorExpl = new Array();
			
			var timeStart;
			var timeFactorStart;
			var toggleOtherAnnotations = false;
			
			var workerRole = "<?php echo $worker_role; ?>";
			var fr = "<?php echo $fac_or_rel; ?>";
			var par_id = <?php echo $pid; ?>;
			
			var fTypes;
			var fTypesHelp;
			var fTypesExample;
			var popTerms;
			var fInd = 0;
			var fType = "";
			var fClass = "";
			
			var term1 = new Array();
			var term1Expl = "";
			var term2 = new Array();
			var term2Expl = "";
			
			var otherButtonToggle = "-1";
			
			$(function () {
				$("#notifPopover").popover({trigger: 'hover', placement:'bottom', html : true });  
			});
			
			var pop_text = "<?php echo $pop_text; ?>";
			
			var plost = <?php echo $points_lost;?>;	
			var pgained = <?php echo $points_gained;?>;
	</script>
	
	<?php
		if (strcmp($fac_or_rel, "factors") == 0) {
			include "setup_factors.php";
		}
		else {
			include "setup_relations.php";
		}
	?>
</body>
</html>
            
