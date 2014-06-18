<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="utf-8"> 
<title>Dr. Watson game mockup</title> 
<link href="bootstrap/css/bootstrap.css" rel="stylesheet"> 
<link href="tagmanager/bootstrap-tagmanager.css" rel="stylesheet"> 
<style>
.span12 h4{padding: 5px; font-weight: normal;}
.important {color:#FE6E4C; font-weight: bold; display: inline;}
.imgpop {display: inline; font-size:small;}
.imgpop a {color:#FE6E4C; font-weight: bold; display: inline; font-size:large;}
.accordion-heading {background-color: #f7f7f7; color:#696969; border-radius: 2px; padding: 5px;text-decoration:none; font-style:italic;}
.paragraph {font-family:serif;}
.selectedFactorType {display:inline;}
.selectedFactorClass {display:inline;}
.factorTypes {display:inline;}
.highlightedWord {font-family:serif; background-color: #FFCC99; display:inline;}
.accordion-inner {font-family:serif;}
</style>

<?php include 'mysql.php'; ?>
<?php include 'verbose.php'; ?>
<?php include 'submit.php'; ?>
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

	<script type="text/javascript">
   </script> 

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
      
<div class="container">

<div class="row">
<div class="span12">
<h4>
This patient is suffering from <div class="important"><?php echo $diagnosis; ?></div>.

<p></p>

In the text below,
<?php
if (strcmp($worker_role, "annotate") == 0) {
	echo "highlight";
} else {
	echo "other players have highlighted";
}
?>
<div class="important">
			<div class="btn-group btn-hover">
          <button class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            <div id="selectedFactorClass" class="selectedFactorClass"></div>
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" id="factorClasses">
          </ul>
        </div>
</div>
all the words that refer to
<div class="important">
			<div class="btn-group btn-hover">
          <button class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            <div id="selectedFactorType" class="selectedFactorType"></div>
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" id="factorTypes">
          </ul>
        </div>
</div>
<div class="imgpop"><a href="#" id="factorTypeHelp" rel="popover" data-original-title="What to look for:"><img src="img/question.png" width="15" height="15"></a></div>.</h4>

<p></p>

     <div class="accordion" id="accordion2">  
            <div class="accordion-group">  
              <div class="accordion-heading">  
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">  
                  Example annotation  
                </a>  
              </div>  
              <div id="collapseOne" class="accordion-body collapse" style="height: 0px; ">  
                <div class="accordion-inner"> 
                </div>  
              </div>  
            </div> 
      </div>
      <h4>  
		<?php
		if (strcmp($worker_role, "annotate") != 0) {
			echo "Checkup their solution, by removing the terms you think are incorrect from the list below.";
		}
		?>
     </h4>
</div>
</div>

<div class="row">
	<div class="span12">
	<div class="paragraph">
	<p id="par"><?php echo $text; ?> <span></span> </p> 
	<p id="listPar">
	<button class="btn btn-info" onclick="listProcess()" id="listButton"></button>
	</p> 
	</div>
	</div>
</div>

<div class="row">
	<div class="span12"></br>
	<p><input type="hidden" name="tags" placeholder="Tags" class="tm-input"/></p> 
	</div>
</div>

<div class="row">
	<div class="span12">
	<p id="paragraphButtons"></p>
	</div>
</div>


<div class="row"><br/>

<form id="submitAnswer" action="test.php?user=<?php echo $wid;?>&role=<?php echo $worker_role; ?>" method="POST">

<input type="hidden" name="worker_role" value="<?php echo $worker_role; ?>">

<input type="hidden" name="time_start" id="timeStartForm">
<input type="hidden" name="time_create" id="timeCreateForm">

<input type="hidden" name="factor_type" id="factorTypeForm">
<input type="hidden" name="factor_class" id="factorClassForm">
<input type="hidden" name="factor_count" id="factorCount">

<div class="span12" id="submitFormButtons">
<button class="btn btn-primary" onclick="submitAnswers();">Submit your answers</button>
<button class="btn" onclick="nextPatient()">Next patient &gt;&gt;</button></div>
<br><br>
</form>

</div>

	<script type="text/javascript">
			var selectedWords = new Array();
			var factors = new Array();
			var removedFactors = new Array();
			var removedFactorsExpl = new Array();
			var factorNumbers = new Array();
			var factorTimes = new Array();
			var factorExpl = new Array();
			
			var factorTypes = new Array();
			var factorTypesHelp = new Array();
			var factorTypesExample = new Array();
			// unselected factor types
			var otherFactorTypes = new Array();
			var otherFactorTypesHelp = new Array();
			var otherFactorTypesExample = new Array();
			
			var factorClasses = new Array();
			var selectedType = 0;
			var selectedClass = 0;
			var saveIndex = 0;
			var factorChangeGranularity = "";
			
			var timeStart;
			var timeCreate;
			var toggleOtherAnnotations = false;
			
			var workerRole = "<?php echo $worker_role; ?>";
	</script>
	
	<script src="jquery-2.0.2.min.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>
	<script src="tagmanager/bootstrap-tagmanager.js"></script>
	
	<?php
		echo "<script type=\"text/javascript\">\n";
		foreach ($factor_classes as $item) {
			echo "factorClasses.push('$item');\n";
			if (strcmp($item, $factor_class) != 0) {
				foreach ($available_factor_types[$item] as $jtem) {
					echo "factorTypes.push('$jtem');\n";
				}
				foreach ($available_factor_types_help[$item] as $jtem) {
					echo "factorTypesHelp.push('$jtem');\n";
				}
				foreach ($available_factor_types_example[$item] as $jtem) {
					echo "factorTypesExample.push('$jtem');\n";
				}
			}
		}
		foreach ($available_factor_types[$factor_class] as $item) {
			echo "otherFactorTypes.push('$item');\n";
		}
		foreach ($available_factor_types_help[$factor_class] as $item) {
			echo "otherFactorTypesHelp.push('$item');\n";
		}
		foreach ($available_factor_types_example[$factor_class] as $item) {
			echo "otherFactorTypesExample.push('$item');\n";
		}
		
		echo "var otherAnnMap = {";
		$i = 0;
		foreach ($factor_classes as $jtem) {
			foreach ($available_factor_types[$jtem] as $item) {
				if ($i > 0) echo ", ";
				echo "\"".$item."\" : Array(";
				$j = 0;
					
				// echo count($words_ann_other[$item]);
				
				foreach ($words_ann_other[$item] as $word_list) {
					if ($j > 0) echo ", ";
					echo "Array(";
					$k = 0;
					
					foreach ($word_list as $word) {
						if ($k > 0) echo ", ";
						echo "\"".$word."\"";
						$k = $k + 1;
					}
					echo ")";
					$j = $j + 1;
				}
				echo ")\n";
				
				$i = $i + 1;
			}
		}
		echo "};\n";
		
		echo "</script>\n";
		
		echo "<script src=\"game.js\"></script>";
		if (strcmp($worker_role, "annotate") == 0) {
			echo "<script src=\"game_factor_annotate.js\"></script>";
		} else {
			echo "<script src=\"game_factor_validate.js\"></script>";
		}
	?>
	
	

</body>
</html>
            
