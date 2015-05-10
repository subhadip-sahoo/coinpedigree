//<![CDATA[
$(function() {

    var map;
    var markers = [];
    var infoWindow;
    var sp_id = "";
    var optionTexts = [];
    var address = "";
    var radius = "";
    var experience = "";
    var a = "";
    var cat_id = "";
    var availbility = "";
    
    map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(40, -100),
        mapTypeId: 'roadmap',
        zoom: 16
    });

    infoWindow = new google.maps.InfoWindow();
    
    //******************on page landing search jobseekers start***************
    
    var ck = $("#keywords").children().length;
	if(ck == 0){
		$("#clrkeywords").hide();
	} else {
		$("#clrkeywords").show();
	}
	
	$("#availability_from").datepicker();
    
    pickvalues();
    searchLocations();
    
    //*****************on page landing search jobseekers end********************
    
    //****************on keyword click start **********************************
    
    $("#filter_button").on("click",function(){
    	
    	var valid = $("#inp_keyword").validationEngine("validate");

		if(!valid){
			var in_key = $("#inp_keyword").val();
			$("#keywords").append("<li><img class='key-close' src='images/key-close.png' alt=''><p>"+ in_key +"</p></li>");
			
			$("#inp_keyword").val("");
			
			pickvalues();
    		searchLocations();
			
			ck = ck + 1;
			if(ck == 0){
				$("#clrkeywords").hide();
			} else {
				$("#clrkeywords").show();
			}

		}
		
    });
    
    //****************on keyword click end ************************************
    
    //******************on clearall keywords click start************************
    
    $("#clrkeywords").click(function(){
		$("#keywords").children().remove();
		$(this).hide();
		
		pickvalues();
		searchLocations();
	});
	
    //****************on clearall keywords click end*****************************
    
    //****************on each keyword close start********************************
    
	$(document).delegate(".key-close","click",function(){
		$(this).parent().remove();
		ck = ck - 1;
		if(ck == 0){
			$("#clrkeywords").hide();
		} else {
			$("#clrkeywords").show();
		}
		
		pickvalues();
		searchLocations();
	});
	
	//****************on each keyword close end********************************
    
    
    //****************on search click start ***********************************
    
    $("#search_contractor").on("click",function(){
    	pickvalues();
    	searchLocations();
    });
    
    //****************on search click end **************************************
    
    //****************on occupation click start**********************************
    
    $(".occcatopt").on("click",function(){
    	aval = $(this).attr("id").split("_")[1];
    	sp_val = $(this).attr("id").split("_")[2];
    	cat_val = $(this).attr("id").split("_")[3];
    	
    	$("#a").val(aval);
    	$("#occupation_cat_key").val(cat_val);
    	$("#occupation_key").val(sp_val);
    	
    	pickvalues();
    	searchLocations();
    });
    
    //****************on occupation click end*************************************
    
    

    //*********************************************************************
    function searchLocations() {
        var address = $("#addressInput").val();
        if (address) {
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: address }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    searchLocationsNear(results[0].geometry.location);
                } else {
                    alert(address + ' not found');
                }
            });
        } else {
            searchAllLocations();
        }
    }
    //*********************************************************************
    
    //*********************************************************************
    function clearLocations() {
        infoWindow.close();
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers.length = 0;
    }
    //**********************************************************************
    
    //************************************************************************
    
    //shortlist individually
    $(document).delegate(".indi_shrt","click",function(){
    	var i = $(this).attr("vl");
    	var act = "singleshortlist";
    	$("#act").val(act);
    	$("#hires").val(i);
    	$("#request-form").attr("action", "invite_jobseeker.php");
    	$("#request-form").submit();
    });
    
    //shortlist selected
    $(document).delegate(".shortlist_selected","click",function(){
    	var act = "multishortlist";
    	$("#act").val(act);
    	$("#request-form").attr("action", "invite_jobseeker.php");
    	$("#request-form").validationEngine();
    	$("#request-form").submit();
    });
    
    //freepost individually
    $(document).delegate(".hirefree","click",function(){
    	var i = $(this).attr("vl");
    	var act = "singlehire";
    	$("#act").val(act);
    	$("#hires").val(i);
    	$("#request-form").attr("action", "hire_jobseeker.php");
    	$("#request-form").submit();
    });
    
  //freepost selected
    $(document).delegate(".hire_selected","click",function(){
    	var act = "multihire";
    	$("#act").val(act);
    	$("#request-form").attr("action", "hire_jobseeker.php");
    	$("#request-form").validationEngine();
    	$("#request-form").submit();
    });
    
    //************************************************************************
    
    //***********************************************************************
    function searchLocationsNear(center) {

        clearLocations();
        var clat = center.lat();
        var clatf = clat.toFixed(3);
        var clng = center.lng();
        var clngf = clng.toFixed(3);
        var filter_key = optionTexts;
        var r = radius;
        var searchUrl = 'jobseekerlocator.php?type=nearby&lat=' + clatf + '&lng=' + clngf + '&radius=' + r + '&fkey=' + filter_key + '&cat=' + sp_id + "&exp=" + experience + "&zip=" + address + "&a=" + a + "&ctgry=" + cat_id + "&availbility=" + availbility;

        downloadUrl(searchUrl, function(data) {
            var xml = parseXml(data);
            var list = "";
            var contrlist = document.getElementById("cont_res");
            var shrtbtn = document.getElementById("shrt-hire-btns");
            
            var status = xml.documentElement.getElementsByTagName("msgs");
            var message = status[0].getAttribute("msg");
            if (message == "success") {
                var markerNodes = xml.documentElement.getElementsByTagName("marker");
                var bounds = new google.maps.LatLngBounds();
                for (var i = 0; i < markerNodes.length; i++) {
                    var id_jobseeker = markerNodes[i].getAttribute("id_jobseeker");
                    var name = markerNodes[i].getAttribute("name");
                    var address = markerNodes[i].getAttribute("address");
                    var about = markerNodes[i].getAttribute("about");
                    var email = markerNodes[i].getAttribute("email");
                    var avl_frm = markerNodes[i].getAttribute("avl_frm");
                    var experience = markerNodes[i].getAttribute("experience");
                    var occupation = markerNodes[i].getAttribute("occupation");
                    var imgsrc = markerNodes[i].getAttribute("imgsrc");
                    var latlng = new google.maps.LatLng(
          parseFloat(markerNodes[i].getAttribute("lat")),
          parseFloat(markerNodes[i].getAttribute("lng")));

                    createMarker(latlng, name, address, email, id_jobseeker);
                    bounds.extend(latlng);
                    if (markerNodes.length == 1) {
                        messages = markerNodes.length + " Job Seeker Found";
                    } else {
                        messages = markerNodes.length + " Job Seekers Found";
                    }
                    
                    if(experience > 1){
                    	var yrs = "years";
                    } else {
                    	yrs = "year";
                    }

                    //disparea.innerHTML = messages;
                    list += '<div class="search-min"> \
                        <div class="search-details"> \
                    <div class="check-bt"> \
                    	<div class="Ist_check"> \
                            <input type="checkbox" class="Ist_toggle validate[minCheckbox[1]]" name="jobseekers_list[]" value="' + id_jobseeker + '" /> \
                            <div class="Ist_check_img"><img src="images/check.png" ></div> \
                        </div> \
                    </div> \
                    <!--search-det-left--> \
                    <div class="search-det-left"> \
                        <div class="search-div"> \
                            <div class="section3 search"> \
                    	<img src="' + imgsrc + '" alt="" /> \
                                <div class="name-text"> \
                                    <a target="_blank" href="viewjobseekerprofile.php?s=' + id_jobseeker + '">' + name + '</a> \
                                </div> \
                                <div class="search-div search-with-image"> \
                                    <p>' + occupation + '</p> \
                                </div> \
                            </div> \
                        </div> \
                        <div class="experience"> \
                    	<h3>Experience: <span>' + experience + ' ' + yrs + '</span></h3> \
                    		<p>' + about + '</p> \
                        </div> \
                    	</div> \
                    <!--search-det-left--> \
                    <!--search-right start--> \
                    <div class="search-right"> \
                        <h4>Available From</h4> \
                        <p>' + avl_frm + '</p> \
                        <div class="hire-div"> \
                        	<input type="button" vl="'+ id_jobseeker +'" value="Shortlist" class="short-list-btn indi_shrt"></div> \
                        <div class="hire-div">	\
                        	<input type="button" vl="'+ id_jobseeker +'" value="Hire" class="hire-free-btn-2 hirefree"></div> \
                    	<div class="more-btn"> \
                            <a target="_blank" href="viewjobseekerprofile.php?s=' + id_jobseeker + '">More info</a> \
                        </div> \
                    </div> \
                    <!--search-right end--> \
                   </div> \
                 </div>';
                    
                    contrlist.innerHTML = list;
                    shrtbtn.style.display = 'block';
                }
                map.fitBounds(bounds);
            } else {
            	list += '<div class="search-min"> \
            		<div class="search-details"> \
            			<div class="search-det-left"> \
    						<b>No Results Found</b> \
            			</div> \
    				</div> \
            	</div>';
                contrlist.innerHTML = list;
                shrtbtn.style.display = 'none';
            }
        });
    }
    
    //*****************************************************************************************
    function searchAllLocations() {
        clearLocations();
        var r = radius;
        var filter_key = optionTexts;
        var searchUrl = 'jobseekerlocator.php?type=all&radius=' + r + '&fkey=' + filter_key + '&cat=' + sp_id + "&exp=" + experience + "&a=" + a + "&ctgry=" + cat_id + "&availbility=" + availbility;

        downloadUrl(searchUrl, function(data) {
            var xml = parseXml(data);
            var list = "";
            var contrlist = document.getElementById("cont_res");
            var shrtbtn = document.getElementById("shrt-hire-btns");
            
            var status = xml.documentElement.getElementsByTagName("msgs");
            var message = status[0].getAttribute("msg");
            
            if (message == "success") {
                var markerNodes = xml.documentElement.getElementsByTagName("marker");
                var bounds = new google.maps.LatLngBounds();
                for (var i = 0; i < markerNodes.length; i++) {
                	var id_jobseeker = markerNodes[i].getAttribute("id_jobseeker");
                    var name = markerNodes[i].getAttribute("name");
                    var address = markerNodes[i].getAttribute("address");
                    var about = markerNodes[i].getAttribute("about");
                    var email = markerNodes[i].getAttribute("email");
                    var avl_frm = markerNodes[i].getAttribute("avl_frm");
                    var experience = markerNodes[i].getAttribute("experience");
                    var occupation = markerNodes[i].getAttribute("occupation");
                    var imgsrc = markerNodes[i].getAttribute("imgsrc");
                    var latlng = new google.maps.LatLng(
          parseFloat(markerNodes[i].getAttribute("lat")),
          parseFloat(markerNodes[i].getAttribute("lng")));

                    createMarker(latlng, name, address, email, id_jobseeker);
                    bounds.extend(latlng);
                    if (markerNodes.length == 1) {
                        messages = markerNodes.length + " Job Seeker Found";
                    } else {
                        messages = markerNodes.length + " Job Seekers Found";
                    }
                    
                    if(experience > 1){
                    	var yrs = "years";
                    } else {
                    	yrs = "year";
                    }
                    
                    list += '<div class="search-min"> \
                        <div class="search-details"> \
                        <div class="check-bt"> \
                        	<div class="Ist_check"> \
                                <input type="checkbox" class="Ist_toggle validate[minCheckbox[1]]" name="jobseekers_list[]" value="' + id_jobseeker + '" /> \
                                <div class="Ist_check_img"><img src="images/check.png" ></div> \
                            </div> \
                        </div> \
                        <!--search-det-left--> \
                        <div class="search-det-left"> \
                            <div class="search-div"> \
                                <div class="section3 search"> \
                        	<img src="' + imgsrc + '" alt="" /> \
                                    <div class="name-text"> \
                                        <a target="_blank" href="viewjobseekerprofile.php?s=' + id_jobseeker + '">' + name + '</a> \
                                    </div> \
                                    <div class="search-div search-with-image"> \
                                        <p>' + occupation + '</p> \
                                    </div> \
                                </div> \
                            </div> \
                            <div class="experience"> \
                        	<h3>Experience: <span>' + experience + ' ' + yrs + '</span></h3> \
                        		<p>' + about + '</p> \
                            </div> \
                        	</div> \
                        <!--search-det-left--> \
                        <!--search-right start--> \
                        <div class="search-right"> \
                            <h4>Available From</h4> \
                            <p>' + avl_frm + '</p> \
                            <div class="hire-div"> \
                            	<input type="button" vl="'+ id_jobseeker +'" value="Shortlist" class="short-list-btn indi_shrt"></div> \
                            <div class="hire-div">	\
                            	<input type="button" vl="'+ id_jobseeker +'" value="Hire" class="hire-free-btn-2 hirefree"></div> \
                        	<div class="more-btn"> \
                                <a target="_blank" href="viewjobseekerprofile.php?s=' + id_jobseeker + '">More info</a> \
                            </div> \
                        </div> \
                        <!--search-right end--> \
                       </div> \
                     </div>';
                    contrlist.innerHTML = list;
                    shrtbtn.style.display = 'block';
                }
                map.fitBounds(bounds);
            } else {
            	list += '<div class="search-min"> \
            		<div class="search-details"> \
            			<div class="search-det-left"> \
    						<b>No Results Found</b> \
            			</div> \
    				</div> \
            	</div>';
                contrlist.innerHTML = list;
                shrtbtn.style.display = 'none';
            }
        });
    }

    //***************************************************************************
    function createMarker(latlng, name, address, email, id_jobseeker) {
        var html = "<div><b>" + name + "</b> <br/>" + address + "<br/>" + email + "<br/><a target='_blank' href='viewjobseekerprofile.php?s=" + id_jobseeker + "' >Profile&nbsp;&nbsp;&nbsp;</a> <a href='invite_jobseeker.php?s=" + id_jobseeker + "'>Shortlist</a></div>";
        var marker = new google.maps.Marker({ map: map, position: latlng });
        
        var listener = google.maps.event.addListener(map, "idle", function() {
  		  if (map.getZoom() > 16) map.setZoom(16); 
  		  google.maps.event.removeListener(listener);
  		});
        
        google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
        });
        markers.push(marker);
    }
    
    //********************************************************************************
    function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
      new ActiveXObject('Microsoft.XMLHTTP') :
      new XMLHttpRequest;

        request.onreadystatechange = function() {
            if (request.readyState == 4) {
                request.onreadystatechange = doNothing;
                callback(request.responseText, request.status);
            }
        };

        request.open('GET', url, true);
        request.send(null);
    }
    
    //************************************************************************************
    function parseXml(str) {
        if (window.ActiveXObject) {
            var doc = new ActiveXObject('Microsoft.XMLDOM');
            doc.loadXML(str);
            return doc;
        } else if (window.DOMParser) {
            return (new DOMParser).parseFromString(str, 'text/xml');
        }
    }
    
    
    //**************************************************************************************
    function pickvalues(){

    	optionTexts = [];
    	//pickup list of keywords
	    $("#keywords > li").each(function() { 
	    		optionTexts.push($(this).text()); 
	    });
	    
	    //pickup zipcode
	    address = $("#addressInput").val();
	    
	    //pickup radius
	    radius = $("#radiusSelect").val();
	    
	    //pickup occupation
	    sp_id = $("#occupation_key").val();
	    
	    //pickup occupation category
	    cat_id = $("#occupation_cat_key").val();
	    
	    //pickup experience
	    experience = $("#experience").val();
	    
	    //pickup active
	    a = $("#a").val();
	    
	    //pickup availbility_from
	    availbility = $("#availability_from").val();
	    
    }
    
    //********************************************************************************************
    function doNothing() { }
});
//]]>