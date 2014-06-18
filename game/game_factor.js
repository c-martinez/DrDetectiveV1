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
				}
				i++;
			}
		}
	}
	
	// change help box
	$("#factorTypeHelp").attr("data-content", "Example solution: <div class=\"pop-inner\">" + fTypesExample[fType] + "</div>");
	$("#factorTypeHelp").attr("data-original-title", fTypesHelp[fType]);
	
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
	$("#factorTypeHelp").attr("data-content", "Example solution: <div class=\"pop-inner\">" + fTypesExample[fType] + "</div>");
	$("#factorTypeHelp").attr("data-original-title", fTypesHelp[fType]);
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

