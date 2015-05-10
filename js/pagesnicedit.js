$(function() {
	var about_us = new nicEditor
    ({
        buttonList:
	    ['bold',
        'italic',
        'underline',
        'left',
        'center',
        'right',
        'justify',
        'ol',
        'ul',
        'subscript',
        'superscript',
        'strikethrough',
        'removeformat',
        'indent',
        'outdent',
        'hr',
        'image',
        'forecolor',
        'bgcolor',
        'link',
        'unlink',
        'fontSize',
        'fontFamily',
        'fontFormat',
        'xhtml'],
        iconsPath: '../images/nicEditorIcons.gif'
    }).panelInstance('about_us_content');

    $("#about_us_save").click(function() {
    var content_about_us = about_us.instanceById('about_us_content').getContent();
    string = "key=" + content_about_us;
        $.ajax({
            type: "POST",
            url: "pageservice.php?type=about_us",
            data: string,
            cache: false,
            success: function(data) {
                alert("success");
            }
        });
    });

    var how_we_works = new nicEditor
    ({
        buttonList:
	    ['bold',
        'italic',
        'underline',
        'left',
        'center',
        'right',
        'justify',
        'ol',
        'ul',
        'subscript',
        'superscript',
        'strikethrough',
        'removeformat',
        'indent',
        'outdent',
        'hr',
        'image',
        'forecolor',
        'bgcolor',
        'link',
        'unlink',
        'fontSize',
        'fontFamily',
        'fontFormat',
        'xhtml'],
        iconsPath: '../images/nicEditorIcons.gif'
    }).panelInstance('how_we_works_content');

    $("#how_we_works_save").click(function() {
        var content_how_we_works = how_we_works.instanceById('how_we_works_content').getContent();
        string = "key=" + content_how_we_works;
        $.ajax({
            type: "POST",
            url: "pageservice.php?type=how_we_works",
            data: string,
            cache: false,
            success: function(data) {
                alert("success");
            }
        });
    });
	
	var terms_and_conditions_homeowners = new nicEditor
    ({
        buttonList:
	    ['bold',
        'italic',
        'underline',
        'left',
        'center',
        'right',
        'justify',
        'ol',
        'ul',
        'subscript',
        'superscript',
        'strikethrough',
        'removeformat',
        'indent',
        'outdent',
        'hr',
        'image',
        'forecolor',
        'bgcolor',
        'link',
        'unlink',
        'fontSize',
        'fontFamily',
        'fontFormat',
        'xhtml'],
        iconsPath: '../images/nicEditorIcons.gif'
    }).panelInstance('terms_and_conditions_homeowners_content');

    $("#terms_and_conditions_homeowners_content_save").click(function() {
    var content_terms_and_conditions_homeowners_content = terms_and_conditions_homeowners.instanceById('terms_and_conditions_homeowners_content').getContent();
    string = "key=" + content_terms_and_conditions_homeowners_content;
        $.ajax({
            type: "POST",
            url: "pageservice.php?type=terms_and_conditions_homeowners_content",
            data: string,
            cache: false,
            success: function(data) {
                alert("success");
            }
        });
    });

    var terms_and_conditions_contractors = new nicEditor
    ({
        buttonList:
	    ['bold',
        'italic',
        'underline',
        'left',
        'center',
        'right',
        'justify',
        'ol',
        'ul',
        'subscript',
        'superscript',
        'strikethrough',
        'removeformat',
        'indent',
        'outdent',
        'hr',
        'image',
        'forecolor',
        'bgcolor',
        'link',
        'unlink',
        'fontSize',
        'fontFamily',
        'fontFormat',
        'xhtml'],
        iconsPath: '../images/nicEditorIcons.gif'
    }).panelInstance('terms_and_conditions_contractors_content');

    $("#terms_and_conditions_contractors_content_save").click(function() {
        var content_terms_and_conditions_contractors_content = terms_and_conditions_contractors.instanceById('terms_and_conditions_contractors_content').getContent();
        string = "key=" + content_terms_and_conditions_contractors_content;
        $.ajax({
            type: "POST",
            url: "pageservice.php?type=terms_and_conditions_contractors_content",
            data: string,
            cache: false,
            success: function(data) {
                alert("success");
            }
        });
    });
});