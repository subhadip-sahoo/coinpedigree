$(function(){
	//to active the menu
	$("#jshm").addClass("active");

	//on page load populate invites
	var type = "ALL";
	$.ajax({
		type	: "post",
		url		: "filterbidlist.php",
		data	: {type:type},
		success : function(data){
			$(".bid-div").html(data);
			$("#currentlist").text("All Jobs");
		},
		error	: function(data){
			alert('Ajax Failed : '+ data);
		}
	});
	
	//on filter click populate invites
	$(".filter").click(function(){
		var type = $(this).attr("id");
		$.ajax({
			type	: "post",
			url		: "filterbidlist.php",
			data	: {type:type},
			success : function(data){
				$(".bid-div").html(data);
				if(type == "ALL"){
					$("#currentlist").text("All Jobs");
				}
				if(type == "A"){
					$("#currentlist").text("Open Invites");
				}
				if(type == "C"){
					$("#currentlist").text("Closed Invites");
				}
				if(type == "H"){
					$("#currentlist").text("Hired");
				}
				if(type == "HO"){
					$("#currentlist").text("Hire Offers");
				}
			},
			error	: function(data){
				alert('Ajax Failed : '+ data);
			}
		});
		
	});
});