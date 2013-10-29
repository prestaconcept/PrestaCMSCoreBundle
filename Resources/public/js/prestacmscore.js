$(document).ready(function() {

    // feed each ajax block with data from route in parameter
    $.each($(".content-ajax"), function (){
        var current = $(this);
        var route   = current.attr("data-route");

        if (route.length > 0) {
            $.ajax({
                url: route,
                success: function (data) {
                    current.html(data);
                }
            });
        }
    });

});
