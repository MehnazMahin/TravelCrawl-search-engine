<html>
 <head>
 	  <title>Lucene Query Results</title>
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
	 	exec('sh indexer.sh "'.$_POST['search_text'].'"',$results);
		//$results=file("/var/www/html/lucene_index/lucene_output.txt");
	 	$i=0;
	 	//change this val if you want to output more top results
	 	$num_top_results=10;
	 	//start at index 10 because that's where the actual results are
	 	$last_row=14+$num_top_results+1;
	 	foreach ($results as $line) {
	 		if($i>14 && $i<$last_row){
	 				//split up the output in order the format the results table
	 				$larr=explode("-->",$line);
	 				$tus_arr=explode("--",$larr[1]);
	 				$url_snippet=explode("|",$tus_arr[1]);
	 				$title=$tus_arr[0];
					$reddit_link="www.reddit.com";
	 				$url=$reddit_link.substr($url_snippet[0], 1);
	 				$snippet=$url_snippet[1];
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
	 	//print_r(error_get_last());
	 ?> 
 	</tbody>
 </table>
</body>
</html>
