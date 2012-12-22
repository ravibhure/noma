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
    };

    var get = function(key) {
        return _cfg[key];
    };

    var set = function(key, value) {
        _cfg[key] = value;
    };

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
    };

    ns.utilities.api_get = api_get;

}(NOMA));

////////////////////////////////////////////////////////////////////////////
// HTML
// Collection of HTML templates / widgets
////////////////////////////////////////////////////////////////////////////
NOMA.html = NOMA.html || {};
(function(ns) {
    var multi_select = function(id, title_selected, title_deselected) {
        var str_html = '<div class="pull-left noma_multiselect"><div class="pull-left">' +
            '<strong>' + title_selected + ':</strong><br>' +
            '<select class="noma_multiselect" id="' + id + '_selected" size="10"></select></div>' +
            '<div class="btn-group pull-left" style="margin-top:75px;padding: 10px;">' +
            '<button class="btn" id="' + id + '_btn_select"><i class="icon-chevron-left"></i></button>' +
            '<button class="btn" id="' + id + '_btn_deselect"><i class="icon-chevron-right"></i></button>' +
            '</div><div class="pull-left"><strong>' + title_deselected + ':</strong><br>' +
            '<select class="noma_multiselect" id="' + id + '_deselected" size="10"></select>' +
            '</div></div>';

        return str_html;
    };

    var nodeslist_row = function(node) {
        var str_html = '<tr>' +
            '<td class="nodeslist_name">' + node['name'] + '</td>' +
            '<td class="nodeslist_ip">' + node['ip'] + '</td>' +
            '<td class="node_nodepropcount" id="node_nodeprops_' + node['id'] + '">' +
            '<a href="#" class="node_link" id="#node_link_' + node['id'] + '">' +
            node['propcount'] + '</a>' +
            '<div id="nodepropslist_' + node['id'] + '" class="nodepropslist"></div>' +
            '</td>' +
            '<td class="nodeslist_deactivate">' +
            '<a href="#" class"node_link" id="#node_link_' + node['id'] + '">Deactivate</a></td>' +
            '</tr>';

        return str_html;
    }

    var serviceslist_row = function(service) {
        var str_html = '<tr>' +
            '<td style="width:200px;">' + service['content'] + '</td>' +
            '<td class="nodeprop_nodecount" id="nodeprop_nodes_' + service['id'] + '">' +
            '<a href="#" class="service_link" id="#service_link_' + service['id'] + '">' +
            service['nodecount'] + '</a>' +
            '<div id="nodeslist_' + service['id'] + '" class="nodeslist"></div>' +
            '</td></tr>';

        return str_html;
    }

    ns.html.multi_select = multi_select;
    ns.html.nodeslist_row = nodeslist_row;
    ns.html.serviceslist_row = serviceslist_row;

}(NOMA));

