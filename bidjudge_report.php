<?php
    /*
      ____  _     _      _           _
     |  _ \(_)   | |    | |         | |
     | |_) |_  __| |    | |_   _  __| | __ _  ___    ___  ___  _ __ ___
     |  _ <| |/ _` |_   | | | | |/ _` |/ _` |/ _ \  / __|/ _ \| '_ ` _ \
     | |_) | | (_| | |__| | |_| | (_| | (_| |  __/_| (__| (_) | | | | | |
     |____/|_|\__,_|\____/ \__,_|\__,_|\__, |\___(_)\___|\___/|_| |_| |_|
                                        __/ |
         (c) 2010-2011 BidJudge.com    |___/
    */
    session_start();
    require("../database.php");
    require("../login_process.php");
   /* if(!$rights['adm_view']) {
    	header("Location: ../index.php");
    }*/
	
	if($_SESSION['adminid']=='')
	{
		header('Location:index.php');
	}
    
    // Set $_GET variables
    $func = $_GET['func'];
    $sp = $_GET['sp'];
    $contractor_id = $_GET['contractor_id'];
    $company_id = $_GET['company_id'];
    
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
	<title>
	<?php 
	    switch($func){
	        case show:
	            echo "BidJudge.com - Show Reports";
	            break;
	        case add:
	            echo "BidJudge.com - Add Reports";
	            break;
	        case edit:
	            echo "BidJudge.com - Edit Reports";
	            break;
	        default:
	            echo "BidJudge.com - Company List";
	            break;
	    }
	?>
	</title>
	<?php include("../kilij/includes/header.php"); ?>
	<script src="//code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script type="text/javascript" src="fancyBox/source/jquery.fancybox.js"></script>
<script type="text/javascript" src="fancyBox/source/jquery.fancybox.pack.js"></script>
<link rel="stylesheet" href="fancyBox/source/jquery.fancybox.css" type="text/css" media="screen"/>
	<script type='text/javascript'>
	
		function loadListPage(ajaxData) {
			// only run when showing list_companies.php
			if(window.location.search.length === 0) {
				// no pageNumber passed, first loading page
				if(ajaxData == undefined) {
					var pageNumber,
						sortString;
					if(window.location.hash.length > 0) {
						// page number passed as #page=2, #page=15, etc.
						var hashString = window.location.hash.replace('#',''); // remove #
						pageNumber = hashString.match(/page=(\d|\w)*/); // gets "page=#"
						sortString = hashString.match(/sort=(\d|\w)*/); // gets "sort=sort_string"

						pageNumber = pageNumber[0] != null ? pageNumber[0].replace('page=','') : 1 ; // strips "page="
						sortString = sortString[0] != null ? sortString[0].replace('sort=','') : "" ; // strips "sort="
					}
					else {
						// first load page without hash
						pageNumber = 1;
						sortString = 'default';
					}
					// we don't have ajaxData, so make one.
					ajaxData = "page="+pageNumber+"&sort="+sortString;
				}
				$.ajax({
					url:'includes/list_report.php',
					data: ajaxData,
					success: function(data) {
						$('.company_list').html(data);
					}
				});
				
			}
		}
		
		$(function() {

			if(window.location.search.search('func=add')!=false) {
				$('#name').focus();
			}

			$('.search').live('keyup', function(event,i) {		
				if(event.keyCode == '8' && (event.metaKey==true || event.ctrlKey==true)) {
					$(this).val('');
				}
				else {					
					
					var search_val=$(this).val();
					var search = search_val.replace(/&/g, "%26");
					var ajaxData = window.location.hash.replace('#','') + "&search=" + search ;
					loadListPage(ajaxData);
				}
			});

			loadListPage(); // by default gets page in hash, or first if none.

			$('.getpage').live('click', function() {
				$('.search').val('');
				var args = $(this).attr('args');
				loadListPage(args);
			});
			
		
			
		});
			var jq= $.noConflict();
			jq(function() {

				jq('.fancybox').fancybox({
				width :850,
				height : 680,
				autoSize : false,
				scrolling: 'no',
				closeClick  : false, // prevents closing when clicking INSIDE fancybox
				autoDimensions: false,
				fitToView	: true,
				autoCenter: false,
				helpers     : { 
				overlay : {closeClick: false,
				locked: false} 
			}
			});
		});
	</script>
	
</head>
<body class="adm">
<center>

<?php include_once('../kilij/includes/admin_nav_nw.php'); ?>
    
<div class="w1010 bgwhite p10 m-t30">

<?php
    switch($sp){
        case added:
            $message = '<div class="message">Reports Added</div>';
            break;
        case edited:
            $message = '<div class="message">Reportspany Updated</div>';
            break;
        case deleted:
            $message = '<div class="message">Reports Deleted</div>';
            break;
        default:
            break;
    }
?>
    <div class="red-link">
    <?php
        switch($func){
        case show:
            echo "<div class='ctxt f20 b m20 m-t30'>Show Reports</div>";
            include_once("../kilij/includes/show_reports.php");
            break;
        case add:
            echo "<div class='ctxt f20 b m20 m-t30'>Add Reports</div>";
            include_once("../kilij/includes/add_reports.php");
            break;
        case edit:
            echo "<div class='ctxt f20 b m20 m-t30'>Edit Reports</div>";
            include_once("../kilij/includes/edit_reports.php");
            break;
        case list_10_companies:
            echo "<div class='ctxt f20 b m20 m-t30'>Company List - 10 contrators</div>";
            include_once("../kilij/includes/list_10_companies.php");
            break;
        default:
            echo "<div class='ctxt f20 b m20 m-t30'>Company List</div>";
			echo $message;
			echo "<span class='fl pos-rel m-l30'>
	    		<span class='inline m2 ui-icon ui-icon-search' style='position:absolute; left:0; top:1px;'></span>
	    		<input class='search' style='width:400px; padding-left:15px;' type='text' />
	    	</span>";
              echo "<div class='company_list'></div>";
        break;
    }
    ?>
    </div>
</div>
</center>
</body>
</html>
<?php mysqli_close($conn); ?>