<!DOCTYPE HTML>

<html lang="en">

<head>

<meta charset='utf-8'>

<title>SWIPE GALLERY - HTML 5 IMAGE GALLERY</title>

 <?php
	function sortmulti ($array, $index, $order, $natsort=FALSE, $case_sensitive=FALSE) {
		if(is_array($array) && count($array)>0) {
			foreach(array_keys($array) as $key)
			$temp[$key]=$array[$key][$index];
			if(!$natsort) {
				if ($order=='asc')
					asort($temp);
				else   
					arsort($temp);
			}
			else
			{
				if ($case_sensitive===true)
					natsort($temp);
				else
					natcasesort($temp);
			if($order!='asc')
				$temp=array_reverse($temp,TRUE);
			}
			foreach(array_keys($temp) as $key)
				if (is_numeric($key))
					$sorted[]=$array[$key];
				else   
					$sorted[$key]=$array[$key];
			return $sorted;
		}
	return $sorted;
}
	
	$galleryarray = array();
	
	$basedir = "./galleries";
	
	$i=0;
	if( file_exists($basedir) ) {
		if ($handle = opendir($basedir)) {
			
			while (false !== ($file = readdir($handle))) {

					if( mb_substr($file,0,1) != "." ) {
				
						$dname = $basedir . "/" . $file;
						
						$galleryarray[$i]['name'] = mb_strtoupper($file);
						$galleryarray[$i]['path'] = $dname;
						
							if( file_exists($dname) ) {
								if ($handle2 = opendir($dname)) {
									
									$galleryarray[$i]['images'] = array();	
									$j=0;
									while (false !== ($file2 = readdir($handle2))) {
										
										if( mb_substr($file2,0,1) != "." ) {
											$fEnd   = mb_substr($file2,-4);
											$fEnd2  = mb_substr($file2,-6);
											
											if( ($fEnd == ".jpg") && ($fEnd2 != "_t.jpg") ) {
											  
											  $galleryarray[$i]['images'][$j] = mb_substr($file2,0,-4);
											  
											  if( j== 0 ) {
												$galleryarray[$i]['thumbimage'] = mb_substr($file2,0,-4);;
											  }
											  
											  $j++;
											}
										}
									}
								}
							}
						
						$i++;		  
					}
			}
								 
		}                        	
	}
	
	$galleryarray = sortmulti($galleryarray, 'name', 'asc');
	
	for($i=0;$i<count($galleryarray);$i++) {
		sort($galleryarray[$i]['images']);
	}
	
?>

<link rel="stylesheet" type="text/css" href="imagegallery.css"/>

<link rel="icon" type="image/png" href="http://www.talaakso.fi/suomi/pics/talaakso-shortcut-icon.png" /> 
<link rel="apple-touch-icon" href="http://www.talaakso.fi/suomi/pics/talaakso-apple-icon.png" />

<meta http-equiv="Content-Language" content="en"/>
<meta name="copyright" content="Tero Laakso" />

<meta name="description" content="SWIPE GALLERY - HTML 5 IMAGE GALLERY: This is simple demo page to demonstrate how it is possible to create userfriendly user interface which automatically connects to image galleries contained as subfolders on server."/>

