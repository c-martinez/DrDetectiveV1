<?php

		echo "<script type=\"text/javascript\">\n";

		echo "fTypes = new Array();";
		echo "fTypes.push(\"\");";
		foreach ($relations as $r) {
			echo "fTypes.push(\"$r\");\n";
		}
		
		$tl = explode(",", $term1);
		foreach ($tl as $t) {
			echo "term1.push($t);\n";
		}
		$tl = explode(",", $term2);
		foreach ($tl as $t) {
			echo "term2.push($t);\n";
		}
		echo "var t1 = '$term1';";
		echo "var t2 = '$term2';";
		
		echo "popTerms = new Array();";
		$i = 0;
		foreach ($pop_rel as $rel) {
			echo "popTerms.push(\"$rel\");";
		}
		
		echo "popVals = new Array();";
		$i = 0;
		foreach ($pop_score as $rel) {
			echo "popVals.push(\"$rel\");";
		}
		
		echo "var allAnn = new Array();";
		foreach ($words_ann_other_a as $key => $value) {
			if ($value > 0) {
				echo "allAnn.push('$key');\n";
			}
		}
		
		echo "var valAnn = new Array();";
		foreach ($words_ann_other_v as $key => $value) {
			if ($value > 0) {
				echo "valAnn.push('$key');\n";
			}
		}
		
		echo "</script>\n";

		echo "<script src=\"game_relation.js\"></script>";
		if (strcmp($worker_role, "annotate") == 0) {
			echo "<script src=\"game_relation_annotate.js\"></script>";
		} else {
			echo "<script src=\"game_relation_validate.js\"></script>";
		}
?>
