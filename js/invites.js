$(function() {
    $("#jsin").addClass("active");
    $(".filter").click(function() {
        var type = $(this).attr("id");
        $.ajax({
            type: "post",
            url: "filterbidlist.php",
            data: { type: type },
            success: function(data) {
                $(".bid-div").html(data);
            },
            error: function(data) {
                alert('Ajax Failed : ' + data);
            }
        });

    });


    //--------------------
    var type = "ALL";
    $.ajax({
        type: "post",
        url: "filterbidlist.php",
        data: { type: type },
        success: function(data) {
            $(".employe-div").html(data);
        },
        error: function(data) {
            alert('Ajax Failed : ' + data);
        }
    });


});