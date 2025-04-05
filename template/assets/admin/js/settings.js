jQuery(document).ready(function($) {
	'use strict';

    var editor = new $.fn.dataTable.Editor( {
        ajax: {
            url: '/wp-json/{{PLUGIN_SLUG}}/v1/settings',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', {{PLUGIN_NAMESPACE}}.nonce);
            },        
        },
        table: '#{{PLUGIN_SLUG}}-settings',
        fields: [
            {
                label: "ID:",
                name: "id",
                type: "hidden",
            },
            {
                label: "Value:",
                name: "value",
                type: "text",
                attr: {
                    maxlength:"255"
                }                  
            },
        ],

    } );


    $('#{{PLUGIN_SLUG}}-settings').DataTable({
        dom: 'Bfrtip',
        ajax: {
            url: '/wp-json/{{PLUGIN_SLUG}}/v1/settings',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', {{PLUGIN_NAMESPACE}}.nonce);
            },        
        },
        order: [],
        columns: [
            {
                title: "ID",
                data: "id",
                visible: false,
            },
            {
                title: "Name",
                data: "name",
                defaultContent: "",
            },
            {
                title: "Value",
                data: null,
                defaultContent: "",
                render: function(data) {
                    let value = data.value;
                    if (data.name.toLowerCase().includes('password')) {
                        value = '***********';
                    }
                    return value;
                }
            },         
            {
                title: "Description",
                data: "description",
                defaultContent: "",
            },                  
            // {
            //     data: "created_at",
            // },
            {
                title: "Updated",
                data: "updated_at",
                defaultContent: "",
            },
        ],
        columnDefs: [],
        select: {
            style: 'single'
        },
        ordering: true,
        pageLength: 20,
        buttons: [
            // { extend: "create", editor: editor },
            { extend: "edit",   editor: editor },
            // { extend: "remove", editor: editor }
        ]
    });

} );
