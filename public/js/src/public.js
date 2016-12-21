(function ( $ ) {
    "use strict";


    // Go when the DOM is ready
    $(function () {
        var apiHost = "https://services.communitycommons.org/";
        var state = "New York";
        var sum_level = "state";

        // ajax call to get geoid list
        var getGeoidList = function (api_param, callback) {
            $.ajax({
                type: "get",
                url: apiHost + "api-report/v1/area/list/NYSCAA-poverty?area_id=0&area_type_id=" + api_param,
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                crossDomain: true,
                success: callback,
                error: function (err) {
                    console.log(err);
                }
            });
        };

        // Populate county list
        if ($("#nyscaa-report-county-list").length) {
            // county list
            getGeoidList("county", function (data) {
                $.each(data.area_list, function (i, v) {
                    $("#nyscaa-report-county-list").append($("<option></option>").val(v.id).html(v.name));
                });
            });

            // city list
            getGeoidList("city", function (data) {
                $.each(data.area_list, function (i, v) {
                    $("#nyscaa-report-city-list").append($("<option></option>").val(v.id).html(v.name));
                });
            });
        } else {
            // KISS tracking for loading a report card -- either directly from shared link or this tool
            var geoid = $("#nyscaa-report-geoid").length ? $("#nyscaa-report-geoid").val() : "";
            if (geoid !== "") {
                _kmq.push(['record', 'opened a salud report card', { 'geoid': geoid }]);
            }
        }

        // Handle county selection
        $("#nyscaa-report-county-list").on("change", function () {
            sum_level = "county";
            load_report();
        });

        // Handle city selection
        $("#nyscaa-report-city-list").on("change", function () {
            sum_level = "city";
            load_report();
        });

        // Handle report exporting
        var popup;
        $(".nyscaa-report-export").on("click", function () {
            var pageFont = "https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&#038;subset=latin,latin-ext";

            // local methods - dependent on popup window being set
            // ** display a message
            var popupMsg = function (s) {
                var msg = "<link type='text/css' rel='stylesheet' href='" + pageFont + "' />";
                msg += "<div style='font-family: Open Sans, Helvetica, Arial, sans-serif; text-align:center; line-height: 250%;'>";
                msg += "<h2>" + s + "</h2>";
                msg += "</div>";
                popup.document.body.innerHTML = msg;
            };

            // ** move the popup window to screen center
            var popupCenter = function (width, height) {
                popup.resizeTo(width, height);
                popup.moveTo((screen.width - width) / 2, (screen.height - height) / 2);
            };

            // open a new window for displaying PDF document later - set popup as global variable
            if (typeof popup !== "undefined" && popup.location) {
                popup.close();
            }
            popup = window.open("", "newwin", "width=500,height=300");
            popupMsg("Converting to Adobe PDF format. Please wait...");
            popupCenter(550, 400);

            // replace img lazyload with original src
            $('#nyscaa-report-content img').each(function () {
                $(this).attr("src", $(this).attr("data-lazy-src"));
            });

            // compile html contents
            var htmContent = "<!DOCTYPE html><html><head>";

            // add font and css files
            var hostname = location.protocol;
            hostname += "//" + document.location.hostname + "/";
            $.each([pageFont,
                    hostname + "wp-content/themes/twentytwelve/style.css",
                    hostname + "wp-content/themes/CommonsRetheme/style.css",
                    hostname + "wp-content/plugins/cc-nyscaa/public/css/public.css"], function (i, css_file) {
                        htmContent += "<link type='text/css' rel='stylesheet' href='" + css_file + "' />";
                    });

            // add report content
            var contentId = "nyscaa-report-content";
            var reportContent = $("#" + contentId).html();
            reportContent = reportContent.replace(/src=("|')\//g, 'src="' + hostname);         // append hostname to any relative links
            htmContent += "</head><body class='custom-font-enabled'>" +
                "<div id='" + contentId + "'>" + reportContent + "</div></body></html>";

            // post to the converter API 
            var post = {
                "content": htmContent,
                "pdf": {
                    "file_name": "NYSCAA-PovertyReport",
                    "show_pagenumber": false,
                    "is_landscape": false,
                    "page_margin": [0, 25, 30, 25],        // in pt: [top, right, bottom, left]
                    "page_width": 800          // change to remove extra blank page
                }
            };

            var errMsg = "Conversion to PDF failed. Please try again or contact us.";
            $.ajax({
                type: "POST",
                url: apiHost + "api-converter/v1/htm2pdf/content",
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                crossDomain: true,
                data: JSON.stringify(post),
                success: function (pdfUrl) {
                    if (pdfUrl) {
                        popupMsg("Loading PDF File...");
                        popup.location = pdfUrl;

                        // resize and move PDF popup to screen center
                        popupCenter(1000, screen.height);
                    } else {
                        popupMsg(errMsg);
                    }
                },
                error: function (err) {
                    console.log(err);
                    popupMsg(errMsg);
                }
            });
        });

        // Handle report saving
        $(".nyscaa-report-save").on("click", function () {

        });

        // load report
        function load_report() {
            var geoid = $("#nyscaa-report-" + sum_level + "-list").val();
            if (typeof geoid !== "undefined" && geoid !== "") {
                $("#nyscaa-report-wait-" + sum_level).show();

                // load report
                document.location = document.location.href.split("?")[0] + "?geoid=" + geoid;
            }
        }
    });
}(jQuery));