<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="colorlib.com">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,800" rel="stylesheet" />
    <link href="css/main.css" rel="stylesheet" />
  </head>
  <body>
    <div class="s004">

      <form id="searchForm" action="lucene_index/results.php" method="POST">
        <fieldset>
          <legend>TravelCrawl</legend>
          <div class="inner-form">
            <div class="input-field">
              <input class="form-control" id="choices-text-preset-values" name="search_text" type="text" placeholder="Type to search..." />
              <button class="btn-search" type="button" onclick="submitForm()">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 35 35">
                  <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path>
                </svg>
              </button>
            </div>
		    <!-- <div class="onoffswitch" onclick="changeIndexVal()">
		        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
		        <label class="onoffswitch-label" for="myonoffswitch">
		            <span class="onoffswitch-inner"></span>
		            <span class="onoffswitch-switch"></span>
		            <input type="hidden" name="index_type" id="index_type" value="lucene_index">
		        </label>
		    </div> -->
        <div class="button" onclick="changeIndexVal()">
          <input type="radio" name="index_type"  id="myonoffswitch" id="index_type"
          <?php if (isset($index) && $index=="Lucene" ) echo "checked";?>
          value="lucene_index"> Lucene
          <span class="space"> </span>
          <input type="radio" name="index_type" id="index_type"
          <?php if (isset($index) && $index=="MapReduce") echo "checked";?>
          value="mr_index">  MapReduce

          </div>
          <div class="suggestion-wrap">
            <! -- add spans here -->
          </div>
        </fieldset>

      </form>
    </div>
    <script src="js/extention/choices.js"></script>
    <script src="js/main.js"></script>
    <script>
      var textPresetVal = new Choices('#choices-text-preset-values',
      {
        removeItemButton: true,
      });
	  function submitForm(){
	    document.getElementById("searchForm").submit();
	     /*result_snippet*/
	  }
     function changeIndexVal(){
     	if(document.getElementById('myonoffswitch').checked){
     		document.getElementById("index_type").value ="lucene_index";
     		document.getElementById('searchForm').action="lucene_index/results.php";
     	}else{
     		document.getElementById("index_type").value ="mr_index";
     		document.getElementById('searchForm').action="mr_index/results.php";
     	}
     }
    </script>
  </body><!-- This template was made by Colorlib (https://colorlib.com) -->
</html>
