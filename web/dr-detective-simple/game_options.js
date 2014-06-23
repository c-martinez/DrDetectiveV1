var gameTask = "";
var gameRole = "";
var gameLevel = "";
var doms = Array("Hematology/Oncology", "Nephrology", "Primary Care/Hospitalist/Clinical Practice", "Viral Infections");


$(document).ready(function () {  

	gameTask = "factors";
	gameRole = "annotate";
	$("#startGame").attr("action", "test.php?task=" + gameTask + "&role=" + gameRole);
	
	for (var i =0; i < 4; i++) $("#dl" + i).css("text-decoration", "none");
	
		for (var i = 0; i < 4; i++) {
			var val = parseInt(totDom[i]) - parseInt(solvedDom[i]);
			$("#totPars" + i).text(val);
			$("#solvedPars" + i).text(solvedDom[i]);
			$("#dl" + i).attr("data-content", solvedDom[i] + " tasks completed, " + val + " left to go");
			
			if (val == 0) {
				$("#dl" + i).attr("onclick", "return false;");
				$("#dl" + i).css("text-decoration", "line-through");
			}
		}
});


function pickDomains(domains) {
	$("#domainsInput").attr("value", domains);
	$("#levelInput").attr("value", gameLevel);
	document.getElementById("startGame").submit();
}
