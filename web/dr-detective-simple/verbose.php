<?php

$jid = 1;


if (isset($_GET['role'])) {
	$worker_role = $_GET['role'];
} else {
	$worker_role = 'annotate';
}

if (isset($_GET['task'])) {
	$fac_or_rel = $_GET['task'];
} else {
	$fac_or_rel = 'factors';
}


$factor_classes = array(
	"Observations",
	"Demographics",
	"Misc"
);

$available_factor_types["Observations"] = array("Factors", "Medical Tests", "Diseases", "Medications", "Therapeutic Procedures", "Allergies");
$available_factor_types["Demographics"] = array("Age", "Gender", "Race", "Location", "Occupation");
$available_factor_types["Misc"] =  array("Time/Duration", "Other");

$available_factor_types_help["Factors"] = 'qualitative clinical observations regardless of who made the observation, non-clinical but potential risk factors such as social habits or hazardous exposure';
$available_factor_types_help["Diseases"] =	'current/past diagnoses';
$available_factor_types_help["Medical Tests"] =	'diagnostic tests or procedures';
$available_factor_types_help["Medications"] =	'drugs and supplements';
$available_factor_types_help["Therapeutic Procedures"] = 'non-medication treatments and devices';
$available_factor_types_help["Allergies"]= 'specific mention of allergies';
$available_factor_types_help["Age"] = 'exact age or age group';
$available_factor_types_help["Gender"] = 'first explicit mention of gender term of the patient';
$available_factor_types_help["Race"] = 'ethnicity of the patient';
$available_factor_types_help["Location"] = 'geographic location where the patient resides, resided in the past, or has travelled to';
$available_factor_types_help["Occupation"] = 'occupation of the patient';
$available_factor_types_help["Time/Duration"] = 'a particular time or time interval';
$available_factor_types_help["Other"] = 'any other relevant aspect that does not fit with the other types';

$available_factor_types_example["Factors"] = 'The patient suffered from <div class=\"highlightedWord\" style=\"background-color: rgb(255, 204, 153);\">dyspnea</div>, was a <div class=\"highlightedWord\" style=\"background-color: rgb(255, 204, 153);\">tobacco user</div>, and had previously <div class=\"highlightedWord\" style=\"background-color: rgb(255, 204, 153);\">worked with asbestos</div>.';
$available_factor_types_example["Medical Tests"] = 'A <div class=\"highlightedWord\" style=\"background-color: rgb(238, 224, 229);\">radiograph</div> revealed broken ribs.';
$available_factor_types_example["Diseases"] = 'The woman had a history of <div class=\"highlightedWord\" style=\"background-color: rgb(255, 192, 203);\">irritable bowel syndrome</div>.';
$available_factor_types_example["Medications"] = 'The doctors prescribed <div class=\"highlightedWord\" style=\"background-color: rgb(189, 252, 201);\">laxatives</div> and <div class=\"highlightedWord\" style=\"background-color: rgb(189, 252, 201);\">antidepressants</div>.';
$available_factor_types_example["Therapeutic Procedures"] = 'The hospital performed <div class=\"highlightedWord\" style=\"background-color: rgb(202, 255, 112);\">hip replacement surgery</div> on the man, and installed a <div class=\"highlightedWord\" style=\"background-color: rgb(202, 255, 112);\">pacemaker</div>.';
$available_factor_types_example["Allergies"] = 'The man was suffering from <div class=\"highlightedWord\" style=\"background-color: rgb(244, 164, 96);\">penicillin allergy</div>.';
$available_factor_types_example["Age"] = 'A <div class=\"highlightedWord\" style=\"background-color: rgb(255, 246, 143);\">19-year-old</div> caucasian male...';
$available_factor_types_example["Gender"] = 'A 19-year-old caucasian <div class=\"highlightedWord\" style=\"background-color: rgb(255, 246, 143);\">male</div>...';
$available_factor_types_example["Race"] = 'A 19-year-old <div class=\"highlightedWord\" style=\"background-color: rgb(255, 246, 143);\">caucasian</div> male...';
$available_factor_types_example["Location"] = 'The patient had just returned from <div class=\"highlightedWord\" style=\"background-color: rgb(255, 246, 143);\">India</div>';
$available_factor_types_example["Occupation"] = 'The patient <div class=\"highlightedWord\" style=\"background-color: rgb(255, 246, 143);\">worked with wild animals</div>.';
$available_factor_types_example["Time/Duration"] = 'The pain intensified <div class=\"highlightedWord\" style=\"background-color: rgb(204, 204, 259);\">two hours later</div>.';
$available_factor_types_example["Other"] = '';


$relation_roles["diagnose"] = 'could help identify the diagnosis';
$relation_roles["irrelevant"] = 'are irrelevant to the diagnosis';
$relation_roles["normal"] = 'are normal conditions of the patient';

$av_rel_roles = array("diagnose", "irrelevant", "normal");

$relations = array(
	"treats",
	"prevents",
	"diagnoses",
	"causes",
	"location",
	"symptom",
	"manifestation",
	"contraindicates",
	"associated with",
	"side effect",
	"is a",
	"part of",
	"other"
);

?>
