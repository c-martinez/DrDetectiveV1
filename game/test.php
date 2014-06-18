<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="utf-8"> 
<title>Dr. Watson game mockup</title> 
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
              <li><a href="#about">Home</a></li>
              <li class="active"><a href="#">Game</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
   </div>
      
<div class="container">

<div class="row">
<div class="span12">
<h4> <br/><br/>
This patient is suffering from <div class="important"><?php echo $diagnosis; ?></div>.

<p></p>

In the text below,
<?php
if (strcmp($fac_or_rel, "factors") == 0) {
	if (strcmp($worker_role, "annotate") == 0) {
		echo "highlight";
	} else {
		echo "other players have highlighted";
	}
	echo " all the terms that refer to";
}
else {
	if (strcmp($worker_role, "annotate") == 0) {
		echo "pick";
	} else {
		echo "other players have picked";
	}
	echo " all the relations between the terms: <span class='important' id='term1'></span> and <span class='important' id='term2'></span>.";
}
?>

<div class="dial">
			<div class="btn-group btn-hover">
          <button class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            <div id="selectedFactorDropdown" class="selectedFactorDropdown"></div>
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" id="factorTypesDropdown">
          </ul>
        </div>
<div class="imgpop"><a href="#" id="factorTypeHelp" rel="popover"><img src="img/question.png" width="15" height="15"></a></div></div><?php 
if (strcmp($fac_or_rel, "factors") == 0) echo  ", that $relation_roles[$rel_role].";
?></h4>
      <h4>  
		<?php
		if (strcmp($fac_or_rel, "factors") == 0) {
			if (strcmp($worker_role, "annotate") != 0) {
				echo "Checkup their solution, by removing the terms you think are incorrect from the list below.";
			}
		}
		?>
     </h4>
</div>
</div>

<div class="row">
	<div class="span12">
	<div class="paragraph">
	<p id="par"><?php echo $text; ?> <span></span> </p> 
	<p id="paragraphButtons"></p>
	<p id="listPar">
	<button class="btn btn-info" onclick="listProcess()" id="listButton"></button>
	</p> 
	</div>
	</div>
</div>

<div class="row" id="answersTagList">
	<div id="userAnswersTagList" class="span12">
	<p>
	<?php
	if (strcmp($fac_or_rel, "factors") == 0) {
		if (strcmp($worker_role, "annotate") == 0) {
			echo "Saved by you: ";
		} else {
			echo "Saved by others: ";
		}
	}
	else {
		if (strcmp($worker_role, "annotate") == 0) {
			echo "Saved by you: ";
		} else {
			echo "Saved by others: ";
		}
	}
	?>
	<span class="relDialog"></span>
	<input type="hidden" name="answerTags" placeholder="Tags" class="tm-input" id="answerTags"/></p> 
	</div>
</div>

<div class="row" id="validateTagList">
	<div class="span12" id ="validateUser">
	</div>
</div>

<div class="row">
	<div class="span12" id ="mostPopular">
	</div>
</div>


<div class="row"><br/>
<div class="span12" id="submitFormButtons">

<form id="submitAnswer" action="submit.php?user=<?php echo $wid;?>&task=<?php echo $fac_or_rel;?>&role=<?php echo $worker_role; ?>" method="POST">

<input type="hidden" name="worker_role" value="<?php echo $worker_role; ?>">
<input type="hidden" name="relation_role" value="<?php echo $rel_role; ?>">
<input type="hidden" name="pid" value="<?php echo $pid; ?>">
<input type="hidden" name="time_start" id="timeStartForm">
<input type="hidden" name="time_create" id="timeCreateForm">
<input type="hidden" name="fac_rel" value="<?php echo $fac_or_rel;?>">
<input type="hidden" name="results" id="resultsForm">

<button class="btn btn-primary" onclick="submitAnswers(); return false;" id="submit">
Submit your answers for <div class="submitTaskButton"></div></button>
<button class="btn" onclick="nextPatient()">Next diagnosis &gt;&gt;</button>
<br><br>
</form>
</div>
</div>

	<script type="text/javascript">
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
	</script>
	
	<script src="jquery-2.0.2.min.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>
	<script src="tagmanager/bootstrap-tagmanager.js"></script>
	<script src="dropdown.js"></script>
	
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
            
