<script type="text/javascript">
    $(document).ready( function () {
        if ($('#user_table').length) {
            let dataTable = $('#user_table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('user-list') }}'
                },
                columns: [
                    {data: 'name', name: 'name', "orderable": true, "searchable": true},
                    {data: 'email', name: 'email', "orderable": true, "searchable": true},
                    {data: 'phone', name: 'phone', "orderable": true, "searchable": true},
                    {data: 'status', name: 'status', "orderable": true, "searchable": true},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            function dataTableReload() {
                dataTable.ajax.reload();
            }
        }

        /* Add user form submit */
        if ($('#add-user-form').length) {
            $('#add-user-form')
                .parsley()
                .on('form:success', function (formInstance) {
                    if (formInstance.isValid()) {
                        demo.showLoader($('#add-user-submit'));
                        $.ajax({
                            url: '{!! route('users.store') !!}',
                            type: 'post',
                            data: $('#add-user-form').serialize(),
                            loadSpinner: true,
                            success: function (response) {
                                demo.hideLoader($('#add-user-submit'), 'Add User');
                                if (response.status == 'validation-error') {
                                    demo.showNotification(JSON.stringify(response.html), 'success');
                                } else if (response.status == 'success') {
                                    demo.showNotification(response.html, "success");
                                    dataTableReload();
                                    $('#addUser').modal('toggle');
                                    $("#add-user-form").trigger('reset');
                                    $("#add-user-form").parsley().reset();
                                } else if (response.status == 'error') {
                                    demo.showNotification(response.html, "error");
                                }
                            }
                        });
                    }
                }).on('form:submit', function () {
                return false;
            });
        }
        /* Add user form submit end */

        /* Edit user form trigger */
        $(document).on('click', '.edit-user', function () {
            $('#user_id').val($(this).attr('data-user_id'));
            $('#user_name').val($(this).attr('data-user_name'));
            $('#user_email').val($(this).attr('data-user_email'));
            $('#user_status').val($(this).attr('data-status'));
            $('#editUser').modal('toggle');
        });
        /* Edit user form trigger end */

        /* Edit user form submit */
        if ($('#edit-user-form').length) {
            $('#edit-user-form')
                .parsley()
                .on('form:success', function (formInstance) {
                    if (formInstance.isValid()) {
                        demo.showLoader($('#edit-user-submit'));
                        $.ajax({
                            url: '{!! url('users/update') !!}',
                            type: 'put',
                            data: $('#edit-user-form').serialize(),
                            loadSpinner: true,
                            success: function (response) {
                                demo.hideLoader($('#edit-user-submit'), 'Edit User');
                                if (response.status == 'validation-error') {
                                    demo.showNotification(JSON.stringify(response.html), 'error');
                                } else if (response.status == 'success') {
                                    demo.showNotification(response.html, "success");
                                    dataTableReload();
                                    $('#editUser').modal('toggle');
                                    $("#edit-user-form").resetForm();
                                    $("#edit-user-form").parsley().reset();
                                } else if (response.status == 'error') {
                                    demo.showNotification(response.html, "error");
                                }
                            }
                        });
                    }
                }).on('form:submit', function () {
                return false;
            });
        }
        /* Edit user form submit end */

        /* Delete user */
        $(document).on('click','.delete-user', function() {
            let user_id = $(this).attr('data-user_id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $('.dataTables_processing', $('#user_table').closest('.dataTables_wrapper')).show();
                    $.ajax({
                        url: '{!! url('users') !!}'+'/'+user_id,
                        type: 'DELETE',
                        loadSpinner: true,
                        data: {_token: '{{ csrf_token() }}'},
                        success: function (response) {
                            if (response.status == 'success') {
                                demo.showNotification(response.html, 'success');
                                dataTableReload();
                            } else if (response.status == 'error') {
                                $('.dataTables_processing', $('#user_table').closest('.dataTables_wrapper')).hide();
                                demo.showNotification(response.html, 'error');
                            }
                        }
                    });
                }
            })
        });
        /* Delete user end */

        /* Add user form submit */
        if ($('#password-change-form').length) {
            $('#password-change-form')
                .parsley()
                .on('form:success', function (formInstance) {
                    if (formInstance.isValid()) {
                        demo.showLoader($('#password-change-form-submit'));
                        $.ajax({
                            url: '{!! route('password-change') !!}',
                            type: 'post',
                            data: $('#password-change-form').serialize(),
                            loadSpinner: true,
                            success: function (response) {
                                demo.hideLoader($('#password-change-form-submit'), 'Password Change');
                                if (response.status == 'validation-error') {
                                    demo.showNotification(JSON.stringify(response.html), 'success');
                                } else if (response.status == 'success') {
                                    demo.showNotification(response.html, "success");
                                    $("#password-change-form").trigger('reset');
                                    $("#password-change-form").parsley().reset();
                                } else if (response.status == 'error') {
                                    demo.showNotification(response.html, "error");
                                }
                            }
                        });
                    }
                }).on('form:submit', function () {
                return false;
            });
        }
        /* Add user form submit end */
    });
</script>
