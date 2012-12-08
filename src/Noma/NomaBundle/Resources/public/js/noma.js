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

    var get = function(key) {
        return _cfg[key];
    }

    var set = function(key, value) {
        _cfg[key] = value;
    }

    var init = function(cfg) {
        _cfg = cfg;
    }

    ns.get = get;
    ns.set = set;
    ns.init = init;
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
                alert(errorThrown + ': ' + NOMA.get('base_url') + 'api/' + api_call + '/');
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
    // Show a list of nodes linked to the given nodeprop
    var show_nodes = function(nodeprop_id) {
        var data =  {
            nodeprop: nodeprop_id
        };

        ns.utilities.api_get('json_get_nodes', data, function(response, textStatus, jqXHR) {
            $('#nodeprop_nodes_' + nodeprop_id).append(
                '<ul class="nodeprop_nodes" id="nodeprop_nodeslist_' + nodeprop_id + '">'
            );
            $.each(response.nodes, function(index, value) {
                $('#nodeprop_nodeslist_' + nodeprop_id).append(
                    '<li>' + value.name + '</li>'
                );
            });
        });
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
                    + '<td>' + value['content'] + '</td>'
                    + '<td class="nodeprop_nodecount" id="nodeprop_nodes_' + value.id + '">'
                    + '<a href="#" class="service_link" id="#service_link_' + value['id'] + '">' + value.nodes.length + '</a></td>'
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
                ns.services.show_nodes(id);
            }
        });
    }

    ns.services.init = init;
    ns.services.refresh = refresh;
    ns.services.show_nodes = show_nodes;
}(NOMA));
