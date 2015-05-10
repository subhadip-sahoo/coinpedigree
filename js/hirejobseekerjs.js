$(function() {
    var icounter = 1;
    var vcounter = 1;
    var dcounter = 1;
    
    //delete temporary files if page refresh or change
    $(window).bind("beforeunload pagehide unload", function() {
        var unique_dir = $("#unique_dir").val();
        string = "dirname=" + unique_dir;
        $.ajax({
            type: "get",
            url: "deletetemp.php",
            data: string,
            async: false,
            success: function(data) {
                //return true;
            }
        });
    });
    
    //page requirements
    $("#jshm").addClass("active");
    $('#start_date').datetimepicker({
		showSecond: true,
		dateFormat: 'mm/dd/yy',
		timeFormat: 'HH:mm:ss',
		stepHour: 1,
		stepMinute: 10,
		stepSecond: 10
	});
	
	$('#report_datetime').datetimepicker({
		showSecond: true,
		dateFormat: 'mm/dd/yy',
		timeFormat: 'HH:mm:ss',
		stepHour: 1,
		stepMinute: 10,
		stepSecond: 10
	});
	
	$('#acceptbydt').datetimepicker({
		showSecond: true,
		dateFormat: 'mm/dd/yy',
		timeFormat: 'HH:mm:ss',
		stepHour: 1,
		stepMinute: 10,
		stepSecond: 10
	});
    
    //another attachment
    $("#another_video").click(function() {
        var html = "<p><span><span><input type='file' name='video_file[]' id='v" + vcounter + "' class='videos' accept='video/*' capture='camcorder' /></span></span><span class='progress' id='progress_v" + vcounter + "'></span></p>";
        $("#video_p").append(html);
        vcounter++;
    });

    $("#another_image").click(function() {
        var html = "<p><span><span><input type='file' name='image_file[]' id='i" + icounter + "' class='images' accept='image/*' capture='camera' /></span></span><span class='progress' id='progress_i" + icounter + "'></span></p>";
        $("#image_p").append(html);
        icounter++;
    });

    $("#another_doc").click(function() {
        var html = "<p><span><span><input type='file' name='doc_file[]' id='d" + dcounter + "' class='docs' /></span></span><span class='progress' id='progress_d" + dcounter + "'></span></p>";
        $("#doc_p").append(html);
        dcounter++;
    });
    
    //form validation and submission
    $("#post_job_form").validationEngine();
    $("#postjob").click(function() {

        var title = $("#title").val();
        var description = $("#description").val();
        var start_date = $("#start_date").val();
        var acceptbydt = $("#acceptbydt").val();
        var location_address = $("#location_address").val();
        var location_zip = $("#location_zip").val();
        var reporting_person = $("#reporting_person").val();
        var report_datetime = $("#report_datetime").val();
        var additional_notes = $("#additional_notes").val();
        var positions = $("#positions").val();
        
        var from = $("#from_page").val();
        if (from == "w1hirefree22") {
            targeturl = "hirepost.php";
            datastring = {
            		"from": 			from,
            		"title": 			title,
            		"description": 		description,
            		"start_date": 		start_date,
            		"acceptbydt":		acceptbydt,
            		"location_address": location_address,
            		"location_zip": 	location_zip,
            		"reporting_person":	reporting_person,
            		"report_datetime":	report_datetime,
            		"additional_notes":	additional_notes,
            		"positions":		positions
            	};
        }

        $.ajax({
            type: "POST",
            beforeSend: function() {
                return $("#post_job_form").validationEngine("validate");
            },
            url: targeturl,
            data: datastring,
            cache: false,
            dataType: "json",
            success: function(data) {

                if (data.status == "success") {

                	location.href = "messages.php";

                } else {
                	
                    alert(data.status);
                    
                }
                
            }
        });
    });
    
    //upload attachments in a temporary folder
    $(document).delegate("input[type='file']", "change", function() {
        var unique_dir = $("#unique_dir").val();
        var vtype = $(this).attr("class");
        var vid = $(this).attr("id");
        var f = this.files[0];
        if (f) {
            //working except android default browser
            var xhr = new XMLHttpRequest();

            if (xhr.upload) {

                // create progress bar
                o = document.getElementById("progress_" + vid);
                var progress = o.appendChild(document.createElement("p"));
                progress.appendChild(document.createTextNode(f.name));

                // start upload
                xhr.open("POST", "temp_upload_job_attachment.php", true);

                // progress bar
                xhr.upload.addEventListener("progress", function(e) {
                    if (e.lengthComputable) {
                        var percent = parseInt(e.loaded / e.total * 100);
                        if (percent < 100) {
                            document.getElementById("postjob").disabled = true;
                        }
                        var pc = parseInt(100 - (e.loaded / e.total * 100));
                        progress.style.backgroundPosition = pc + "% 0";
                        progress.innerHTML = percent + "%";
                    } else {
                        alert("not computable");
                    }
                }, false);

                // file received/failed
                xhr.onreadystatechange = function(e) {
                    if (xhr.readyState == 4) {
                        progress.className = (xhr.status == 200 ? "success" : "failed");
                        progress.innerHTML = "uploaded";
                        document.getElementById("postjob").disabled = false;
                    }
                };

                //send file
                xhr.setRequestHeader("X_FILENAME", f.name);
                xhr.setRequestHeader("X_DIRNAME", unique_dir);
                xhr.setRequestHeader("X_FILETYPE", vtype);
                xhr.send(f);
            }

        }
    });
});