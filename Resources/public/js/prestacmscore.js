$(document).ready(function() {

    $.each($(".content-ajax"), function (){
        var url    = $(this).attr("data-url");
        var parent = $(this).attr("block-parent");

        if (url.length > 0) {
            $.ajax({
                url: url,
                success: function (data) {
                    $(".content-ajax[block-parent=" + parent + "]").html(data);
                }
            });
        }
    });

});
