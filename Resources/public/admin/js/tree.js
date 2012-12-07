/**
 * Nicolas Bastien :
 * Bas? sur init.js du CMFTreebrowser pour ajouter le param?tre _locale au routing
 * + surpprimer le context munu
 *
 * @todo voir pour faire une pull request !
 *
 * @type {*}
 */


/**
 * define a tree used to see all content, move nodes and select things to edit
 */
var AdminCMSTree = (function () {

    var my = {};

    my.initTree = function (config) {
        if (! 'rootNode' in config) {
            config.rootNode = "/";
        }
        if (! 'types' in config) {
            config.types = {
                "default": {
                    "valid_children": "none",
                    "icon": {
                        "image": config.icon.document
                    }
                }
            }
        }
        if (! 'selected' in config) {
            config.selected = config.rootNode;
        }
        jQuery(config.selector).jstree({
            "core": {
                "initially_load": config.path.expanded,
                "initially_open": config.path.preloaded
            },
            "plugins": [ "themes", "types", "ui", "json_data", "dnd", "cookies" ],
            "json_data": {
                "ajax": {
                    "url":    config.ajax.children_url,
                    "data":   function (node) {
                        if (node == -1) {
                            return { 'root' : config.rootNode };
                        } else {
                            return { 'root' : jQuery(node).attr('id') };
                        }
                    }
                }
            },
            "types": {
                "max_depth":        -2,
                "max_children":     -2,
                "valid_children":  [ "folder" ],
                "types": config.types
            },
            "ui": {
                "initially_select" : [ config.selected ]
            },
            "dnd": {
                "drop_target" : false,
                "drag_target" : false
            },
            "crrm": {
                "move": {

                }
            },
            "cookies": {
                "save_selected": false
            }
        })
        .bind("select_node.jstree", function (event, data) {
            if (data.rslt.obj.attr("className").replace(/\\/g, '') in config.routecollection
                && data.rslt.obj.attr("id") != config.selected) {
                window.location = Routing.generate(config.routecollection[data.rslt.obj.attr("className").replace(/\\/g, '')].routes.edit, { "id": data.rslt.obj.attr("url_safe_id"), "_locale": config._locale, "locale": config.locale });
            } else {
                // TODO: overlay?
                console.log('This node is not editable');
            }
        })
        .bind("move_node.jstree", function (event, data) {
            var dropped = data.rslt.o;
            var target = data.rslt.r;

            $.post(
                config.ajax.move_url,
                { "dropped": dropped.attr("id"), "target": target.attr("id") },
                function (data) {
                    dropped.attr("id", data);
                }
            );
        })
        .delegate("a", "click", function (event, data) { event.preventDefault(); });
};

    return my;

}());
