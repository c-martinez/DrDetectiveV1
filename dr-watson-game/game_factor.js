// map of color highlighting
var colorMap = {
	"red"     : "rgb(255, 204, 153)",
	
	"Factors"                : "rgb(255, 204, 153)",
	"Diseases"               : "rgb(255, 192, 203)",
	"Medical Tests"          : "rgb(238, 224, 229)",
	"Medications"            : "rgb(189, 252, 201)",
	"Therapeutic Procedures" : "rgb(202, 255, 112)",
	"Allergies"              : "rgb(244, 164, 96)",
	"Age"                    : "rgb(255, 246, 143)",
	"Gender"                 : "rgb(255, 246, 143)",
	"Race"                   : "rgb(255, 246, 143)",
	"Location"               : "rgb(255, 246, 143)",
	"Occupation"             : "rgb(255, 246, 143)",
	"Time/Duration"          : "rgb(204, 204, 259)",
	"Other"                  : "rgb(204, 204, 259)",
	
	"blue"   : "rgb(153, 204, 255)",
	"gray"   : "rgb(208, 208, 208)",
	"purple" : "rgb(204, 153, 204)",
	"yellow" : "rgb(255, 255, 0)",
	"white"  : "rgb(255, 255, 255)"
};


// initialize the help popover icon for giving help with factor typing
$(function () {
	$("#factorTypeHelp").popover({trigger: 'hover', placement:'bottom', html : true });
	$("#factorTypeEx").popover({trigger: 'hover', placement:'bottom', html : true });    
}); 

// initialize the tag manager
jQuery(".tm-input").tagsManager();

submission = {};

// split the paragraph into words
/*var split_words = $("#par").text().replace(/\b(\w+?)\b/g, '<span class="word">$1</span>');
$("#par").html(split_words);
*/
var words = $("#par").text().split(" ");
$("#par").empty();
$.each(words, function(i, v) {
		if (v.charAt(v.length - 1) == ",") {
			$("#par").append(" ");
			$("#par").append($("<span class=\"word\">").text(v.substring(0, v.length - 1)));
			$("#par").append(", ");
		}
		else if (v.charAt(v.length - 1) == ".") {
			$("#par").append(" ");
			$("#par").append($("<span class=\"word\">").text(v.substring(0, v.length - 1)));
			$("#par").append(". ");
		}
		else if (v.charAt(v.length - 1) == "!") {
			$("#par").append(" ");
			$("#par").append($("<span class=\"word\">").text(v.substring(0, v.length - 1)));
			$("#par").append("! ");
		}
		else if (v.charAt(v.length - 1) == "?") {
			$("#par").append(" ");
			$("#par").append($("<span class=\"word\">").text(v.substring(0, v.length - 1)));
			$("#par").append("? ");
		}
		else if (v.charAt(v.length - 1) == ";") {
			$("#par").append(" ");
			$("#par").append($("<span class=\"word\">").text(v.substring(0, v.length - 1)));
			$("#par").append("; ");
		}
		else {
			$("#par").append(" ");
    	$("#par").append($("<span class=\"word\">").text(v));
			$("#par").append(" ");
    }
});

var i = 0;
var w = $("#par").find("span").each(function() {
	$(this).attr("id", i++);
});

/*
** Select a new type of factor
*/
function changeSelectedFactorType(index) {
	fInd = index;
	
	var i = 0;
	for (var key in fTypes) {
		if (fTypes.hasOwnProperty(key)) {
			for (var j = 0; j < fTypes[key].length; j++) {
				if (i.toString() == fInd) {
					fType = fTypes[key][j];
					fClass = key;
					document.getElementById('selectedFactorDropdown').innerHTML = fType;
					$("#selectedFactorDropdown").css("background-color", colorMap[fType]);
					$("#ftype").text(fType);
					$("#ftype").css("background-color", colorMap[fType]);
				}
				i++;
			}
		}
	}
	
	// change help box
	$("#factorTypeEx").attr("data-content", "Example solution: <div class=\"pop-inner\">" + fTypesExample[fType] + "</div>");
	$("#factorTypeHelp").attr("data-content", "clues that are " +  fTypesHelp[fType]);
	//$(".imgpop").find(".highlightedWord").css("background-color", colorMap[fType]);
	
	// change example box
	//$("div.accordion-inner").html(fTypesExample[fType]);
	
	timeFactorStart = new Date();
}

