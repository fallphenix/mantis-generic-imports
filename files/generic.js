
window.addEventListener("load", function (event) {

    $('#gridSystemModal').on('hidden.bs.modal', function () {
        $('#priority option').removeAttr('selected');
        $('#reproducibility option').removeAttr('selected');
        $('#severity option').removeAttr('selected');
        $('#resolution option').removeAttr('selected');
    });

    function setFullOptionValue(name, value) {
        $('#' + name + ' option[value="' + value + '"]').attr('selected', 'selected');
        $('#' + name + ' option[value="' + value + '"]').prop('selected', true);
    }


    $('body').on('click', ".bouton-edition-issue", function (event) {

        var issue = $(this).data("issue");
        $("#validation-colonne-issue").data("idIssue", issue.id);
        // console.log(issue);
        //on peut faire un ajax pour recupérer la liste des catégory sur le projet choisi
        $('#category_id option[value=' + issue.category + ']').attr('selected', 'selected');


        setFullOptionValue("reproducibility", issue.reproducibility);
        setFullOptionValue("severity", issue.severity);
        setFullOptionValue("priority", issue.priority);
        setFullOptionValue("priority", issue.priority);
        setFullOptionValue("resolution", issue.resolution);
        setFullOptionValue("handler_id", issue.assignedTo);


        $('#summary').val(issue.summary);
        $('#description').val(issue.description);
        $("#project-label").text(issue.labelProject);
        $('#due_date').data("DateTimePicker").date(issue.dateSubmitted);


        var url = $("#url-remote-server-category").val();


        $("#loading-category").show();
        var data = {id_project: issue.projectId};
        getDataFromRemoteMantis(url, data, function (response) {
            if (response.SUCESS) {
                // 
                var liste = JSON.parse(response.data);

                $("#category_id").html("");

                $.each(liste, function (key, category) {
                    var elem = $("<option></option>").attr("value", category.id).text(category.name);
                    if (issue.category === category.id) {
                        elem.attr('selected', 'selected');
                        elem.prop('selected', true);
                    }
                    $("#category_id").append(elem);

                    //console.log(key);
                    //  console.log(category);
                });

            }
            $("#loading-category").hide();
        }, function () {
            $("#loading-category").hide();
        });





    });


    $('body').on('click', ".save-issue-row", function (event) {
        var $element = $(this);
        var idIssue = $element.data("issue-id");
        var issue = $("#issue-" + idIssue).data("issue");
        var url = $("#url-remote-server-create").val();
        // console.log(url);

        $element.attr("disabled", true);
        $element.html($element.data("loading"));
        getDataFromRemoteMantis(url, issue, function (response) {

            if (response.SUCESS) {
                alertSucces($("#issue-notification-create").val());
                console.log($("#issue-notification-create").val())
                $element.html($element.data("label-created"));

                var url2 = $("#url-remote-server-ack").val();
                //console.log(url2);
                $("#show-id-" + idIssue).html($element.data("loading"));
                var regex = new RegExp('id=*[^&#]*');
                var link = $("#show-id-" + idIssue).attr("href");
                link = link.replace(regex, 'id=' + response.response);
                $("#show-id-" + idIssue).attr("href", link);
                $("#show-id-" + idIssue).show();



                getDataFromRemoteMantis(url2, {id: issue.id, id_issue: response.response}, function (response) {
                    //  console.log(response);

                    if (response.SUCESS) {
                        alertSucces($("#issue_notification_ack").val());

                        $("#show-id-" + idIssue).html($("#show-id-" + idIssue).data("label"));



                    } else {
                        $("#show-id-" + idIssue).html($("#show-id-" + idIssue).data("label"));
                        alertError($("#issue_notification_error_ack").val());

                    }


                    window.location.href = link;

                }, function () {
                    $element.html($element.data("label"));
                    $("#show-id-" + idIssue).html($("#show-id-" + idIssue).data("label"));
                    console.log("error create issue acknowlege");
                    alertError($("#issue_notification_error_ack").val());
                });
            } else {
                $element.html($element.data("label"));
                alertError($("#issue_notification_error").val() + " : " + response.response);
            }

            $element.removeAttr("disabled");

        }, function () {
            console.log("error create issue");
            alertError($("#issue_notification_error").val());
        });
    });



    $('body').on('click', ".save-issue-row-modal", function (event) { 
      var idIssue = $("#validation-colonne-issue").data("idIssue");
      //var issue = $("#issue-" + idIssue).data("issue");
      
      $("#validation-colonne-issue").trigger("click");
      $("#issue-row-bouton-create-"+idIssue).trigger("click");
    
    });
    
    $('body').on('click', "#validation-colonne-issue", function (event) {

        var idIssue = $(this).data("idIssue");
        var issue = $("#issue-" + idIssue).data("issue");

        issue.category = $("#category_id").val();
        issue.reproducibility = $("#reproducibility").val();
        issue.severity = $("#severity").val();
        issue.priority = $("#priority").val();
        issue.resolution = $("#resolution").val();
        issue.dateSubmitted = $("#due_date").val();
        issue.assignedTo = $("#handler_id").val();
        issue.summary = $("#summary").val();
        issue.description = $("#description").val();
        issue.view_state = $("input[name='view_state']:checked").val();

        $("#issue-" + idIssue).data("issue", issue);

        $("#issue-tr-" + idIssue + " .i_assigned_to").text($('#handler_id option[value="' + issue.assignedTo + '"]').html());
        $("#issue-tr-" + idIssue + " .i_priority").text($('#priority option[value="' + issue.priority + '"]').html());
        $("#issue-tr-" + idIssue + " .i_severity").text($('#severity option[value="' + issue.severity + '"]').html());
        $("#issue-tr-" + idIssue + " .i_reproducibility").text($('#reproducibility option[value="' + issue.reproducibility + '"]').html());
        $("#issue-tr-" + idIssue + " .i_category").text($('#category_id option[value="' + issue.category + '"]').html());
        $("#issue-tr-" + idIssue + " .i_resolution").text($('#resolution option[value="' + issue.resolution + '"]').html());
        $("#issue-tr-" + idIssue + " .i_dateSubmitted").text($('#due_date').val());
        $("#issue-tr-" + idIssue + " .i_summary").text($('#summary').val());

        $('#gridSystemModal').modal('hide');

    });

    window.alertSucces = function (message) {

        $.iaoAlert({msg: message,
            zIndex: '999999',
            type: "success",
            mode: "light", });
    }
    window.alertError = function (message) {

        $.iaoAlert({msg: message,
            type: "error",
            zIndex: '999999',
            mode: "dark", });
    }
    window.getDataFromRemoteMantis = function (url, data, callback, callbackError) {



        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (response) {
                callback(response);
            },
            error: function () {
                callbackError();

            }
        });


    }

    window.pagObj = $('#pagination').twbsPagination({
        totalPages: parseInt($("#size-all-issues").val()),
        startPage: 1,
        prev: $("#issue_pagination_previous").val(),
        next: $("#issue_pagination_next").val(),
        visiblePages: 10,
        href: true,
        pageVariable: "pagination-page",
        onPageClick: function (event, page) {
            //  console.info(page + ' (from options)');
        }
    }).on('page', function (event, page) {
        // console.info(page + ' (from event listening)');
    });



    $("#filter_issues").change(function (event) {
        $("#filter-filter-issues-form").submit();
    });


}, false);



