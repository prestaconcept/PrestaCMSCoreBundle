/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
var CMSPage = function() {

    return {
        /**
         * Submit Modal Edition form and update content
         */
        submitTabForm : function (formType) {
            $.ajax({
                url: $('#edit_' + formType + '_form').attr('action'),
                type: "POST",
                data: $('#edit_' + formType + '_form').serialize()
            }).done(function( html ) {
                $('#edit_seo_form .alert').hide()
                if (html.error != undefined) {
                    $('#edit_seo_form_error').show();
                }
                if (html.success != undefined) {
                    $('#edit_seo_form_success').show();
                }

                $('#' + formType + '_form_tab').effect("highlight", {}, 3000);
            });
        }
    }
}();

$(document).ready(function() {
    // issue 99: remove the confirmation alert when changing cms page
    window.onbeforeunload = function() {
        return;
    };
});
