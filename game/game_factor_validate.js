$(document).ready(function () {  
	$('.dropdown-toggle').dropdown(); 
	timeStart = new Date();
	submission = {};
	
	$('#listButton').html("Show what others have found");
	factorTypeSelect(0);
	
	/*var divu = document.createElement("div");
	divu.setAttribute("id", "userRes");
	divu.innerHTML = "Terms eliminated by you: ";
	document.getElementById("mostPopular").appendChild(divu);*/
	
	var divo = document.createElement("div");
	divo.setAttribute("id", "otherRes");
	divo.innerHTML = "Most popular eliminated terms: ";
	document.getElementById("mostPopular").appendChild(divo);
	
	$("#mostPopular").css("display","none");
	
	createFactorDialog();
	
	$("#validateUser").text("Eliminated by you: ");
	
	var vt = document.createElement("input");
	vt.setAttribute("name", "validateTags");
	vt.setAttribute("type", "hidden");
	vt.setAttribute("placeholder", "Tags");
	vt.setAttribute("class", "tm-input tm-input-error");
	vt.setAttribute("id", "validateTags");
	document.getElementById("validateUser").appendChild(vt);
	
	jQuery("#validateTags").tagsManager({hiddenTagListName: "validateTagsHidden"});
	colorOtherFactors();
});

function colorText() {
	// alert(fType);
	$("#par").find("span").css("background-color","white");
	
	for (var i = 0; i < factors.length; i++) {
		for (var j = 0; j < factors[i].length; j++) {
			factors[i][j].css("background-color", colorMap[fType]);
		}
	}
	//alert("Factors:" + factors.length);
}

function loadTagList() {
	for (var i = 0; i < otherAnnMap[fType].length; i++) {
		var fact = "";
		var wordList = new Array();
		for (var j = 0; j < otherAnnMap[fType][i].length; j++) {
			if (j > 0) fact += " ";
			$("#par").find("span[id='" + otherAnnMap[fType][i][j] + "']").each(function() {
				fact += $(this).text();
				wordList.push($(this));
			});
		}
		//alert(fact);
		if (fact != "") {
			if (alreadySubmitted() == false) {
				jQuery("#answerTags").tagsManager('pushTag',fact);
				factors.push(wordList);
				factorExpl.push(fact);
			} else {
				var found = false;
				for (var k = 0; k < submission[fType].factorExpl.length; k++) {
					if (submission[fType].factorExpl[k] == fact) {
						found = true;
						break;
					}
				}
				if (found == false) {
					jQuery("#answerTags").tagsManager('pushTag',fact);
					factors.push(wordList);
					factorExpl.push(fact);
				}
			}
		}
	}
	colorText();
}

/*
** Select a new class of factors
*/						
function factorTypeSelect(index) {
	saveIndex = index;
	factorChangeGranularity = "type";
	selectedWords.length = 0;
	
	if (removedFactors.length > 0) {
		$('#clearFactorsModal').modal();
	} else {
		emptyArrays();
		jQuery(".tm-input").tagsManager('empty');
		changeSelectedFactorType(index);
		
		$("#mostPopular").css("display","none");
	
		document.getElementById("listButton").disabled = false;
		document.getElementById("submit").disabled = false;
		loadTagList();
		$("#allAnn").remove();
		$("#validateUser").attr("class", "span12");
		jQuery("#validateTags").tagsManager('empty');
	
		if (alreadySubmitted() == true) {
	//		document.getElementById("listButton").disabled = true;
			document.getElementById("submit").disabled = true;
			loadExistingAnn(submission[fType].factorPos);
		}
		$(".submitTaskButton").text(fType);
	}
}