function createFactorDialog() {
	fInd = 0;
	var i = 0;
	var factorTypesHTML = "";
	for (var key in fTypes) {
		if (fTypes.hasOwnProperty(key)) {
			factorTypesHTML = factorTypesHTML + "<li class=\"dropdown submenu\">" +
				"<a href=\"#\" class=\"dropdown-toggle\"  data-toggle=\"dropdown\">" + key + "</a>";
			for (var j = 0; j < fTypes[key].length; j++) {
				if (j == 0) factorTypesHTML += "<ul class=\"dropdown-menu submenu-show submenu-hide\">";
				if (i == fInd) {
					fType = fTypes[key][j];
					fClass = key;
					document.getElementById('selectedFactorDropdown').innerHTML = fType;
					$("#selectedFactorDropdown").css("background-color", colorMap[fType]);
					$("#ftype").text(fType);
					$("#ftype").css("background-color", colorMap[fType]);
				}
				
				var ft = fTypes[key][j].replace(' ', '-').replace('/', '-');
				factorTypesHTML = factorTypesHTML + "<li><a href=\"#\" onclick=\"factorTypeSelect(" + i + ")\" id=\"" + ft + "\">" + fTypes[key][j] + "</a></li>";
				i++;
				if (j == fTypes[key].length - 1) factorTypesHTML = factorTypesHTML + "</ul>";
			}
	
			factorTypesHTML = factorTypesHTML + "</li>";
		}
	}
	
	$('#factorTypesDropdown').html(factorTypesHTML);
	
	// change help box
	$("#factorTypeEx").attr("data-content", "Example solution: <div class=\"pop-inner\">" + fTypesExample[fType] + "</div>");
	$("#factorTypeHelp").attr("data-content", "clues that are " + fTypesHelp[fType]);
	//$(".highlightedWord").css("background-color", colorMap[fType]);
	//alert($("#factorTypeHelp").attr("data-content"));
	
	// change example box
	//$("div.accordion-inner").html();
	
	timeFactorStart = new Date();
}

function makeStruct(names) {
  var names = names.split(' ');
  var count = names.length;
  function constructor() {
    for (var i = 0; i < count; i++) {
      this[names[i]] = arguments[i];
    }
  }
  return constructor;
}

function alreadySubmitted() {
	if (fType in submission) {
		return true;
	}
	return false;
}

function createHiddenInput(iName, iVal) {
	var input = document.createElement("input");
	input = document.createElement("input");
	input.setAttribute("type", "hidden");
	input.setAttribute("name", iName);
	input.setAttribute("value", iVal);
	return input;
}

function getCorrectAnswers() {
	var plusscore = 0;

	var all = [];
	for (var i = 0; i < otherAnnMap[fType].length; i++) {
		var found = false;
		for (var x = 0; x < otherAnnMap[fType][i].length; x++) {
			for (var j = 0; j < submission[fType].factorPos.length; j++) {
				for (var y = 0; y < submission[fType].factorPos[j].length; y++) {
					if (otherAnnMap[fType][i][x] == submission[fType].factorPos[j][y]) {
						if (found == false) {
							answList.push(popTerms[fType][i]);
							answVal.push(parseInt(popVals[fType][i]));
							answScore += parseInt(popVals[fType][i]);
							
							plusscore += 1/submission[fType].factorPos[j].length;

							all.push({'A': answVal[answVal.length - 1], 
								'B': answList[answList.length - 1]});
							found = true;
						}
					}
				}
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
	
	if (plusscore > 0) {
		//alert(plusscore);
		pop_text = "";
		pgained += plusscore;
		
		if (pgained == 0) {
			if (plost == 0) {
				pop_text = "nothing yet";
			}
			else {
				pop_text = "<span class='trmin'>-"+
				plost.toFixed(1) +
				"</span> points deducted by other users disagreeing with your answers";
			}
		}
		else {
			if (plost == 0) {
				pop_text = "<span class='trme'>+" +
				pgained.toFixed(1) +
				"</span> points gained from other users agreeing with your answers";
			}
			else {
				pop_text = "<p><span class='trme'>+" +
					pgained.toFixed(1) +
					"</span> points gained from other users agreeing with your answers</p><p><span class='trmin'>-" +
					plost.toFixed(1) +
					"</span> points deducted by other users disagreeing with your answers</p>";
			}
		}
		
		$("#notifPopover").attr("data-content", pop_text);
		//alert(pop_text);
		$("#notifPopover").popover('show');
		currScore += (plusscore * docScore);
		$("#navscore").text(currScore.toFixed(1));
	}
	
	/*if (answList.length > 0) {
		$('#answersModal').modal();
	}*/
	
}

