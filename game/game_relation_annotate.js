$(document).ready(function () {  
	$('.dropdown-toggle').dropdown(); 
	
	// add button to toggle other submissions
	button = document.createElement("button");
	button.setAttribute("id", "otherSubmissions");
	button.setAttribute("class", "btn btn-info");
	button.setAttribute("onclick", "showOtherSubmissions(); return false;");
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
		document.getElementById("listButton").disabled = false;
		document.getElementById("submit").disabled = false;
		
		changeSelectedFactorType(index);
	}

	if (alreadySubmitted() == true) {
		document.getElementById("listButton").disabled = true;
		document.getElementById("submit").disabled = true;
		loadExistingAnn(submission[fType].factorPos);
	}*/
	changeSelectedFactorType(index);
	
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
	var Item = makeStruct("timeStart timeCreate relations relationTimes");
	timeCreate = new Date();
	var row = new Item(timeStart.getTime().toString(),
										 timeCreate.getTime().toString(),
										 relations.slice(0),
										 factorTimes.slice(0));
	submission = row;
	
	toggleMostPopular();
}

function toggleMostPopular() {
	document.getElementById("mostPopular").innerHTML = "Most popular relations: ";
	$("#mostPopular").css("display","inline");
	document.getElementById("submit").disabled = true;
	$(".tm-tag-remove").css("display", "none");
	
	for (var i = 0; i < popTerms.length; i++) {
		text = document.createElement("span");
		text.setAttribute("class", "tm-tag");
		text.setAttribute("id", "popTag" + i);
		text.innerHTML = popTerms[i];
		document.getElementById("mostPopular").appendChild(text);
		$("#mostPopular").find("#popTag" + i).css("background-color", colorMap["yellow"]);
	}
}

function nextPatient() {
	timeCreate = new Date();
	$("#timeStartForm").attr("value", timeStart.getTime().toString());
	$("#timeCreateForm").attr("value", timeCreate.getTime().toString());
	
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

function showOtherSubmissions() {
	$("#userAnswersTagList").attr("class", "span6");
	
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
		document.getElementById("allAnn").appendChild(text);
		$("#allAnn").find("#otherTag" + i).css("background-color", colorMap["blue"]);	
	}
	document.getElementById("otherSubmissions").disabled = true;
}

