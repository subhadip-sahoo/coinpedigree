$(function(){
	$("#home").addClass("active");
	$("#search_form").validationEngine({scroll: false});
	
	$("#search_btn").click(function(){
		var valid = (!($("#occupation").validationEngine("validate")) && !($("#location").validationEngine("validate")));
		if(valid){
			var id_occ = $("#id_occupation").val();
			var id_cat = $("#id_cat").val();
			var loc = $("#location").val();
			location.href = "search_jobseekers.php?id_occ="+id_occ+"&id_cat="+id_cat+"&zipcode="+loc;
		}
	});
	
	$( "#occupation" ).catcomplete({
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
	
	$(window).resize(function() {
		//location.href="index.php";
	});
	
});

$.widget( "custom.catcomplete", $.ui.autocomplete, {
	_renderMenu: function( ul, items ) {
		var that = this,
		currentCategory = "";
		$.each( items, function( index, item ) {
			if ( item.category != currentCategory ) {
				ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
				currentCategory = item.category;
			}
			that._renderItemData( ul, item );
		});
	}
});