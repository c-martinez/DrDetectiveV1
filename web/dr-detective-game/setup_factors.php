<?php
		echo "<script type=\"text/javascript\">\n";
		
		echo 'fTypes = {';
		$i = 0;
		foreach ($factor_classes as $item) {
			if ($i > 0) echo ",\n";
			echo "\"$item\" : Array(";
			$j = 0;
			foreach ($available_factor_types[$item] as $jtem) {
				if ($j > 0) echo ", ";
				echo "\"$jtem\"";
				$j++;
			}
			echo ")";
			$i++;
		}
		echo "};\n";
		
		echo 'fTypesHelp = {';
		$i = 0;
		foreach ($factor_classes as $item) {
			$j = 0;
			if ($i > 0) echo ",\n";
			
			foreach ($available_factor_types[$item] as $jtem) {
				if ($j > 0) echo ",\n";
				echo "\"$jtem\" : \"$available_factor_types_help[$jtem]\"";
				$j++;
			}
			$i++;
		}
		echo "};\n";
		
		echo 'fTypesExample = {';
		$i = 0;
		foreach ($factor_classes as $item) {
			$j = 0;
			if ($i > 0) echo ",\n";
			foreach ($available_factor_types[$item] as $jtem) {
				if ($j > 0) echo ",\n";
				echo "\"$jtem\" : \"$available_factor_types_example[$jtem]\"";
				$j++;
			}
			$i++;
		}
		echo "};\n";
		
		echo "popTerms = {";
		$i = 0;
		foreach ($factor_classes as $jtem) {
			foreach ($available_factor_types[$jtem] as $item) {
				if ($i > 0) echo ", ";
				echo "\"".$item."\" : Array(";
				$j = 0;
					
				// echo count($words_ann_other[$item]);
				
				if (isset($total_answers["diagnose"])) {
					if (isset($total_answers["diagnose"][$item])) {
						if (isset($pop_term["diagnose"])) {
							foreach ($pop_term["diagnose"][$item] as $word) {
								if ($j > 0) echo ", ";
								echo "$word";
								$j = $j + 1;
							}
						}
					}
				}
				echo ")\n";
				
				$i = $i + 1;
			}
		}
		echo "};\n";
		
		echo "popVals = {";
		$i = 0;
		foreach ($factor_classes as $jtem) {
			foreach ($available_factor_types[$jtem] as $item) {
				if ($i > 0) echo ", ";
				echo "\"".$item."\" : Array(";
				$j = 0;
					
				// echo count($words_ann_other[$item]);
				if (isset($total_answers["diagnose"])) {
					if (isset($total_answers["diagnose"][$item])) {
						foreach ($pop_score["diagnose"][$item] as $word) {
							if ($j > 0) echo ", ";
							echo "'$word'";
							$j = $j + 1;
						}
					}
				}
				echo ")\n";
				
				$i = $i + 1;
			}
		}
		echo "};\n";
		
		echo "var otherAnnMap = {";
		$i = 0;
		foreach ($factor_classes as $jtem) {
			foreach ($available_factor_types[$jtem] as $item) {
				if ($i > 0) echo ", ";
				echo "\"".$item."\" : Array(";
				$j = 0;
					
				// echo count($words_ann_other[$item]);
				if (isset($words_ann_other["diagnose"])) {
					if (isset($words_ann_other["diagnose"][$item])) {
						foreach ($words_ann_other["diagnose"][$item] as $word_list) {
							if ($j > 0) echo ", ";
							echo "Array(";
							$k = 0;
					
							foreach ($word_list as $word) {
								if ($k > 0) echo ", ";
								echo "\"".$word."\"";
								$k = $k + 1;
							}
							echo ")";
							$j = $j + 1;
						}
					}
				}
				echo ")\n";
				
				$i = $i + 1;
			}
		}
		echo "};\n";
		
		if (strcmp($worker_role, "validate") == 0) {
			echo "var otherValMap = {";
			$i = 0;
			foreach ($factor_classes as $jtem) {
				foreach ($available_factor_types[$jtem] as $item) {
					if ($i > 0) echo ", ";
					echo "\"".$item."\" : Array(";
					$j = 0;
					
					// echo count($words_ann_other[$item]);
				
					if (isset($words_ann_other_v["diagnose"])) {
						foreach ($words_ann_other_v["diagnose"][$item] as $word_list) {
							if ($j > 0) echo ", ";
							echo "Array(";
							$k = 0;
					
							foreach ($word_list as $word) {
								if ($k > 0) echo ", ";
								echo "\"".$word."\"";
								$k = $k + 1;
							}
							echo ")";
							$j = $j + 1;
						}
					}
					echo ")\n";
				
					$i = $i + 1;
				}
			}
			echo "};\n";
		}
		
		echo "</script>\n";
		
		echo "<script src=\"game_factor.js\"></script>";
		if (strcmp($worker_role, "annotate") == 0) {
			echo "<script src=\"game_factor_annotate.js\"></script>";
		} else {
			echo "<script src=\"game_factor_validate.js\"></script>";
		}
?>
