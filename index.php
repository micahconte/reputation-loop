<?php

// ini_set('display_errors',true);

$url = 'http://test.localfeedbackloop.com/api?apiKey=61067f81f8cf7e4a1f673cd230216112&noOfReviews=10&internal=1&yelp=1&google=1&offset=50&threshold=1';

$rfrom = array(0=>'internal',1=>'yelp',2=>'google');
// create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        

        // $output contains the output string
        $data = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);    


        $objArray = json_decode($data);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Rep Loop</title>
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/paginator.js"></script>
	<link href="css/style.css" rel="stylesheet" type="text/css" />

    <style type="text/css">

        body
        {
            padding:0 20px;
            font: 15px Roboto, arial, serif;
        }

        #wrapper
        {
            margin: auto;
            width: 500px;
        }

        .contents
        {
            width: 45%; /*height: 150px;*/
            margin: 0;
        }

        .contents > p
        {
            padding: 8px;
        }

        .table
        {
            width: 100%;
            border-right: solid 1px #5f9000;
        }

        .table th, .table td
        {
            width: 16%;
            height: 20px;
            padding: 4px;
            text-align: left;
        }

        .table th
        {
            border-left: solid 1px #5f9000;
        }

        .table td
        {
            border-left: solid 1px #5f9000;
            border-bottom: solid 1px #5f9000;
        }

        .header
        {
            background-color: #4f7305;
            color: White;
        }

        #divs
        {
            margin: 0;
            height: 200px;
            font: verdana;
            font-size: 14px;
            background-color: White;
        }

        #divs > div
        {
            width: 98%;
            padding: 8px;
        }

        #divs > div p
        {
            width: 95%;
            padding: 8px;
        }

        ul.tab
        {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        ul.tab li
        {
            display: inline;
            padding: 10px;
            color: White;
            cursor: pointer;
        }
        .title
        {
        	font-weight: bold;
        }

        #container
        {
            width: 100%;
            border: solid 1px red;
        }        

    </style>


	<script>
		$(document).ready(function(){
          $('#red').smartpaginator({ 
          		totalrecords: <?php echo count($objArray->reviews);?>, 
                recordsperpage: 1,
                theme: 'red', 
                controlsalways:true,
                onchange: function(newPage) {
                    $("#r").html($('#page_'+newPage).html());
                }
            });
	        $("#r").html($('#page_1').html());
		});
	</script>
</head>

<body>

        <div id="red-contents" class="contents" style="border: solid 1px #F82723;">
            
				<ul style="list-style-type: none;">
            		<li><span class="title">Business Name:</span> <?php echo $objArray->business_info->business_name;?></li>
            		<li><span class="title">Business Address:</span> <?=$objArray->business_info->business_address;?></li>
            		<li><span class="title">Business Phone:</span> <?=$objArray->business_info->business_phone;?></li>
            		<li><span class="title">Ratings:</span><br>
            			<span class="title">Avg:</span> <?=$objArray->business_info->total_rating->total_avg_rating;?> 
            			<span class="title">Reviews:</span> <?=$objArray->business_info->total_rating->total_no_of_reviews;?>
            		</li>
            		<li><a href="<?=$objArray->business_info->external_url;?>" target="_blank">External url</a></li>
            		<li><a href="<?=$objArray->business_info->external_page_url;?>" target="_blank">Page url</a></li>
            	</ul>
            <div style="height: 315px; text-align: left; font-size: 16px; padding: 30px 40px 0 80px;color: #333" id="r">
               
            </div>
            <div id="red" style="margin: auto;">

            </div>

        </div>
<?php 
    $i=1;
    foreach($objArray->reviews as $review):
    	echo "<div id='page_$i' style='display:none'>";
    ?>
        <ul style="list-style-type: none;">
            <li><span class="title">Review Id:</span> <?php echo $review->review_id;?></li>
            <li><span class="title">Customer Name:</span> <?php echo $review->customer_name;?></li>
            <li><span class="title">Submission Date:</span> <?=date('m/d/Y h:i:s', strtotime($review->date_of_submission));?></li>
            <li><span class="title">Customer Last Name:</span> <?=$review->customer_last_name;?></li>
            <li><span class="title">Description:</span> <p><?=$review->description;?></p></li>
            <li><span class="title">Rating:</span> <?=$review->rating;?></li>
            <li><span class="title">Review From:</span> <?=$rfrom[$review->review_from];?></li>
            <li><a href="<?=$review->review_url;?>" target="_blank"><?=$review->review_source;?></a></li>
            <li><a href="<?=$review->customer_url;?>" target="_blank">Review url</a></li>
        </ul>
	<?php
        echo "</div>";
        $i++;
    endforeach;
?>
</body>
</html>