<?php

include 'mysql.php';
include 'Text-Statistics/TextStatistics.php';

$statistics = new TextStatistics;

if ($handle = opendir('data/')) {
    echo "Directory handle: $handle\n";
    echo "Entries:\n";

    /* This is the correct way to loop over the directory. */
    while (false !== ($entry = readdir($handle))) {
        //echo "$entry\n";
        
        $file_handle = fopen("data/$entry", "r");
        $text = "";
				while (!feof($file_handle)) {
					 $line = fgets($file_handle);
					 //echo $line;	
					 $text = $text.$line;
				}
				fclose($file_handle);
				
				if (strcmp($text, "") != 0) {
	        echo "$entry\n";
					$xml = simplexml_load_string($text);
					
					// get title
					$result = $xml->xpath("//title");
					//print_r($result);
					while(list( , $node) = each($result)) {
						$title = $node;
					}
					
					// get diagnosis
					$result = $xml->xpath("//div[@id='diagnosis']");
					//print_r($result);
					while(list( , $node) = each($result)) {
						$diagnosis = $node;
					}
					
					// get domains
					$result = $xml->xpath("//div[@id='domains']");
					//print_r($result);
					while(list( , $node) = each($result)) {
						$domains = $node;
					}
					
					// get diagnosis
					$result = $xml->xpath("//div[@id='url']");
					//print_r($result);
					while(list( , $node) = each($result)) {
						$url = $node;
					}
					
					// get paragraphs
					$par_no = 0;
					$par_text = "";
					$result = $xml->xpath("//p");
					//print_r($result);
					while(list( , $node) = each($result)) {
						if ($statistics->word_count($node)) {
							$par_no++;
							$par_text = $par_text."\n".$node;
						}
					}
					$doc_s = $statistics->sentence_count($par_text);
					$doc_w = $statistics->word_count($par_text);
					$doc_a = $statistics->average_words_per_sentence($par_text);
					
					$query = "INSERT INTO game_doc (Par_count, Sen_count, Word_count, Path, Title, URL, Domains, Diagnosis, Sen_avg_len) VALUES
					($par_no, $doc_s, $doc_w, 'data/$entry', '$title', '$url', '$domains', \"$diagnosis\", $doc_a)";
					mysqli_query($con, $query);
					//echo $query."\n\n";;
					
					$query = "SELECT ID FROM game_doc WHERE
					URL = '$url'";
					$res = mysqli_query($con, $query);
					while ($row = mysqli_fetch_array($res)) {
						$id = $row['ID'];
					}
					
					// process paragraphs
					$par_no = 0;
					$result = $xml->xpath("//p");
					//print_r($result);
					while(list( , $node) = each($result)) {
						if ($statistics->word_count($node) != 1) {
							$readability = $statistics->smog_index($node);
							$par_s = $statistics->sentence_count($node);
							$par_w = $statistics->word_count($node);
							$par_a = $statistics->average_words_per_sentence($node);
						
							$query = "INSERT INTO game_doc_par (Doc_ID, Doc_order, Readability, Sen_count, Word_count, Sen_avg_len) VALUES
							($id, $par_no, $readability, $par_s, $par_w, $par_a)";
							mysqli_query($con, $query);
							//echo $query."\n\n";;
						
							$par_no++;
						}
					}
				}
    }
}

?>
