$(document).ready(function () {  
	$('.dropdown-toggle').dropdown(); 
	
	// add button to toggle other submissions
	var button = document.createElement("button");
	button.setAttribute("id", "otherSubmissions");
	button.setAttribute("class", "btn btn-info");
	button.setAttribute("onclick", "showOtherSubmissions(); return false;");
	button.innerHTML = "Show what others have found";
	document.getElementById("paragraphButtons").appendChild(button);
	
	$(".dial").remove();
	
	timeStart = new Date();
	$("#mostPopular").css("display","none");
	
	$("#term1").text(term1Expl);
	$("#term2").text(term2Expl);
	
	$("#validateUser").text("Eliminated by you: ");
	
	var vt = document.createElement("input");
	vt.setAttribute("name", "validateTags");
	vt.setAttribute("type", "hidden");
	vt.setAttribute("placeholder", "Tags");
	vt.setAttribute("class", "tm-input tm-input-error");
	vt.setAttribute("id", "validateTags");
	document.getElementById("validateUser").appendChild(vt);
	
	jQuery("#validateTags").tagsManager({hiddenTagListName: "validateTagsHidden"});
});

for (var i = 0; i < allAnn.length; i++) {
	jQuery("#answerTags").tagsManager('pushTag',allAnn[i]);
	relations.push(allAnn[i]);
}


function showOtherSubmissions() {
	$("#validateUser").attr("class", "span6");
	
	var div = document.createElement("div");
	div.setAttribute("class", "span6");
	div.setAttribute("id", "allAnn");
	div.innerHTML = "Eliminated by others: ";
	document.getElementById("validateTagList").appendChild(div);
	
	for (var i = 0; i < valAnn.length; i++) {
		text = document.createElement("span");
		text.setAttribute("class", "tm-tag");
		text.setAttribute("id", "otherTag" + i);
		text.innerHTML = valAnn[i];
		document.getElementById("allAnn").appendChild(text);
		$("#allAnn").find("#otherTag" + i).css("background-color", colorMap["blue"]);	
	}
	
	document.getElementById("otherSubmissions").disabled = true;
}

function submitAnswers() {
	var Item = makeStruct("timeStart timeCreate relations relationTimes");
	timeCreate = new Date();
	
	var row = new Item(timeStart.getTime().toString(),
										 timeCreate.getTime().toString(),
										 removedRelations.slice(0),
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



