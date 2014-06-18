$(document).ready(function () {  
	$('.dropdown-toggle').dropdown(); 
	
	// add button to toggle other submissions
	button = document.createElement("button");
	button.setAttribute("id", "otherSubmissions");
	button.setAttribute("class", "btn btn-info");
	button.setAttribute("onclick", "showOtherSubmissions(false); return false;");
	button.innerHTML = "Show what others have found";
	document.getElementById("paragraphButtons").appendChild(button);
	
	createFactorDialog();
	factorTypeSelect(0);
	
	$(".dial").appendTo(".relDialog");
	
	timeStart = new Date();
	$("#mostPopular").css("display","none");
	
	$("#term1").text(term1Expl);
	$("#term2").text(term2Expl);
});

function factorTypeSelect(index) {
	saveIndex = index;
	
	/*if (factors.length > 0) {
		$('#clearFactorsModal').modal();
	} else {
		emptyArrays();
		$("#par span").css("background-color", "white");
		document.getElementById("showOtherSubmissionslistButton").disabled = false;
		document.getElementById("submit").disabled = false;
		
		changeSelectedFactorType(index);
	}

	if (alreadySubmitted() == true) {
		document.getElementById("listButton").disabled = true;
		document.getElementById("submit").disabled = true;
		loadExistingAnn(submission[fType].factorPos);
	}*/
	changeSelectedFactorType(index);
	
	otherButtonToggle = "-1";
	
	if (alreadySubmitted() == false) {
		if (fType != "") {
			if ($("#" + fType).css("text-decoration") != "line-through") {
				jQuery(".tm-input").tagsManager('pushTag',fType);
				relations.push(fType);
				timeCreate = new Date();
				factorTimes.push(timeCreate.getTime());
				$("#" + fType).css("text-decoration", "line-through");
			}
		}
	}
}

function submitAnswers() {
	var Item = makeStruct("timeStart timeCreate relations relationTimes otherButtonToggle");
	timeCreate = new Date();
	var row = new Item(timeStart.getTime().toString(),
										 timeCreate.getTime().toString(),
										 relations.slice(0),
										 factorTimes.slice(0),
										 otherButtonToggle);
	submission = row;
	
	getCorrectAnswers();
	toggleMostPopular();
}

function toggleMostPopular() {
	/*document.getElementById("mostPopular").innerHTML = "Most popular relations: ";
	$("#mostPopular").css("display","inline");
	document.getElementById("submitAnswersButton").disabled = true;
	$(".tm-tag-remove").css("display", "none");
	
	for (var i = 0; i < popTerms.length; i++) {
		text = document.createElement("span");
		text.setAttribute("class", "tm-tag");
		text.setAttribute("id", "popTag" + i);
		text.innerHTML = popTerms[i];
		document.getElementById("mostPopular").appendChild(text);
		$("#mostPopular").find("#popTag" + i).css("background-color", colorMap["yellow"]);
	}*/
	
	document.getElementById("submitAnswersButton").disabled = true;
	$(".tm-tag-remove").css("display", "none");
	showOtherSubmissions(true);
}

function nextPatient(url) {
	timeCreate = new Date();
	$("#timeStartForm").attr("value", timeStart.getTime().toString());
	$("#timeCreateForm").attr("value", timeCreate.getTime().toString());
	$("#scoreForm").attr("value", currScore + docScore);
	$("#nextURL").attr("value", url);
	
	var input1 = document.createElement("input");
	input1.setAttribute("name", "term1");
	input1.setAttribute("type", "hidden");
	input1.setAttribute("value", t1);
	document.getElementById("submitAnswer").appendChild(input1);
	
	var input2 = document.createElement("input");
	input2.setAttribute("name", "term2");
	input2.setAttribute("type", "hidden");
	input2.setAttribute("value", t2);
	document.getElementById("submitAnswer").appendChild(input2);
	
	// answer forms
	var json = JSON.stringify(submission);
	document.getElementById('resultsForm').setAttribute('value', json);
	
	document.getElementById("submitAnswer").submit();
}

function showOtherSubmissions(submitted) {
	$("#userAnswersTagList").attr("class", "span6");
	
	$("#allAnn").remove();
	var div = document.createElement("div");
	div.setAttribute("class", "span6");
	div.setAttribute("id", "allAnn");
	div.innerHTML = "Relations selected by others: ";
	document.getElementById("answersTagList").appendChild(div);
	
	for (var i = 0; i < allAnn.length; i++) {
		text = document.createElement("span");
		text.setAttribute("class", "tm-tag");
		text.setAttribute("id", "otherTag" + i);
		text.innerHTML = allAnn[i];
		if (submitted == true) {
			text.innerHTML += " (" + popVals[i] + ")";
		}
		document.getElementById("allAnn").appendChild(text);
		$("#allAnn").find("#otherTag" + i).css("background-color", colorMap["blue"]);	
	}
	document.getElementById("otherSubmissions").disabled = true;
	var dt = new Date();
	otherButtonToggle = dt.getTime.toString();
}

function getCorrectAnswers() {
	var all = [];
	
	for (var i = 0; i < popTerms.length; i++) {
		for (var j = 0; j < relations.length; j++) {
			if (popTerms[i] == relations[j]) {
				answList.push(popTerms[i]);
				answVal.push(parseInt(popVals[i]));
				answScore += parseInt(popVals[i]);

				all.push({'A': answVal[answVal.length - 1], 
					'B': answList[answList.length - 1]});
			}
		}
	}
	
	all.sort(function(a, b) {
	  return a.A - b.A;
	});
	
	answList = new Array();
	answVal = new Array();
	
	for (var i = 0; i < all.length; i++) {
		 answVal.push(all[i].A);
		 answList.push(all[i].B);
	}
	
	var text = "Your answers that were selected by other players:\n<ul>";
	
	for (var i = 0; i < answList.length; i++) {
		text += "<li>" + answList[i] + " (" + answVal[i] + ")</li>\n";
	}
	text += "</ul>";
	
	$("#feedbackAnswers").html(text);
	
	$('#answersModal').modal();
}


