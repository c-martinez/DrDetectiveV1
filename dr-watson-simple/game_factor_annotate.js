$(document).ready(function () {  
	$('.dropdown-toggle').dropdown(); 
	submission = {};
	
	
	text = document.createElement("span");
	text.setAttribute("id", "selectedWords");
	text.setAttribute("class", "label");
	$("selectedWords").css("display","none");
	document.getElementById("listPar").appendChild(text);
	document.getElementById("selectedWords").disabled = true;
	
	createFactorDialog();
	factorTypeSelect(0);
	
	timeStart = new Date();
	
	$('#listButton').html("Save term");
	//toggleMostPopular();
	$("#mostPopular").css("display","none");
	
});

if (par_id == -1) {
		//alert("Game Over");
		$('#overModal').modal();
	}

/*
** Word Highlighting
*/
if (enableHighlighting == true) {      
  
	$("#par span").mouseover(function(){
		if (alreadySubmitted() == false) {
			if($(this).css("background-color") == colorMap["white"] ||
			$(this).css("background-color") == colorMap["blue"] ||
			$(this).css("background-color") == colorMap["yellow"]) {
				//highlight a word when hovered 
				$(this).css("background-color", colorMap["gray"]);
			}
		}
	});
				
	$("#par span").mouseout(function(){
		if (alreadySubmitted() == false) {

			if($(this).css("background-color") == colorMap["gray"]) {
					$(this).css("background-color","white");
					
					if (toggleOtherAnnotations == true) {
						for (var i = 0; i < otherAnnMap[fType].length; i++) {
							var j = $.inArray($(this).attr("id").toString(), otherAnnMap[fType][i]);
							if (j > -1) $(this).css("background-color", colorMap["blue"]);
						}
					}
					
					for (var i = 0; i < selectedWords.length; i++) {
						if (selectedWords[i].attr("id") == $(this).attr("id")) {
							$(this).css("background-color", colorMap["yellow"]);
						}
					}
			}

			getHighlightedWords();
		}
	});
				
	$("#par span").click(function(event) {
		if (alreadySubmitted() == false) {
			if($(this).css("background-color") == colorMap["blue"] ||
			$(this).css("background-color") == colorMap["white"] ||
			$(this).css("background-color") == colorMap["gray"] ||
			$(this).css("background-color") == colorMap["yellow"]) {
					event.stopPropagation();
					
					var found = -1;
					for (var i = 0; i < selectedWords.length; i++) {
						if (selectedWords[i].attr("id") == $(this).attr("id")) {
							found = i;
						}
					}
					
					if (found == -1) {
						selectedWords.push($(this));
						$(this).css("background-color", colorMap["yellow"]);
					}
					else {
						selectedWords.splice(found, 1);
						
						$(this).css("background-color","white");
						
						if (toggleOtherAnnotations == true) {
							for (var i = 0; i < otherAnnMap[fType].length; i++) {
								var j = $.inArray($(this).attr("id").toString(), otherAnnMap[fType][i]);
								if (j > -1) $(this).css("background-color", colorMap["blue"]);
							}
						}
					}
					getHighlightedWords();
			}
		}
	});
}


function clearAnswers() {
	jQuery(".tm-input").tagsManager('empty');
	emptyArrays();
	
	$("#par").find("span").css("background-color","white");
	jQuery(".tm-input").tagsManager('empty');
	changeSelectedFactorType(saveIndex);
	hideOtherSubmissions();
	
	$('#clearFactorsModal').modal('hide');

	if (alreadySubmitted() == true) {
		document.getElementById("listButton").disabled = true;
		document.getElementById("submitAnswersButton").disabled = true;
		loadExistingAnn(submission[fType].factorPos);
	}
	$(".submitTaskButton").text(fType);
	colorOtherFactors();
}
						
function factorTypeSelect(index) {
	saveIndex = index;
	
	if (factors.length > 0) {
		$('#clearFactorsModal').modal();
	} else {
		jQuery(".tm-input").tagsManager('empty');
		emptyArrays();
		$("#par span").css("background-color", "white");
		document.getElementById("listButton").disabled = false;
		document.getElementById("submitAnswersButton").disabled = false;
		
		changeSelectedFactorType(index);
		
		hideOtherSubmissions();
	}

	if (alreadySubmitted() == true) {
		document.getElementById("listButton").disabled = true;
		document.getElementById("submitAnswersButton").disabled = true;
		loadExistingAnn(submission[fType].factorPos);
	}
	$(".submitTaskButton").text(fType);
	colorOtherFactors();
}
				
