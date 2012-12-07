var noma = noma || {};

(function() {
    var _ns = noma;

    this.cfg = {};
    this.services = {};
    this.utilities = {};

    this.init = function(cfg) {
        this.cfg = cfg;
    }

    this.utilities.api_get = function(api_call, data, fn_success) {
        $.ajax({
            url: _ns.cfg['base_url'] + 'api/' + api_call + '/',
            type: 'GET',
            dataType: 'json',
            data: data,
            success: fn_success,
            error: function(jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }

    this.services.show_nodes = function(nodeprop_id) {
        var data =  {
            nodeprop: nodeprop_id
        };

        _ns.utilities.api_get('json_get_nodes', data, function(response, textStatus, jqXHR) {
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

    this.services.refresh = function() {
        var data = {
            nodepropdefname: 'service'
        };

        _ns.utilities.api_get('json_get_nodeprops', data, function(response, textStatus, jqXHR) {
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

    this.services.init = function() {
        $('#servicelist_body').on('click', function(event) {
            if ($(event.target).is('a.service_link')) {
                var id = event.target.id.replace('#service_link_', '');
                _ns.services.show_nodes(id);
            }
        });
    }
}).apply(noma);

