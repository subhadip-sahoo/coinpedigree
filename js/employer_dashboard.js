$(function() {
    $("#jshm").addClass("active");
    $(".filter").click(function(){
		var type = $(this).attr("id");
		$.ajax({
			type	: "post",
			url		: "filterjoblist.php",
			data	: {type:type},
			success : function(data){
				$(".bid-div").html(data);
				if(type == "ALL"){
					$("#currentlist").text("All Jobs");
				}
				if(type == "A"){
					$("#currentlist").text("Active Jobs");
				}
				if(type == "C"){
					$("#currentlist").text("Closed Jobs");
				}
				if(type == "H"){
					$("#currentlist").text("Hired Jobs");
				}
				if(type == "HO"){
					$("#currentlist").text("Hired Offers");
				}
			},
			error	: function(data){
				alert('Ajax Failed : '+ data);
			}
		});
		
	});
	
	
	//--------------------
	var type = "ALL";
	$.ajax({
		type	: "post",
		url		: "filterjoblist.php",
		data	: {type:type},
		success : function(data){
			$(".bid-div").html(data);
			$("#currentlist").text("All Jobs");
		},
		error	: function(data){
			alert('Ajax Failed : '+ data);
		}
	});
});