
function notification() {
    console.log("testing");
    jQuery.ajax({
        type: "POST",
        url: "member/notification.php",
        data: {action: "notification"},
        dataType: "json",
        success: function(data) {
            jQuery("#notify_value").text(data.results.total)
        }
    });
}