function listProcess() {
	if (selectedWords.length == 0) {
		alert("You have not selected any words!");
	} else {
		var factor = "";
		//alert(selectedWords[0].attr("id"));
		selectedWords.sort(function(a, b){
			var nra = parseInt(a.attr("id"));
			var nrb = parseInt(b.attr("id"));
			if (nra < nrb) return -1;
			if (nra > nrb) return 1;
			return 0;
		});
						
		var factorArr = new Array();
		var factorNumArr = new Array();
						
		for (var i = 0; i < selectedWords.length; i++) {
			if (i > 0) factor = factor + " ";
			factor = factor + selectedWords[i].text();
			
			selectedWords[i].css("background-color", colorMap[fType]);
			if (toggleOtherAnnotations == true) {
				for (var j = 0; j < otherAnnMap[fType].length; j++) {
					for (var k = 0; k < otherAnnMap[fType][j].length; k++) {
						if (otherAnnMap[fType][j][k] == selectedWords[i].attr("id")) {
							selectedWords[i].css("background-color", colorMap["purple"]);
						}
					}
				}
			}
			
							
			//factorArr.push(selectedWords[i].text());
			factorNumArr.push(selectedWords[i].attr("id"));
			// push time
		}
						
		factors.push(selectedWords);
		var factorCreationTime = new Date();
						
		factorNumbers.push(factorNumArr);
		factorTimes.push(factorCreationTime.getTime().toString());
		factorExpl.push(factor);
						
		//alert(factor);
		//$("#tagList").attr("value", factor);
		//alert($("#tagList").attr("value"));
		//push_tag(factor);
		jQuery(".tm-input").tagsManager('pushTag',factor);
		document.getElementById("selectedWords").innerHTML = "";
						
		// clean up fType factor
		//$("#par").find("span").css("background-color","white");
		selectedWords = new Array();
	}
}

/**
  * save answers for current typing
  */
function submitAnswers() {
	var fPos = new Array();
	for (var i = 0; i < factors.length; i++) {
		var w = new Array();
		for (var j = 0; j < factors[i].length; j++) {
			w.push(factors[i][j].attr("id")); 
		}
		fPos.push(w);
	}
	
	var Item = makeStruct("timeStart timeCreate factorPos factorExpl factorTimes");
	timeCreate = new Date();
	var row = new Item(timeFactorStart.getTime().toString(),
										 timeCreate.getTime().toString(),
										 fPos,
										 factorExpl.slice(0),
										 factorTimes.slice(0));
	submission[fType] = row;
	
	// disable buttons
	emptyArrays();
	document.getElementById("listButton").disabled = true;
	document.getElementById("submitAnswersButton").disabled = true;
//	loadExistingAnn(fPos);
//	toggleMostPopular();
	//showOtherSubmissions();
	$(".tm-tag-remove").css("display", "none");
	
	//alert(fType);
	var ft = fType.replace(' ', '-').replace('/', '-');
	$("#" + ft).css("text-decoration", "line-through");
	
//	getCorrectAnswers();
	//alert(answScore);
}

/**
  * move to next case report
  */
function nextPatient(url) {
	// time forms
	timeCreate = new Date();
	$("#timeStartForm").attr("value", timeStart.getTime().toString());
	$("#timeCreateForm").attr("value", timeCreate.getTime().toString());
	$("#scoreForm").attr("value", docScore * consScore);
	$("#nextURL").attr("value", url);
	
	// answer forms
	var json = JSON.stringify(submission);
	document.getElementById('resultsForm').setAttribute('value', json);
	
	//alert(url);
	//alert($('#submitAnswer').attr("action"));
	
	document.getElementById("submitAnswer").submit();
	//document.forms[0].submit();
}

/**
  * toggle the submissions of the other users for this task
  */
function showOtherSubmissions(submitted) {
//	alert(otherAnnMap[factorTypes[fType]].length);
//	alert(submitted);
	//toggleOtherAnnotations = true;
//	document.getElementById("otherSubmissions").disabled = true;
	
	/*var allAnn = new Array();
	
	for (var i = 0; i < otherAnnMap[fType].length; i++) {	
		var word = "";	
		for (var j = 0; j < otherAnnMap[fType][i].length; j++) {
			$("#par").find("span[id='" + otherAnnMap[fType][i][j] + "']").each(function() {
				if($(this).css("background-color") != colorMap["white"] &&
				$(this).css("background-color") != colorMap["blue"]) {
					$(this).css("background-color", colorMap["purple"]);
				}
				else {
					$(this).css("background-color", colorMap["blue"]);
				}
				if (word != "") word = word + " ";
				word = word + $(this).text();
			});
		}
		allAnn.push(word);
	}
	
	for (var i = 0; i < selectedWords.length; i++) {
		selectedWords[i].css("background-color", colorMap["white"]);
	}
	selectedWords = new Array();
	// alert(factorTypes[fType] + " : " + otherAnnMap[factorTypes[fType]][1]);
	
	
	$("#userAnswersTagList").attr("class", "span6");
	
	$("#answersTagList").find("#allAnn").remove();
	var div = document.createElement("span");
	div.setAttribute("id", "allAnn");
	//div.innerHTML = "Saved by others: ";
	document.getElementById("answersTagList").appendChild(div);
	
	for (var i = 0; i < allAnn.length; i++) {
		$("#answersTagList").find("#otherTag" + i).remove();
		
		text = document.createElement("span");
		text.setAttribute("class", "tm-tag");
		text.setAttribute("id", "otherTag" + i);
		text.innerHTML = allAnn[i];
		if (submitted == true) {
			text.innerHTML += " (" + popVals[fType][i] + ")";
			//alert( popVals["Factors"][0] + " " + fType);
		}
		document.getElementById("allAnn").appendChild(text);
		
		$("#allAnn").find("#otherTag" + i).css("background-color", colorMap["blue"]);	
	}*/
	
}