<meta name="keywords" content="image gallery,gallery,photo gallery,slideshow,swipe,swipegallery,touchSwipe,demo,ipad,ipad2,android,tablet,html5,javascript,php,TweenMax" />
    
    <script type="text/javascript" src="./js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="./js/jquery.touchSwipe.js"></script>
    <script src="./js/TweenMax.min.js"></script>
    
    <script type="text/javascript">
          var oldIE = 0;
    </script>
    
    <!--[if lt IE 9 ]> 
    	<script type="text/javascript">
          var oldIE = 1;
        </script>
    
    <![endif]-->
    
    <script type="text/javascript">
	
	 	<?php
					
			echo "var galleryarray    = new Array();";
			echo "var galleryIndex    = 0;";
			echo "var imageIndex      = 0;";
			echo "var galleryLength   = 0;";
			echo "var currentImage    = null;";
			
		?>
				
		function logoClick(document)
		{
			logoicon = document.getElementById("logoicon");
			
			TweenMax.to(logoicon, 0.35, {css:{scaleX:1.25, scaleY:1.25, rotation:0}, ease:Power3.easeOut});
			
			for(j=0;j<galleryarray.length;j++) {
			  icon1    = document.getElementById("icon"+j);
			  TweenMax.to(icon1, 0.35, {css:{scaleX:1, scaleY:1, rotation:0}, ease:Power3.easeOut});
			}
			
			$('#iframe1').hide();
			$('#iframe0').fadeIn(1500);
		}
		
		function iconClick(index)
		{
			logoicon = document.getElementById("logoicon");
			icon0    = document.getElementById("icon"+index);
			
			TweenMax.to(icon0, 0.35, {css:{scaleX:1.25, scaleY:1.25, rotation:0}, ease:Power3.easeOut});
			TweenMax.to(logoicon, 0.35, {css:{scaleX:1, scaleY:1, rotation:0}, ease:Power3.easeOut});
			
			for(j=0;j<galleryarray.length;j++) {
			  icon1    = document.getElementById("icon"+j);
			  if( j != index ) {
			  	TweenMax.to(icon1, 0.35, {css:{scaleX:1, scaleY:1, rotation:0}, ease:Power3.easeOut});
			  }
			}
			
			galleryIndex = index; 
			imageIndex   = 0;
			
			imgName = galleryarray[galleryIndex]['images'][imageIndex];
			imgName = imgName.replace(/_/g," ");		
			document.getElementById('img_header1').innerHTML = imgName;
			
			galleryLength = galleryarray[galleryIndex]['images'].length;
			
			imageIndex2 = imageIndex + 1;
			imgCounter  = imageIndex2 + " / " + galleryLength;
			document.getElementById('img_counter1').innerHTML = imgCounter;
				
			fName = galleryarray[galleryIndex]['path'] + "/" + galleryarray[galleryIndex]['images'][imageIndex] + ".jpg";
			document.getElementById('iframe1_img').src = fName;
			   
			$('#iframe1').fadeIn(1500);
			$('#iframe0').hide();
		}
		
		function nextClick()
		{
			imageIndex++;
			if( imageIndex >= galleryLength ) {
			  imageIndex = 0;
			}
			
			imgName = galleryarray[galleryIndex]['images'][imageIndex];
			imgName = imgName.replace(/_/g," ");
			document.getElementById('img_header1').innerHTML = imgName;
			
			fName = galleryarray[galleryIndex]['path'] + "/" + galleryarray[galleryIndex]['images'][imageIndex] + ".jpg";
			document.getElementById('iframe1_img').src = fName;
			
			imageIndex2 = imageIndex + 1; 
			imgCounter  = imageIndex2 + " / " + galleryLength;
			document.getElementById('img_counter1').innerHTML = imgCounter;
			
		}
		
		function prevClick()
		{
			imageIndex--;
			if( imageIndex < 0 ) {
			  imageIndex = galleryLength - 1;
			}
			
			imgName = galleryarray[galleryIndex]['images'][imageIndex];
			imgName = imgName.replace(/_/g," ");
			document.getElementById('img_header1').innerHTML = imgName;
			
			fName = galleryarray[galleryIndex]['path'] + "/" + galleryarray[galleryIndex]['images'][imageIndex] + ".jpg";
			document.getElementById('iframe1_img').src = fName;
			
			imageIndex2 = imageIndex + 1;
			imgCounter = imageIndex2 + " / " + galleryLength;
			document.getElementById('img_counter1').innerHTML = imgCounter;
			
		}
		
		function imageLoaded()
		{
			ht = document.getElementById('iframe1_img').height;
			wi = document.getElementById('iframe1_img').width;
			
			if( ht > wi ) {
				$('#iframe1_img').removeClass('iframe_img');
				$('#iframe1_img').addClass('iframe_img_portrait');
			}
			else {
				$('#iframe1_img').removeClass('iframe_img_portrait');
				$('#iframe1_img').addClass('iframe_img');
			}
		}
		
		function checkKey(e) {

			e = e || window.event;
			
			if (e.keyCode == '37') {
				
				imageIndex--;
				if( imageIndex < 0 ) {
				  imageIndex = galleryLength - 1;
				}
				
				imgName = galleryarray[galleryIndex]['images'][imageIndex];
				imgName = imgName.replace(/_/g," ");
				document.getElementById('img_header1').innerHTML = imgName;
				
				fName = galleryarray[galleryIndex]['path'] + "/" + galleryarray[galleryIndex]['images'][imageIndex] + ".jpg";
				document.getElementById('iframe1_img').src = fName;
				
				imageIndex2 = imageIndex + 1;
				imgCounter = imageIndex2 + " / " + galleryLength;
				document.getElementById('img_counter1').innerHTML = imgCounter;
			}
			else if (e.keyCode == '38') {
				
				imageIndex = 0;
				galLen = galleryarray.length;
				
				galleryIndex--;
				if( galleryIndex < 0 ) {
				  galleryIndex = 0;
				  logoClick();
				}
				else {	
				  iconClick(galleryIndex);
				}				
			}
			else if (e.keyCode == '39') {
				imageIndex++;
				if( imageIndex >= galleryLength ) {
				  imageIndex = 0;
				}
				
				imgName = galleryarray[galleryIndex]['images'][imageIndex];
				imgName = imgName.replace(/_/g," ");
				document.getElementById('img_header1').innerHTML = imgName;
				 
				fName = galleryarray[galleryIndex]['path'] + "/" + galleryarray[galleryIndex]['images'][imageIndex] + ".jpg";
				document.getElementById('iframe1_img').src = fName;
				
				imageIndex2 = imageIndex + 1;
				imgCounter = imageIndex2 + " / " + galleryLength;
				document.getElementById('img_counter1').innerHTML = imgCounter;
				
			}
			else if (e.keyCode == '40') {
			
				if( $('#iframe0').is(":visible") ) {
					imageIndex   = 0;
					galleryIndex = 0;
					
					iconClick(galleryIndex);
				}
				else {
					imageIndex = 0;
					galLen = galleryarray.length;
					
					galleryIndex++;
					if( galleryIndex >= galLen ) {
					  galleryIndex =  galLen - 1 ;
					}
					else {
					  iconClick(galleryIndex);
					}
				}
						
			}
		}
	
		$(function() {
			
			<?php 
			  echo "galleryIndex = 0;";
			  echo "imageIndex   = 0;";
			
			  for($i=0;$i<count($galleryarray);$i++) {				
				echo "galleryarray[" . $i . "] = {};";
				echo "galleryarray[" . $i . "]['name'] = '" . $galleryarray[$i]['name'] . "';";
				echo "galleryarray[" . $i . "]['path'] = '" . $galleryarray[$i]['path'] . "';";
				echo "galleryarray[" . $i . "]['thumbimage'] = '" . $galleryarray[$i]['thumbimage'] . "';";
				echo "galleryarray[" . $i . "]['images'] = new Array();";
				
				for($j=0;$j<count($galleryarray[$i]['images']);$j++) {
				  echo "galleryarray[" . $i . "]['images'][" . $j . "] = '" . $galleryarray[$i]['images'][$j] . "';";
				}
			  }
			?>
			
			if( oldIE != 1 ) {
			
				$("#iframe0").swipe( {
					//Generic swipe handler for all directions
					swipeUp:function(event, direction, distance, duration, fingerCount) {
					  imageIndex   = 0;
					  galleryIndex = 0;
						
					  iconClick(galleryIndex);  
					},
					//Default is 75px, set to 0 for demo so any distance triggers swipe
					threshold:0
				  });
				  
				  $("#iframe1").swipe( {
					//Generic swipe handler for all directions
					swipeUp:function(event, direction, distance, duration, fingerCount) {
					   imageIndex = 0;
						galLen = galleryarray.length;
						
						galleryIndex++;
						if( galleryIndex >= galLen ) {
						  galleryIndex =  galLen - 1 ;
						}
						else {
						  iconClick(galleryIndex);
						}
					},
					
					swipeDown:function(event, direction, distance, duration, fingerCount) {
						imageIndex = 0;
						galLen = galleryarray.length;
						
						galleryIndex--;
						if( galleryIndex < 0 ) {
						  galleryIndex = 0;
						  logoClick();
						}
						else {	
						  iconClick(galleryIndex);
						}  
						
					},
					
					swipeRight:function(event, direction, distance, duration, fingerCount) {
						imageIndex--;
						if( imageIndex < 0 ) {
						  imageIndex = galleryLength - 1;
						}
						
						imgName = galleryarray[galleryIndex]['images'][imageIndex];
						imgName = imgName.replace(/_/g," ");
						document.getElementById('img_header1').innerHTML = imgName;
						
						fName = galleryarray[galleryIndex]['path'] + "/" + galleryarray[galleryIndex]['images'][imageIndex] + ".jpg";
						document.getElementById('iframe1_img').src = fName;
						
						imageIndex2 = imageIndex + 1;
						imgCounter = imageIndex2 + " / " + galleryLength;
						document.getElementById('img_counter1').innerHTML = imgCounter;
					},
					
					swipeLeft:function(event, direction, distance, duration, fingerCount) {
						imageIndex++;
						if( imageIndex >= galleryLength ) {
						  imageIndex = 0;
						}
						
						imgName = galleryarray[galleryIndex]['images'][imageIndex];
						imgName = imgName.replace(/_/g," ");
						document.getElementById('img_header1').innerHTML = imgName;
						 
						fName = galleryarray[galleryIndex]['path'] + "/" + galleryarray[galleryIndex]['images'][imageIndex] + ".jpg";
						document.getElementById('iframe1_img').src = fName;
						
						imageIndex2 = imageIndex + 1;
						imgCounter = imageIndex2 + " / " + galleryLength;
						document.getElementById('img_counter1').innerHTML = imgCounter;
					},
					
					//Default is 75px, set to 0 for demo so any distance triggers swipe
					threshold:75
				  });
			}
			
			document.onkeydown = checkKey;
			logoClick(document);
		
		});
	
	</script>
