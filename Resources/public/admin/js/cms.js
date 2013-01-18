/**
 * CMSContent : Manage CMS content administration for themes and pages
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
var CMSContent = function() {
    
    /**
     * Url for bloc edition
     */
    var _editBlocUrl;

    /**
     * Url for bloc addition
     */
    var _addBlocUrl;
    
    /**
     * Url for block rendering
     */
    var _renderBlockUrl;   
   
    return {
        /**
         * Initialisation
         */
        init : function (editBlocUrl, renderBlockUrl, addBlockUrl) {
            this._editBlocUrl = editBlocUrl;
            this._renderBlockUrl = renderBlockUrl;
            this._addBlocUrl = addBlockUrl;
            
            $('a.action-edit').click(function(e) {
                e.preventDefault();
                CMSContent.editBlock($(this).attr('block-id'));                    
            });
            $('a.action-add').click(function(e) {
                e.preventDefault();
                CMSContent.addBlock($(this).attr('zone-id'));
            });
        },
        /**
         * Return url for block rendering
         */
        getRenderBlocUrl : function (blockId) {
            return this._renderBlockUrl + '?id=' + blockId;
        },
        /**
         * Handle block edit button click
         * Load Modal Edition
         */
        editBlock : function (blockId) {
            $('#modal-loader').show();
            $('#modal-content').html('');
            $('#modal-content').hide();
            $('#modal').modal('show');
            
            $('#modal-content').load(this._editBlocUrl + '?id=' + blockId, function() {
                $('#modal-content div.form-actions').remove();
                $('#modal-loader').hide();
                $('#modal-content').show();
            });
        },
        /**
         * Handle block add button click
         * Load Modal
         */
        addBlock : function (zoneId) {
            $('#modal-loader').show();
            $('#modal-content').html('');
            $('#modal-content').hide();
            $('#modal').modal('show');

            $('#modal-content').load(this._addBlocUrl + '?id=' + zoneId, function() {
                $('#modal-content div.form-actions').remove();
                $('#modal-loader').hide();
                $('#modal-content').show();
            });
        },
        /**
         * Submit Modal Edition form and update content
         */
        submitModalForm : function () {
            $('#modal-content').hide();
            $('#modal-loader').show();
            // déclenche la sauvegarde du Tiny
            //tinymce.triggerSave();
            $.ajax({
                url: $('#modal form').attr('action'),
                type: "POST",
                data: $('#modal form').serialize()
            }).done(function( html ) {
                //If succesfull we only get a return code in JSON
                if (html.result != undefined) {
                    $('#modal-content').html('');
                    $('#modal').modal('hide');
                    CMSContentInit();
                    //check action
                    if (html.action != undefined) {
                        if (html.action == 'add') {
                            var zoneContainerId = html.zone.replace(/\//g, '');
                            zoneContainerId = zoneContainerId.replace(/\_/g, '');
                            zoneContainerId = '#cms-zone-' + zoneContainerId.replace(/\./g, '');
                            $(zoneContainerId).append(html.content);
                            $(zoneContainerId).effect("highlight", {}, 3000);
                            return;
                        }
                    }
                    var blockContainerId = html.objectId.replace(/\//g, '');
                    blockContainerId = blockContainerId.replace(/\_/g, '');
                    blockContainerId = '#block-content-' + blockContainerId.replace(/\./g, '');
                    $(blockContainerId).load(CMSContent.getRenderBlocUrl(html.objectId), function() {
                        $(blockContainerId).effect("highlight", {}, 3000);
                    });
                } else {
                    //If an error occurs, we rediplay the form
                    $('#modal-content').html(html);
                    $('#modal-loader').hide();
                    $('#modal-content').show();
                }
            });
        }    
    }
}();