function hideOtherSubmissions() {
	$("#userAnswersTagList").attr("class", "span12");
	$("#allAnn").remove();
}

/**
  * empty all the arrays, reset the form
  */
function emptyArrays() {
	for (var i = 0; i < selectedWords.length; i++) { later
		selectedWords[i].css("background-color", colorMap["white"]);
	}

	factors.length = 0;		
	removedFactors.length = 0;
	removedFactorsExpl.length = 0;
	factorNumbers.length = 0;
	factorTimes.length = 0;
	factorExpl.length = 0;
	selectedWords.length = 0;
	
	document.getElementById("selectedWords").innerHTML = "";
	//jQuery(".tm-input").tagsManager('empty');
	
	toggleOtherAnnotations = false;
	
	//document.getElementById("otherSubmissions").disabled = false;
	$("#mostPopular").css("display","none");
}

/**
  * stores the highlighted words in a label
  */
function getHighlightedWords() {

	document.getElementById("selectedWords").innerHTML = "";
	
	if (selectedWords.length > 0) {
			$("selectedWords").css("display","inline-block");
	} else {
		$("selectedWords").css("display","none");
	}
	
	selectedWords.sort(function(a, b){
		var nra = parseInt(a.attr("id"));
		var nrb = parseInt(b.attr("id"));
		if (nra < nrb) return -1;
		if (nra > nrb) return 1;
		return 0;
	});
		
	for (var i = 0; i < selectedWords.length; i++) {
		document.getElementById("selectedWords").innerHTML += selectedWords[i].text() + " ";
	}
}

/**
  * loads existing annotation by the current user
  */
function loadExistingAnn(fPos) {
	for (var i = 0; i < fPos.length; i++) {
		var fact = "";
		var wordList = new Array();
		
		for (var j = 0; j < fPos[i].length; j++) {
			if (j > 0) fact += " ";
			$("#par").find("span[id='" + fPos[i][j] + "']").each(function() {
				fact += $(this).text();
				wordList.push($(this));
				$(this).css("background-color", colorMap[fType]);
			});
		}
		
		//alert(fact);
		if (fact != "") {
			jQuery(".tm-input").tagsManager('pushTag',fact);
			jQuery(".tm-tag").css("background-color", colorMap[fType]);
		}
	}
	
	//toggleMostPopular();
	$(".tm-tag-remove").css("display", "none");
}

function toggleMostPopular() {
	/*document.getElementById("mostPopular").innerHTML = "Most popular terms: ";

	for (var i = 0; i < popTerms[fType].length; i++) {		
		text = document.createElement("span");
		text.setAttribute("class", "tm-tag");
		text.setAttribute("id", "popTag" + i);
		text.innerHTML = popTerms[fType][i];
		document.getElementById("mostPopular").appendChild(text);
		$("#mostPopular").find("#popTag" + i).css("background-color", colorMap["yellow"]);	
	}
	
	$("#mostPopular").css("display","inline");*/
	//showOtherSubmissions(true);
	//$(".tm-tag-remove").css("display", "none");
}


function colorOtherFactors() {

	for (var cl in fTypes) {
		if (fTypes.hasOwnProperty(cl)) {
			
			for (var i = 0; i < fTypes[cl].length; i++) {
				var ft = fTypes[cl][i].replace(' ', '-').replace('/', '-'); 
				$("#" + ft).css("background-color", colorMap[fTypes[cl][i]]);
				
				if (fTypes[cl][i] in submission) {
					for (var j = 0; j < submission[fTypes[cl][i]].factorPos.length; j++) {
						for (var k = 0; k < submission[fTypes[cl][i]].factorPos[j].length; k++) {
							$("#par").find("span[id='" + submission[fTypes[cl][i]].factorPos[j][k] + "']").css("background-color", colorMap[fTypes[cl][i]]);
						}
					}
				}
			}
		}
	}

}

