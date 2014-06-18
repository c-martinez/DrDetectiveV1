$(document).ready(function () {  
	$("#domainsSpan").css("display", "none");
	$("#taskButtons").css("display", "none");
	
	document.getElementById("factorsvalidate").disabled = true;
	document.getElementById("relationsannotate").disabled = true;
	document.getElementById("relationsvalidate").disabled = true;
	
	for (var i =0; i < 4; i++) $("#dl" + i).css("text-decoration", "none");
	
	if (savedLevel != "") {
		pickLevel(savedLevel);
	}
});

var gameTask = "";
var gameRole = "";
var gameLevel = "";
var doms = Array("Hematology/Oncology", "Nephrology", "Primary Care/Hospitalist/Clinical Practice", "Viral Infections");

function pickLevel(level) {
	document.getElementById("quick").disabled = false;
	document.getElementById("normal").disabled = false;
	document.getElementById("hard").disabled = false;

	gameLevel = level;
	$("#taskButtons").css("display", "inline");
	document.getElementById(level).disabled = true;

	$("#domainsSpan").css("display", "inline");
	gameTask = "factors";
	gameRole = "annotate";
	$("#startGame").attr("action", "test.php?task=" + gameTask + "&role=" + gameRole);
	
	if (gameLevel == "quick") {
		for (var i = 0; i < 4; i++) {
			var val = parseInt(totDom[i][0]) - parseInt(solvedDom[i][0]);
			$("#totPars" + i).text(val);
			$("#solvedPars" + i).text(solvedDom[i][0]);
			$("#dl" + i).attr("data-content", solvedDom[i][0] + " cases solved, " + val + " left to go");
			
			if (totDom[i][0] == solvedDom[i][0]) {
				//var ht = $("#domLink" + i).find("a").html();
				//$("#domLink" + i).html(ht);
				domainsSolved[i] = true;
				$("#dl" + i).attr("onclick", "return false;");
				$("#dl" + i).css("text-decoration", "line-through");
				//alert(ht);
			}
			else if (domainsSolved[i] == true) {
				var text = $("#domLink" + i).html();
				//$("#domLink" + i).html("<a href=\"#\" onclick=\"pickDomains('" + domainsArray[i] +"');\">" + text + "</a>");
				$("#dl" + i).attr("onclick", "pickDomains('" + doms[i] +"');");
				$("#dl" + i).css("text-decoration", "none");
				domainsSolved[i] = false;
			}
		}
	}
	else if (gameLevel == "normal") {
		for (var i = 0; i < 4; i++) {
			var val = parseInt(totDom[i][1]) - parseInt(solvedDom[i][1]);
			$("#totPars" + i).text(totDom[i][1]);
			$("#solvedPars" + i).text(solvedDom[i][1]);
			$("#dl" + i).attr("data-content", solvedDom[i][1] + " cases solved, " + val + " left to go");
			
			if (totDom[i][1] == solvedDom[i][1]) {
				//var ht = $("#domLink" + i).find("a").html();
				//$("#domLink" + i).html(ht);
				$("#dl" + i).attr("onclick", "return false;");
				$("#dl" + i).css("text-decoration", "line-through");
				domainsSolved[i] = true;
				//alert(ht);
			}
			else if (domainsSolved[i] == true) {
				var text = $("#domLink" + i).html();
				$("#dl" + i).attr("onclick", "pickDomains('" + doms[i] +"');");
				$("#dl" + i).css("text-decoration", "none");
				//$("#domLink" + i).html("<a href=\"#\" onclick=\"pickDomains('" + domainsArray[i] +"');\">" + text + "</a>");
				domainsSolved[i] = false;
			}
		}
	}
	else {
		for (var i = 0; i < 4; i++) {
			var val = parseInt(totDom[i][2]) - parseInt(solvedDom[i][2]);
			$("#totPars" + i).text(totDom[i][2]);
			$("#solvedPars" + i).text(solvedDom[i][2]);	
			$("#dl" + i).attr("data-content", solvedDom[i][2] + " cases solved, " + val + " left to go");
			
			if (totDom[i][2] == solvedDom[i][2]) {
				//var ht = $("#domLink" + i).find("a").html();
				//$("#domLink" + i).html(ht);
				$("#dl" + i).attr("onclick", "return false;");
				$("#dl" + i).css("text-decoration", "line-through");
				domainsSolved[i] = true;
				//alert(ht);
			}
			else if (domainsSolved[i] == true) {
				var text = $("#domLink" + i).html();
				$("#dl" + i).attr("onclick", "pickDomains('" + doms[i] +"');");
				$("#dl" + i).css("text-decoration", "none");
				//$("#domLink" + i).html("<a href=\"#\" onclick=\"pickDomains('" + domainsArray[i] +"');\">" + text + "</a>");
				domainsSolved[i] = false;
			}
		}
	}
}

function pickTask(task, role) {
	/*document.getElementById("factorsannotate").disabled = false;
	document.getElementById("factorsvalidate").disabled = false;
	document.getElementById("relationsannotate").disabled = false;
	document.getElementById("relationsvalidate").disabled = false;
	
	gameTask = task;
	gameRole = role;
	
	$("#startGame").attr("action", "test.php?task=" + gameTask + "&role=" + gameRole);
	
	document.getElementById(task + role).disabled = true;
	if (task == 'factors') {
		$("#domainsSpan").css("display", "inline");
	}
	else {
		//$("#domainsSpan").css("display", "none");
		//window.location.replace("test.php?task=" + gameTask + "&role=" + gameRole);
		$("#levelInput").attr("value", gameLevel);
		document.getElementById("startGame").submit();
	}*/
}

function pickDomains(domains) {
	$("#domainsInput").attr("value", domains);
	$("#levelInput").attr("value", gameLevel);
	document.getElementById("startGame").submit();
}
