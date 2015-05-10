$(function() {
    $("#acep").addClass("active");
    $("#edit_profile").validationEngine();
    $("#availability_from").datepicker();

    $("#add_video").click(function() {
        var i = $('.edit-form-sub-vid input').size() + 1;
        $('<div class="edit-form-rep" id="v' + i + '"><input type="text" name="video_caption[]"  class="edit-form-sub-input"><input type="file"  name="protfolio_video[]" value="" accept="video/*" capture="camcorder" class="edit-file" /> <a href="javascript:void(0)" id="' + i + '" class="del_v">Remove</a></div>').fadeIn('slow').appendTo('#video');
    });

    $("#add_image").click(function() {
        var i = $('.edit-form-sub-ima input').size() + 1;
        $('<div class="edit-form-rep" id="p' + i + '"><input type="text" class="edit-form-sub-input" name="image_caption[]"><input type="file" name="protfolio_image[]" accept="image/*" capture="camcorder" class="edit-file" > <a href="javascript:void(0)" id="' + i + '" class="del_i">Remove</a></div>').fadeIn('slow').appendTo('#image');
    });

    $("#add_doc").click(function() {
        var i = $('.edit-form-sub-doc input').size() + 1;
        //When use the remove then use the below coted line;
        $('<div class="edit-form-rep" id="d' + i + '"><input type="text" class="edit-form-sub-input" name="doc_caption[]"><input type="file" class="edit-file" name="protfolio_doc[]"> <a href="javascript:void(0)" id="' + i + '" class="del">Remove</a></div>').fadeIn('slow').appendTo('#doc');
    });

    $(document).delegate(".del","click", function() {
        var id = $(this).attr('id');
        $("#d" + id).css("background", "red");
        $("#d" + id).fadeOut('slow', function() { $(this).remove(); });
    });

    $(document).delegate(".del_i","click", function() {
        var id = $(this).attr('id');
        $("#p" + id).css("background", "red");
        $("#p" + id).fadeOut('slow', function() { $(this).remove(); });
    });

    $(document).delegate(".del_v","click", function() {
        var id = $(this).attr('id');
        $("#v" + id).css("background", "red");
        $("#v" + id).fadeOut('slow', function() { $(this).remove(); });
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