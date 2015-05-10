$(function() {
    var icounter = 1;
    var vcounter = 1;
    var dcounter = 1;
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
    $("#empcj").addClass("active");

    //typeahead
    $("#occupation").catcomplete({
        delay: 0,
        source: "service_page.php?t=oc",
        select: function(event, ui) {
            $("#occupation").val(ui.item ? ui.item.value : this.value);
            $("#id_occupation").val(ui.item ? ui.item.id : this.value);
            $("#id_cat").val(ui.item ? ui.item.id_cat : this.value);
        },
        change: function(event, ui) {
            if (!ui.item) {
                $("#occupation").validationEngine('showPrompt', 'Please select a correct occupation', 'red');
                $("#occupation").val("");
                $("#id_occupation").val("");
                $("#id_cat").val("");
            }
        }
    });

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

    $("#post_job_form").validationEngine();
    $("#postjob").click(function() {
    	var first_response_award_count = "";
        var title = $("#title").val();
        var description = $("#description").val();
        var occupation = $("#id_occupation").val();
        var job_duration = $("#job_duration").val();
        var job_award_type = $("#job_award_type").val();
        if(job_award_type == "F"){
        	first_response_award_count = $("#first_response_award_count").val();
        }
        var response_limit = $("#response_limit").val();
        var zipcode = $("#zipcode").val();

        var from = $("#from_page").val();
        if (from == "w1posta2jobx2") {
            targeturl = "submitpost.php";
            string = "from=" + from + "&title=" + title + "&description=" + description + "&occupation=" + occupation + "&job_duration=" + job_duration + "&response_limit=" + response_limit + "&zipcode=" + zipcode + "&job_award_type=" + job_award_type + "&first_response_award_count=" + first_response_award_count;
        }
        if (from == "w1edita2jobx2") {
            var id_jobs = $("#id_jobs").val();
            targeturl = "editpost.php";
            string = "from=" + from + "&id_jobs=" + id_jobs + "&title=" + title + "&description=" + description + "&occupation=" + occupation + "&job_duration=" + job_duration + "&response_limit=" + response_limit + "&zipcode=" + zipcode + "&job_award_type=" + job_award_type + "&first_response_award_count=" + first_response_award_count;
        }

        $.ajax({
            type: "POST",
            beforeSend: function() {
                return $("#post_job_form").validationEngine("validate");
            },
            url: targeturl,
            data: string,
            cache: false,
            dataType: "json",
            success: function(data) {

                if (data.status == "success") {

                    location.href = "jobdetails.php?j=" + data.jobid;

                } else {
                    alert(data.status);
                }
                
            }
        });
    });

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
                        progress.innerHTML = percent +"%";
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
    
    $("#job_award_type").on("change",function(){
    	var type = $(this).val();
    	if(type == "R"){
    		$("#frc").hide("slow");
    	}
    	if(type == "F"){
    		$("#frc").show("slow");
    	}
	});
    
    var type = $("#job_award_type").val();
	if(type == "R"){
		$("#frc").hide("slow");
	}
	if(type == "F"){
		$("#frc").show("slow");
	}
    
});

//typeahead categorywise
$.widget("custom.catcomplete", $.ui.autocomplete, {
    _renderMenu: function(ul, items) {
        var that = this,
			currentCategory = "";
        $.each(items, function(index, item) {
            if (item.category != currentCategory) {
                ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
                currentCategory = item.category;
            }
            that._renderItemData(ul, item);
        });
    }
});