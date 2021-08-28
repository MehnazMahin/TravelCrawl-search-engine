<html>
 <head>
 	  <title>MapReduce Query Results</title>
 	  <link href="../css/main.css" rel="stylesheet" />
  </head>
 <body>
	<div class="hero-image">
	  <a href="//localhost">
		  <div class="hero-text">
		    <h1 style="font-size:20px; font-family: 'Poppins', sans-serif;">TravelCrawl</h1>
		  </div>
	  </a>
	</div>
 	<table id="result_table" class="results_table">
 		<thead><tr><th><b><?php echo "Top Results for Query: ".str_replace(",", " ", $_POST['search_text'])?></b></th></tr></thead>
 		 <tbody>
		 <?php
	 	//execute shell command that runs indexer using user input
	 	//Need to add '2>&1' at the end of your shell command so that the results of the command get output
		$abc = str_replace(",", " ", $_POST['search_text']);
		exec('python3 irs.py '.$abc." 2>&1",$results);
		//<?php echo $results>
		//$results=json_encode($results);
		//$results=json_decode($results);
	 	$i=0;
		$num_top_results=10;
	 	foreach ($results as $line) {
			if($i<=$num_top_results){
	 				//split up the output in order the format the results table
					$s1=explode(", 'comments': ", $line);
					$comment=$s1[1];
                                        $s2=$s1[0];
					$s3=str_replace("{'title': ", "", $s2);
					$s4=explode(", 'body': ", $s3);
					$tit=$s4[0];					
					$s5=$s4[1];
					$s6=explode(", 'link': ", $s5);
					$body=$s6[0];
					$link=$s6[1];
					

					$b=substr($link, 1,-1);
					$url="www.reddit.com".$b;
					$title=substr($tit, 1, -1);
					if(strlen($body) > 3) {
						$snippet=substr($body, 0, 300)."...";					
					}
					else {
						$a=substr($comment, 1, 300);
						$snippet=$a."...";
					}
					
		 		?>
		 		<tr>
		 			<td><a href=<?php echo 'https://'.$url.''; ?> target="_blank"><span class="result_name"><?php echo $title ?></span>
		 				<br>
		 				<span class="result_url"><?php echo $url ?></span>
		 				<br>
		 				<span class="result_snippet"><?php echo $snippet ?></span>
		 				</a>
		 			</td>
		 		</tr>
		 		<?php
			}
	 		$i++;
	 	}
//	 	print_r(error_get_last());
	 ?> 
 	</tbody>
 </table>
</body>
</html>