/*
** Clear all the current answers
*/
function clearAnswers() {
	emptyArrays();
	
	$("#par").find("span").css("background-color","white");
	jQuery(".tm-input").tagsManager('empty');
	changeSelectedFactorType(saveIndex);
	
	$('#clearFactorsModal').modal('hide');
	loadTagList();
		
	$("#mostPopular").css("display","none");
	
	document.getElementById("listButton").disabled = false;
	document.getElementById("submit").disabled = false;
	loadTagList();
	$("#allAnn").remove();
	$("#validateUser").attr("class", "span12");
	jQuery("#validateTags").tagsManager('empty');
	
	$(".submitTaskButton").text(fType);
	
	if (alreadySubmitted() == true) {
		document.getElementById("submit").disabled = true;
		loadExistingAnn(submission[fType].factorPos);
	}
	$(".submitTaskButton").text(fType);
}

				
function submitAnswers() {
	timeCreate = new Date();
	var fPos = new Array();
	for (var i = 0; i < removedFactors.length; i++) {
		var w = new Array();
		for (var j = 0; j < removedFactors[i].length; j++) {
			w.push(removedFactors[i][j].attr("id")); 
		}
		fPos.push(w);
	}
	
	var Item = makeStruct("timeStart timeCreate factorPos factorExpl factorTimes");
	timeCreate = new Date();
	var row = new Item(timeFactorStart.getTime().toString(),
										 timeCreate.getTime().toString(),
										 fPos,
										 removedFactorsExpl.slice(0),
										 factorTimes.slice(0));
	submission[fType] = row;
	
	// disable buttons
	emptyArrays();
	
	loadExistingAnn(fPos);
	//toggleMostPopular();
	
//	document.getElementById("listButton").disabled = true;
	document.getElementById("submit").disabled = true;
	
	$("#" + fType.replace(' ', '-')).css("text-decoration", "line-through");
}

function nextPatient() {
	// change input par
	// submitAnswers();
	timeCreate = new Date();
	$("#timeStartForm").attr("value", timeStart.getTime().toString());
	$("#timeCreateForm").attr("value", timeCreate.getTime().toString());
	
	// answer forms
	var json = JSON.stringify(submission);
	document.getElementById('resultsForm').setAttribute('value', json);
	
	document.getElementById("submitAnswer").submit();
}

function listProcess() {
	
	$("#validateUser").attr("class", "span6");
	
	var div = document.createElement("div");
	div.setAttribute("class", "span6");
	div.setAttribute("id", "allAnn");
	div.innerHTML = "Eliminated by others: ";
	document.getElementById("validateTagList").appendChild(div);
	
	for (var i = 0; i < otherValMap[fType].length; i++) {
		word = "";
		for (var j = 0; j < otherValMap[fType][i].length; j++) {
			$("#par").find("span[id='" + otherValMap[fType][i][j] + "']").each(function() {
				if (word != "") word += " ";
				word += $(this).text();
			});
		}
		text = document.createElement("span");
		text.setAttribute("class", "tm-tag");
		text.innerHTML = word;
		text.setAttribute("id", "otherTag" + i);
		document.getElementById("allAnn").appendChild(text);
		$("#allAnn").find("#otherTag" + i).css("background-color", colorMap["blue"]);	
	}
	
	document.getElementById("listButton").disabled = true;
}


function emptyArrays() {
	factors.length = 0;		
	removedFactors.length = 0;
	removedFactorsExpl.length = 0;
	factorNumbers.length = 0;
	factorTimes.length = 0;
	factorExpl.length = 0;
	selectedWords.length = 0;
	//jQuery(".tm-input").tagsManager('empty');
}

function loadExistingAnn(fPos) {
	//$("#mostPopular").css("display","inline");
//	document.getElementById("userRes").innerHTML = "Terms eliminated by you: ";
	
	for (var i = 0; i < fPos.length; i++) {
		var fact = "";
		var wordList = new Array();
		
		for (var j = 0; j < fPos[i].length; j++) {
			if (j > 0) fact += " ";
			$("#par").find("span[id='" + fPos[i][j] + "']").each(function() {
				fact += $(this).text();
				wordList.push($(this));
			});
		}
	}
	
	for (var i = 0; i < submission[fType].factorExpl.length; i++) {
		var fact = "";
		jQuery("#validateTags").tagsManager('pushTag',submission[fType].factorExpl[i]);
	}
	
	$(".tm-tag-remove").css("display", "none");
	toggleMostPopular();
}

function toggleMostPopular() {
	document.getElementById("otherRes").innerHTML = "Most popular eliminated terms: ";

	for (var i = 0; i < popTerms[fType].length; i++) {		
		text = document.createElement("span");
		text.setAttribute("class", "tm-tag");
		text.innerHTML = popTerms[fType][i];
		text.setAttribute("id", "popTag" + i);
		document.getElementById("otherRes").appendChild(text);
		$("#mostPopular").find("#popTag" + i).css("background-color", colorMap["yellow"]);	
	}
	
	$("#mostPopular").css("display","inline");
}

function colorOtherFactors() {

	for (var cl in fTypes) {
		if (fTypes.hasOwnProperty(cl)) {
			
			for (var i = 0; i < fTypes[cl].length; i++) {
				var ft = fTypes[cl][i].replace(' ', '-').replace('/', '-'); 
				$("#" + ft).css("background-color", colorMap[fTypes[cl][i]]);
			}
		}
	}

}


