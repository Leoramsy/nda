<script type="text/javascript">
    var contacts_table, contacts_editor;
    $(document).ready(function () {
        contacts_editor = new $.fn.dataTable.Editor({
            ajax: {
                create: '/contacts/add',
                edit: {
                    type: 'PUT',
                    url: '/contacts/edit/_id_'
                },
                remove: {
                    type: 'DELETE',
                    url: '/contacts/delete/_id_'
                }
            },
            table: "#contacts-table",
            template: "#contacts-editor",
            fields: [{
                    label: "First name:",
                    name: "name"
                }, {
                    label: "Last name:",
                    name: "surname"
                }, {
                    label: "Email:",
                    name: "email"
                }, {
                    label: "Notification Type:",
                    name: "notification_type",
                    type: "radio",
                    options: [
                        {label: "Email", value: 0},
                        {label: "SMS", value: 1}
                    ],
                    def: 0
                }, {
                    label: "Mobile Number:",
                    name: "mobile_number"
                }, {
                    label: "Province:",
                    name: "province_id",
                    type: "select2",
                    def: 0,
                    opts: {
                        minimumResultsForSearch: 0
                    }
                }
            ]
        });

        contacts_table = $('#contacts-table').DataTable({
            processing: true,
            bLengthChange: false,
            ajax: "{{ route('contacts.index') }}",
            columns: [
                {data: null, defaultContent: '', orderable: false, sClass: "selector"},
                {data: 'name', name: 'name'},
                {data: 'surname', name: 'surname'},
                {data: 'province', name: 'province'},
                {data: 'email', name: 'email'},
                {data: 'mobile_number', name: 'mobile_number'},
                {data: 'opt_in_status', name: 'opt_in_status'}
            ],
            columnDefs: [
                {className: "dt-cell-left", targets: [1, 2, 3, 4, 5]}, //Align table body cells to left 
                {className: "dt-cell-center", targets: [6]},
                {searchable: false, targets: 0}
            ],
            order: [[1, 'desc']],
            select: {
                style: 'single',
                selector: 'td:first-child'
            }
        });
        // Display the buttons
        //https://datatables.net/extensions/buttons/custom
        new $.fn.dataTable.Buttons(contacts_table, [
            {extend: 'create', text: 'Add', className: "add-contact",
                action: function () {
                    contacts_editor.create({
                        title: '<h3>Add: Contact</h3>',
                        buttons: [
                            {
                                label: 'Add',
                                fn: function (e) {
                                    this.submit();
                                }
                            },
                            {
                                label: 'Close',
                                fn: function (e) {
                                    this.close();
                                }
                            }
                        ]
                    });
                    contacts_editor.show(['name', 'surname', 'email', 'mobile_number', 'province_id']);
                    contacts_editor.hide(['notification_type']);
                }
            }, {
                extend: 'edit', text: 'Edit', className: "edit-contact",
                action: function () {
                    contacts_editor.edit(contacts_table.row({selected: true}).indexes(), {
                        title: '<h3>Edit: Contact</h3>',
                        buttons: [
                            {
                                label: 'Update',
                                fn: function (e) {
                                    this.submit();
                                }
                            },
                            {
                                label: 'Cancel',
                                fn: function (e) {
                                    this.close();
                                }
                            }
                        ]
                    });
                    contacts_editor.show(['name', 'surname', 'email', 'mobile_number', 'province_id']);
                    contacts_editor.hide(['notification_type']);
                }
            }, {
                extend: 'remove',
                text: 'Delete',
                action: function () {
                    contacts_editor.title('<h3>Delete: Contact</h3>').buttons([
                        {label: 'Delete', fn: function () {
                                this.submit();
                            }},
                        {label: 'Cancel', fn: function () {
                                this.close();
                            }}
                    ]).message('Are you sure you want to delete this contact?').remove(contacts_table.row({selected: true}));
                }
            }, {
                extend: 'edit', text: 'Notify', className: "edit-contact",
                action: function () {
                    contacts_editor.edit(contacts_table.row({selected: true}).indexes(), {
                        title: '<h3>Edit: Contact</h3>',
                        buttons: [
                            {
                                label: 'Send',
                                fn: function (e) {
                                    var id = contacts_table.row({selected: true}).data()['id'];
                                    var csrf_token = $('meta[name="csrf-token"]').attr('content');//Tag is in the main layout //csrf_token()
                                    this.close();
                                    $('<form>', {
                                        'method': 'post',
                                        'action': "/contacts/notify/" + id
                                    }).append($('<input>', {
                                        'name': '_token',
                                        'value': csrf_token,
                                        'type': 'hidden'
                                    })).append($('<input>', {
                                        'name': 'notification_type',
                                        'value': contacts_editor.field('notification_type').val(),
                                        'type': 'hidden'

                                    })).appendTo('body').submit();
                                }
                            },
                            {
                                label: 'Cancel',
                                fn: function (e) {
                                    this.close();
                                }
                            }
                        ]
                    });
                    contacts_editor.hide(['name', 'surname', 'email', 'mobile_number', 'province_id']);
                    contacts_editor.show(['notification_type']);
                }
            }
        ]);       

        contacts_table.buttons().container().insertBefore('#contacts-table_filter');
        contacts_table.on('postSubmit', function (e, json, data, action) {
            if ((json.hasOwnProperty('data') && !json.hasOwnProperty('fieldErrors')) || (json.hasOwnProperty('data') && !json.hasOwnProperty('error'))) {
                var key = Object.keys(json['data']);
                var info = json['data'][key];
                switch (action) {
                    case 'create':
                        flash_msg("Contact " + info['name'] + " " + info['surname'] + " has been successfully added", "alert-success");
                        break;
                    case 'edit':
                        flash_msg("Contact " + info['name'] + " " + info['surname'] + "  has been successfully updated", "alert-success");
                        break;
                    case 'remove':
                        flash_msg("Contact has been successfully removed", "alert-success");
                        break;
                }
            }
        });

    });
</script>