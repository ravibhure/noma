//
// Noma
// LICENSE
//
// This source file is subject to the GPLv3 license that is bundled
// with this package in the file doc/GPLv3.txt.
// It is also available through the world-wide-web at this URL:
// http://www.gnu.org/licenses/gpl-3.0.txt
//
// @copyright  Copyright (c) 2012 Jochem Kossen <jochem@jkossen.nl>
// @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
//

var NOMA = NOMA || {};

////////////////////////////////////////////////////////////////////////////
// SETUP
// Base NOMA functions
////////////////////////////////////////////////////////////////////////////
(function(ns) {
    var _cfg = {};

    var init = function(cfg) {
        _cfg = cfg;
    }

    var get = function(key) {
        return _cfg[key];
    }

    var set = function(key, value) {
        _cfg[key] = value;
    }

    ns.init = init;
    ns.get = get;
    ns.set = set;

}(NOMA));

////////////////////////////////////////////////////////////////////////////
// UTILITIES
// Collection of utility functions to make it easier to do certain things
////////////////////////////////////////////////////////////////////////////
NOMA.utilities = NOMA.utilities || {};
(function(ns) {

    // Simple wrapper around jQuery's .ajax function
    var api_get = function(api_call, data, fn_success) {
        $.ajax({
            url: ns.get('base_url') + 'api/' + api_call + '/',
            type: 'GET',
            dataType: 'json',
            data: data,
            success: fn_success,
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown + ': ' + ns.get('base_url') + 'api/' + api_call + '/');
            }
        });
    }

    ns.utilities.api_get = api_get;

}(NOMA));

////////////////////////////////////////////////////////////////////////////
// SERVICES
// Functions for the 'Services' screen
////////////////////////////////////////////////////////////////////////////
NOMA.services = NOMA.services || {};
(function(ns) {
    var add_node = function(nodeprop_id, node_id) {
        var data = {
            'node': node_id,
            'nodeprop': nodeprop_id
        };

        ns.utilities.api_get('json_node_add_nodeprop', data, function(response, textStatus, jqXHR) {
            $('#nodeprop_nodeslist_' + nodeprop_id).append(
                $('#nodeprop_nodeslist_available_' + nodeprop_id + ' option:selected')
            );
        });
    }

    var remove_node = function(nodeprop_id, node_id) {
        var data = {
            'node': node_id,
            'nodeprop': nodeprop_id
        };

        ns.utilities.api_get('json_node_remove_nodeprop', data, function(response, textStatus, jqXHR) {
            $('#nodeprop_nodeslist_available_' + nodeprop_id).append(
                $('#nodeprop_nodeslist_' + nodeprop_id + ' option:selected')
            );
        });
    }

    var refresh_nodes = function(nodeprop_id, el_target) {
        $(el_target).empty();

        $(el_target).append(
            '<div class="pull-left"><strong>selected nodes:</strong><br>'
            + '<select class="nodeprop_nodes" id="nodeprop_nodeslist_' + nodeprop_id + '" size="10"></select></div>'
            + '<div class="btn-group btn-group-vertical pull-left" style="margin-top:75px;padding: 10px;">'
            + '<button class="btn btn-success" id="select_node_' + nodeprop_id + '"><i class="icon-hand-left"></i></button>'
            + '<button class="btn btn-danger" id="deselect_node_' + nodeprop_id + '"><i class="icon-hand-right"></i></button>'
            + '</div>'
            + '<div class="pull-left"><strong>available nodes:</strong><br>'
            + '<select class="nodeprop_nodes" id="nodeprop_nodeslist_available_' + nodeprop_id + '" size="10"></select>'
            + '</div>'
        );

        // event handler: add selected node to service
        $('#select_node_' + nodeprop_id).click(function() {
            var node_id = $('#nodeprop_nodeslist_available_' + nodeprop_id + ' option:selected').val();
            if (node_id != undefined) {
                add_node(nodeprop_id, node_id);
            }
        });

        // event handler: remove selected node from service
        $('#deselect_node_' + nodeprop_id).click(function() {
            var node_id = $('#nodeprop_nodeslist_' + nodeprop_id + ' option:selected').val();
            if (node_id != undefined) {
                remove_node(nodeprop_id, node_id);
            }
        });

        var data =  {
            nodeprop: nodeprop_id
        };

        ns.utilities.api_get('json_get_nodes', data, function(response, textStatus, jqXHR) {
            $.each(response.nodes, function(index, value) {
                $('#nodeprop_nodeslist_' + nodeprop_id).append(
                    '<option value="' + value.id + '">' + value.name + '</option>'
                );
            });
        });

        var data2 = {
            exclude_nodeprop: nodeprop_id
        };

        ns.utilities.api_get('json_get_nodes', data2, function(response, textStatus, jqXHR) {
            $.each(response.nodes, function(index, value) {
                $('#nodeprop_nodeslist_available_' + nodeprop_id).append(
                    '<option value="' + value.id + '">' + value.name + '</option>'
                );
            });
        });
    }

    // Show a list of nodes linked to the given nodeprop
    var show_nodes = function(nodeprop_id, el_target) {
        $(el_target).toggle();
        if ($(el_target).css('display') != 'none') {
            refresh_nodes(nodeprop_id, el_target);
        }
    }

    // Refresh the servicelist
    var refresh = function() {
        var data = {
            nodepropdefname: 'service'
        };

        ns.utilities.api_get('json_get_nodeprops', data, function(response, textStatus, jqXHR) {
            $.each(response.nodeprops, function(index, value) {
                $('#servicelist_body').append(
                    '<tr>'
                    + '<td style="width:200px;">' + value['content'] + '</td>'
                    + '<td class="nodeprop_nodecount" id="nodeprop_nodes_' + value.id + '">'
                    + '<a href="#" class="service_link" id="#service_link_' + value['id'] + '">'
                    + value.nodes.length + '</a>'
                    + '<div id="nodeslist_' + value.id + '" class="nodeslist" style="display:none;width:250;height:200;"></div>'
                    + '</td>'
                    + '</tr>'
                );
            });
        });
    }

    // Event handler for the servicelist: when the nr of nodes link is clicked, show the nodes
    var init = function() {
        $('#servicelist_body').on('click', function(event) {
            if ($(event.target).is('a.service_link')) {
                var id = event.target.id.replace('#service_link_', '');
                ns.services.show_nodes(id, '#nodeslist_' + id);
            }
        });
    }

    ns.services.init = init;
    ns.services.refresh = refresh;
    ns.services.show_nodes = show_nodes;

}(NOMA));
