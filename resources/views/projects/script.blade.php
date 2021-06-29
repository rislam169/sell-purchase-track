<script type="text/javascript">
    $(document).ready( function () {
        if ($('#project_table').length) {
            let dataTable = $('#project_table').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                language: {
                    "lengthMenu": "Display _MENU_"
                },
                ajax: {
                    url: '{{ route('project-list') }}'
                },
                columns: [
                    {data: 'id', name: 'id', orderable: false, searchable: false, visible: false},
                    {data: 'project_name', name: 'project_name', orderable: true, searchable: true, width: "20%"},
                    {data: 'total_budget', name: 'total_budget', orderable: true, searchable: true, width: "15%"},
                    {data: 'total_expense', name: 'total_expense', orderable: true, searchable: true, width: "10%"},
                    {data: 'staff_person', name: 'staff_person', orderable: true, searchable: true, width: "20%"},
                    {data: 'created_at', name: 'created_at', orderable: true, searchable: true, width: "20%"},
                    {data: 'action', name: 'action', orderable: false, searchable: false, width: "20%"},

                ],
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                },
                dom: '<"top-toolbar row"<"top-left-toolbar col-md-9"lB><"top-right-toolbar col-md-3"f>>rt<"bottom-toolbar"<"bottom-left-toolbar"i><"bottom-right-toolbar"p>>',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-copy"></i>',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-text-o"></i>',
                        filename: 'Project_List_'+(new Date).getTime(),
                        exportOptions: {
                            columns: [ 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        filename: 'Project_List_'+(new Date).getTime(),
                        title: 'User List',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        sheetName: 'Exported data',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        pageSize: 'A4',
                        filename: 'Project_List_'+(new Date).getTime(),
                        title: 'Project List',
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4]
                        },
                        customize : function(doc) {
                            doc.content[1].table.widths = ['10%', '25%', '35%', '20%', '10%'];
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4]
                        }
                    },
                ]
            });

            function dataTableReload() {
                dataTable.ajax.reload();
            }
        }

        /* Add project form submit */
        if($('#add-project-form').length) {
            $('#add-project-form')
                .parsley()
                .on('form:success', function (formInstance) {
                    if (formInstance.isValid()) {
                        demo.showLoader($('#add-project-submit'));

                        let frm = $('#add-project-form');
                        let formData = new FormData(frm[0]);

                        $.ajax({
                            url: '{!! route('projects.store') !!}',
                            type: 'post',
                            data: formData,
                            processData: false,
                            contentType: false,
                            loadSpinner: true,
                            success: function (response) {
                                demo.hideLoader($('#add-project-submit'), 'Add Project');
                                if (response.status == 'validation-error') {
                                    demo.showNotification(JSON.stringify(response.html), 'success');
                                } else if (response.status) {
                                    demo.showNotification(response.html, "success");
                                    dataTableReload();
                                    $('#addProject').modal('toggle');
                                    $("#add-project-form").trigger('reset');
                                    $("#add-project-form").parsley().reset();
                                } else {
                                    demo.showNotification(response.html, "error");
                                }
                            }
                        });
                    }
                }).on('form:submit', function () {
                return false;
            });
        }
        /* Add project form submit end */

        /* Edit project form trigger */
        $(document).on('click', '.edit-project', function () {
            $('.dataTables_processing', $('#project_table').closest('.dataTables_wrapper')).show();

            let project_id = $(this).attr('data-project_id');
            $.ajax({
                url: '{{ route('get-project-form') }}',
                type: 'post',
                data: {_token: '{{ csrf_token() }}', project_id: project_id},
                loadSpinner: true,
                success: function (response) {
                    $('.dataTables_processing', $('#project_table').closest('.dataTables_wrapper')).hide();

                    if (response.status) {
                        $('#editProjectModal').html(response.html);
                        $('#editProject').modal('toggle');
                    } else {
                        demo.showNotification(response.html, "error");
                    }
                }
            });
        });
        /* Edit project form trigger end */

        /* Edit project form submit */
        $(document).on('click', '#edit-project-form-submit', function () {
            let button = $(this);
            let isModal = $(this).attr('data-isModal');
            let form = $('#edit-project-form').parsley();
            form.validate();

            let frm = $('#edit-project-form');
            let formData = new FormData(frm[0]);

            if (form.isValid()) {
                demo.showLoader(button);
                $.ajax({
                    url: '{{ route('projects-update') }}',
                    type: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    loadSpinner: true,
                    success: function (response) {
                        demo.hideLoader(button, 'Update Project');
                        if (response.status === 'validation-error'){
                            demo.showNotification(JSON.stringify(response.html), 'error');
                        } else if (response.status) {
                            demo.showNotification(response.html,"success");
                            dataTableReload();
                            $('#editProject').modal('toggle');
                        } else {
                            demo.showNotification(response.html, "error");
                        }
                    }
                });
            }
        });
        /* Edit project form submit end */

        /* Delete project */
        $(document).on('click', '.delete-project', function() {
            let project_id = $(this).attr('data-project_id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'confirmButton',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $('.dataTables_processing', $('#project_table').closest('.dataTables_wrapper')).show();
                    $.ajax({
                        url: '{!! url('projects') !!}'+'/'+project_id,
                        type: 'DELETE',
                        loadSpinner: true,
                        data: {_token: '{{ csrf_token() }}'},
                        success: function (response) {
                            if (response.status == 'success') {
                                demo.showNotification(response.html, 'success');
                                dataTableReload();
                            } else if (response.status == 'error') {
                                $('.dataTables_processing', $('#project_table').closest('.dataTables_wrapper')).hide();
                                demo.showNotification(response.html, 'error');
                            }
                        }
                    });
                }
            })
        });
        /* Delete project end */
    });
</script>