</head>

<body class="mainBody" >

   <img class="bg" src="./background/main_bg_5.jpg" alt="">
   
   <div id="mainTable">
		
		<h1 id="page_header">
			www.talaakso.fi
		</h1>
       
       <div class='mainPage' id='PageDiv' >
       	
            <div id='iframe0' class='pageIDiv'>
                
                <h1 class='page_text_h2'>SWIPE GALLERY - IMAGE GALLERY</h1>
                
                <p class='page_text_normal'>
                    
                    This is simple demo page to demonstrate how it is possible to create userfriendly user interface which automatically
                    connects to image galleries contained as subfolders on your own server space. 
                    <br /><br />
                    
                    To make things simpler for maintenance, no database is used to store image information. 
                    Image filenames are used as image titles and gallery folder names as gallery titles.                    
                    <br /><br />
                    
                    These galleries are specially designed to be used on iPad 2 touchscreen as the images scale nicely on iPad 2 screen
                    and one can use swipe gestures to navigate between (up-down) and inside galleries (left-right). One can also navigate
                    using arrow keys or by clicking icons.
                    <br /><br />
                    
                    USED EXTERNAL LIBRARIES: <a href='http://jquery.com/' target='_blank' >Jquery</a>, 
                    <a href='https://github.com/mattbryson/TouchSwipe-Jquery-Plugin' target='_blank' >TouchSwipe Jquery Plugin</a>
                    and <a href='http://www.greensock.com/get-started-js/' target='_blank' >TweenMax</a>.
					<br /><br />
                    
					Begin by clicking one of the gallery icon on the left, by swiping up or by pressing arrow down.
                    <br /><br />
                    
                </p>
            </div>
            
            <div id='iframe1' class='pageIDiv2'>
                
                <h1 id='img_header1' class='page_text_h1'></h1>
                <h1 id='img_counter1' class='page_text_counter'></h1>
                
                <img id='img_next1' onclick="nextClick();" class='img_navigator' src='./gfx/next.png' alt='Next' />
                <img id='img_prev1' onclick="prevClick();" class='img_navigator2' src='./gfx/prev.png' alt='Prev' />
                
                <img id='iframe1_img' class='iframe_img' onload='imageLoaded();' src='' alt='' />
                
                <div style="clear:both;"></div>
                
            </div>
                 
      </div>         
      
      <div class='icondiv' id='iconbar'>
            		
            <img id="logoicon" class="header_logo_image" onclick="logoClick();" src="./gfx/lehtilogo-3.png" alt="" /><br />
            
            <?php
                for($i=0;$i<count($galleryarray);$i++) {
                    echo "<img class='logo_image' id='icon" . $i . "' onclick='iconClick(" . $i . ");' title='" . $galleryarray[$i]['name'] . "' src='" . $galleryarray[$i]['path'] . "/" .  $galleryarray[$i]['images'][0] . "_t.jpg' alt='' /><br />";
                }
            
            ?>

            <div style="clear:both;"></div>
            
      </div>
          
  </div>     

</body>
</html>

