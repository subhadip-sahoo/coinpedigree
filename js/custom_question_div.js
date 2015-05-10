$(function() {

    $("#custom_form").validationEngine();

    //***********************************answer_type onchange start*************************
    $(document).delegate("#custom_answer_type", "change", function() {

        var val = $(this).val();
        if (val == "M") {

            //load input area and options
            $.ajax({
                type: "post",
                url: "service_page.php?t=lo",
                success: function(data) {
                    $("#mcqarea").html(data);
                }
            });

            //always shows no options
            var qid = $("#qid").val();
            $.ajax({
                type: "post",
                url: "service_page.php",
                data: {qid: qid, t: "lol"},
                success: function(data) {
                    $("#option_list").html(data);
                }
            });

        } else {
            $("#mcqopt").remove();
        }

        var jid = $("#jid").val();
        var qid = $("#qid").val();
        var quest = $("#custom_question").val();
        var at = $("#custom_answer_type").val();

        //on change if question field not empty save question
        $.ajax({
            type: "post",
            url: "service_page.php?t=scq",
            data: {jid: jid, qid: qid, quest: quest, at: at},
            dataType: "json",
            beforeSend: function() {
                return !($("#custom_question").validationEngine("validate"));
            },
            success: function(data) {

                $("#qid").val(data.qid);
                var jid = $("#jid").val();
                $.ajax({
                    type: "post",
                    url: "service_page.php?t=lql",
                    data: "jid=" + jid,
                    success: function(data) {
                        $("#questionlist").html(data);
                    }
                });

            }
        });

    }); //answer_type onchange end


    //***********************************onclick add option start*****************************
    $(document).delegate("#add_other", "click", function() {

        var opt_val = $("#mcq_option_val").val();
        var jid = $("#jid").val();
        var qid = $("#qid").val();
        var quest = $("#custom_question").val();
        var at = $("#custom_answer_type").val();

        $.ajax({
            type: "post",
            url: "service_page.php?t=scq",
            data: {jid: jid, qid: qid, quest: quest, at: at, opt_val: opt_val, add: "t"},
            dataType: "json",
            beforeSend: function() {
                return (!($("#custom_question").validationEngine("validate")) && !($("#mcq_option_val").validationEngine("validate")));
            },
            success: function(data) {

                $("#qid").val(data.qid);
                $("#mcq_option_val").val("");

                var qid = $("#qid").val();

                $.ajax({
                    type: "post",
                    url: "service_page.php?t=lol",
                    data: "qid=" + qid,
                    success: function(data) {

                        $("#option_list").html(data);

                        var jid = $("#jid").val();
                        $.ajax({
                            type: "post",
                            url: "service_page.php?t=lql",
                            data: "jid=" + jid,
                            success: function(data) {
                                $("#questionlist").html(data);
                            }
                        });

                    }
                });
            }
        });

    });

    //**************************************
    $(document).delegate("#custom_question", "blur", function() {

        var jid = $("#jid").val();
        var qid = $("#qid").val();
        var quest = $("#custom_question").val();
        var at = $("#custom_answer_type").val();

        $.ajax({
            type: "post",
            url: "service_page.php?t=scq",
            data: {jid: jid, qid: qid, quest: quest, at: at},
            dataType: "json",
            beforeSend: function() {
                return !($("#custom_question").validationEngine("validate"));
            },
            success: function(data) {

                $("#qid").val(data.qid);
                var jid = $("#jid").val();
                $.ajax({
                    type: "post",
                    url: "service_page.php?t=lql",
                    data: "jid=" + jid,
                    success: function(data) {
                        $("#questionlist").html(data);
                    }
                });
            }
        });

    });

    //**************************************
    $(document).delegate("#custom_delete", "click", function() {
        var qid = $("#qid").val();
        $.ajax({
            type: "post",
            url: "service_page.php?t=del_cust_quest",
            data: "qid=" + qid,
            beforeSend: function() {
                return !($("#custom_question").validationEngine("validate"));
            },
            success: function(data) {
                if (data == "deleted") {
                    var tid = $("input[name=qtype]:checked").attr('id');
                    var jid = $("#jid").val();
                    var oid = $("#oid").val();
                    $.ajax({
                        type: "post",
                        url: "service_page.php?t=lqa",
                        data: {jid: jid, oid: oid, tid: tid},
                        success: function(data) {
                            $("#questionarea").html(data);
                            var jid = $("#jid").val();
                            $.ajax({
                                type: "post",
                                url: "service_page.php?t=lql",
                                data: "jid=" + jid,
                                success: function(data) {
                                    $("#questionlist").html(data);
                                }
                            });
                        }
                    });
                }
            }
        });
    });

    //*********************************
    $(document).delegate(".edit_opt", "click", function() {

        var opt_id = $(this).attr("id").split("_")[1];

        //load input area and options
        $.ajax({
            type: "post",
            data: "opt_id=" + opt_id,
            url: "service_page.php?t=lo",
            success: function(data) {

                $("#mcqarea").html(data);
                var qid = $("#qid").val();
                $.ajax({
                    type: "post",
                    url: "service_page.php?t=lol",
                    data: "qid=" + qid,
                    success: function(data) {
                        $("#option_list").html(data);
                    }
                });

            }
        });
    });

    //****************************************
    $(document).delegate("#edit_opt_btn", "click", function() {

        var opt_id = $(this).attr("oi");
        var opt_val = $("#mcq_option_val").val();
        var jid = $("#jid").val();
        var qid = $("#qid").val();
        var quest = $("#custom_question").val();
        var at = $("#custom_answer_type").val();

        $.ajax({
            type: "post",
            url: "service_page.php?t=scq",
            data: {jid: jid, qid: qid, quest: quest, at: at, opt_val: opt_val, add: "f", opt_id: opt_id},
            dataType: "json",
            beforeSend: function() {
                return (!($("#custom_question").validationEngine("validate")) && !($("#mcq_option_val").validationEngine("validate")));
            },
            success: function(data) {

                $("#qid").val(data.qid);
                $("#mcq_option_val").val("");

                //load input area and options
                $.ajax({
                    type: "post",
                    url: "service_page.php?t=lo",
                    success: function(data) {

                        $("#mcqarea").html(data);
                        var qid = $("#qid").val();

                        $.ajax({
                            type: "post",
                            url: "service_page.php?t=lol",
                            data: "qid=" + qid,
                            success: function(data) {

                                $("#option_list").html(data);

                                var jid = $("#jid").val();
                                $.ajax({
                                    type: "post",
                                    url: "service_page.php?t=lql",
                                    data: "jid=" + jid,
                                    success: function(data) {
                                        $("#questionlist").html(data);
                                    }
                                });

                            }
                        });

                    }
                });
            }
        });

    });

});