////////////////////////////////////////////////////////////////////////////
// NODES
// Functions for the 'Nodes' screen
////////////////////////////////////////////////////////////////////////////
NOMA.nodes = NOMA.nodes || {};
(function(ns) {
    var add_nodeprop = function(node_id, nodeprop_id) {
        var data = {
            'nodeprop': nodeprop_id,
            'node': node_id
        };

        ns.utilities.api_get('json_node_add_nodeprop', data, function(response, textStatus, jqXHR) {
            $('#select_nodeprop_' + node_id + '_selected').append(
                $('#select_nodeprop_' + node_id + '_deselected option:selected'));
        });
    };

    var remove_nodeprop = function(node_id, nodeprop_id) {
        var data = {
            'nodeprop': nodeprop_id,
            'node': node_id
        };

        ns.utilities.api_get('json_node_remove_nodeprop', data, function(response, textStatus, jqXHR) {
            $('#select_nodeprop_' + node_id + '_deselected').append(
                $('#select_nodeprop_' + node_id + '_selected option:selected'));
        });
    };

    var show_nodeprops = function(node_id, el_target) {
        $(el_target).toggle();
        if ($(el_target).css('display') != 'none') {
            refresh_nodeprops(node_id, el_target);
        }
    };

    var refresh_nodeprops = function(node_id, el_target) {
        $(el_target).empty();

        var multiselect = ns.html.multi_select('select_nodeprop_' + node_id, 'selected properties', 'deselected properties');

        $(el_target).append(multiselect);

        // event handler: remove selected nodeprop from node
        $('#select_nodeprop_' + node_id + '_btn_deselect').click(function() {
            var nodeprop_id = $('#select_nodeprop_' + node_id + '_selected option:selected').val();
            if (nodeprop_id != undefined) {
                remove_nodeprop(node_id, nodeprop_id);
            }
        });

        // event handler: add selected nodeprop to node
        $('#select_nodeprop_' + node_id + '_btn_select').click(function() {
            var nodeprop_id = $('#select_nodeprop_' + node_id + '_deselected option:selected').val();
            if (nodeprop_id != undefined) {
                add_nodeprop(node_id, nodeprop_id);
            }
        });

        var data =  {
            node: node_id
        };

        ns.utilities.api_get('json_get_nodeprops', data, function(response, textStatus, jqXHR) {
            $.each(response.nodeprops, function(index, nodeprop) {
                $('<option>').attr({
                    'value': nodeprop.id,
                })
                .text(nodeprop.nodepropdef_name + ': ' + nodeprop.content)
                .appendTo($('#select_nodeprop_' + node_id + '_selected'));
            });
        });

        var data2 = {
            exclude_node: node_id
        };

        ns.utilities.api_get('json_get_nodeprops', data2, function(response, textStatus, jqXHR) {
            $.each(response.nodeprops, function(index, nodeprop) {
                $('<option>').attr({
                    'value': nodeprop.id
                })
                .text(nodeprop.nodepropdef_name + ': ' + nodeprop.content)
                .appendTo($('#select_nodeprop_' + node_id + '_deselected'));
            });
        });
    };

    // Refresh the nodes list
    var refresh = function() {
        var data = {};

        ns.utilities.api_get('json_get_nodes', data, function(response, textStatus, jqXHR) {
            $.each(response.nodes, function(index, node) {
                $('#nodeslist_body').append(ns.html.nodeslist_row(node));
            });
        });
    };

    var init = function() {
        // event handler: show nodeprops when the 'nr of nodeprops' link is clicked
        $('#nodeslist_body').on('click', function(event) {
            if ($(event.target).is('a.node_link')) {
                var id = event.target.id.replace('#node_link_', '');
                ns.nodes.show_nodeprops(id, '#nodepropslist_' + id);
            }
        });
    };

    ns.nodes.init = init;
    ns.nodes.refresh = refresh;
    ns.nodes.show_nodeprops = show_nodeprops;

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
            $('#select_node_' + nodeprop_id + '_selected').append(
                $('#select_node_' + nodeprop_id + '_deselected option:selected'));
        });
    };

    var remove_node = function(nodeprop_id, node_id) {
        var data = {
            'node': node_id,
            'nodeprop': nodeprop_id
        };

        ns.utilities.api_get('json_node_remove_nodeprop', data, function(response, textStatus, jqXHR) {
            $('#select_node_' + nodeprop_id + '_deselected').append(
                $('#select_node_' + nodeprop_id + '_selected option:selected'));
        });
    };

    var refresh_nodes = function(nodeprop_id, el_target) {
        $(el_target).empty();

        var multiselect = ns.html.multi_select('select_node_' + nodeprop_id, 'selected nodes', 'deselected nodes');

        $(el_target).append(multiselect);

        // event handler: remove selected node from service
        $('#select_node_' + nodeprop_id + '_btn_deselect').click(function() {
            var node_id = $('#select_node_' + nodeprop_id + '_selected option:selected').val();
            if (node_id != undefined) {
                remove_node(nodeprop_id, node_id);
            }
        });

        // event handler: add selected node to service
        $('#select_node_' + nodeprop_id + '_btn_select').click(function() {
            var node_id = $('#select_node_' + nodeprop_id + '_deselected option:selected').val();
            if (node_id != undefined) {
                add_node(nodeprop_id, node_id);
            }
        });

        var data =  {
            nodeprop: nodeprop_id
        };

        ns.utilities.api_get('json_get_nodes', data, function(response, textStatus, jqXHR) {
            $.each(response.nodes, function(index, value) {
                $('#select_node_' + nodeprop_id + '_selected').append(
                '<option value="' + value.id + '">' + value.name + '</option>');
            });
        });

        var data2 = {
            exclude_nodeprop: nodeprop_id
        };

        ns.utilities.api_get('json_get_nodes', data2, function(response, textStatus, jqXHR) {
            $.each(response.nodes, function(index, value) {
                $('#select_node_' + nodeprop_id + '_deselected').append(
                '<option value="' + value.id + '">' + value.name + '</option>');
            });
        });
    };

    // Show a list of nodes linked to the given nodeprop
    var show_nodes = function(nodeprop_id, el_target) {
        $(el_target).toggle();
        if ($(el_target).css('display') != 'none') {
            refresh_nodes(nodeprop_id, el_target);
        }
    };

    // Refresh the serviceslist
    var refresh = function() {
        var data = {
            nodepropdefname: 'service'
        };

        ns.utilities.api_get('json_get_nodeprops', data, function(response, textStatus, jqXHR) {
            $.each(response.nodeprops, function(index, service) {
                $('#serviceslist_body').append(ns.html.serviceslist_row(service));
            });
        });
    };

    var init = function() {
        // event handler for the serviceslist: when the nr of nodes link is clicked, show the nodes
        $('#serviceslist_body').on('click', function(event) {
            if ($(event.target).is('a.service_link')) {
                var id = event.target.id.replace('#service_link_', '');
                ns.services.show_nodes(id, '#nodeslist_' + id);
            }
        });
    };

    ns.services.init = init;
    ns.services.refresh = refresh;
    ns.services.show_nodes = show_nodes;

}(NOMA));
