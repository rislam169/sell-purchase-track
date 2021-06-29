<script type="text/javascript">
    $(document).ready( function () {
        if ($('#product_table').length) {
            let dataTable = $('#product_table').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                language: {
                    "lengthMenu": "Display _MENU_"
                },
                ajax: {
                    url: '{{ route('product-list') }}'
                },
                columns: [
                    {data: 'id', name: 'id', orderable: false, searchable: false, visible: false},
                    {data: 'title', name: 'title', orderable: true, searchable: true, width: "20%"},
                    {data: 'category', name: 'category', orderable: true, searchable: true, width: "15%"},
                    {data: 'description', name: 'description', orderable: true, searchable: true, width: "15%"},
                    {data: 'created_at', name: 'created_at', orderable: true, searchable: true, width: "20%"},
                    {data: 'status', name: 'status', orderable: true, searchable: true, width: "10%"},
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
                            columns: [ 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-text-o"></i>',
                        filename: 'Product_List_'+(new Date).getTime(),
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        filename: 'Product_List_'+(new Date).getTime(),
                        title: 'User List',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        sheetName: 'Exported data',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        pageSize: 'A4',
                        filename: 'Product_List_'+(new Date).getTime(),
                        title: 'Product List',
                        text: '<i class="fa fa-file-pdf-o"></i>',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5]
                        },
                        customize : function(doc) {
                            doc.content[1].table.widths = ['10%', '25%', '35%', '20%', '10%'];
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5]
                        }
                    },
                ]
            });

            function dataTableReload() {
                dataTable.ajax.reload();
            }
        }

        /* Add product form submit */
        if($('#add-product-form').length) {
            $('#add-product-form')
                .parsley()
                .on('form:success', function (formInstance) {
                    if (formInstance.isValid()) {
                        demo.showLoader($('#add-product-submit'));

                        let frm = $('#add-product-form');
                        let formData = new FormData(frm[0]);

                        $.ajax({
                            url: '{!! route('products.store') !!}',
                            type: 'post',
                            data: formData,
                            processData: false,
                            contentType: false,
                            loadSpinner: true,
                            success: function (response) {
                                demo.hideLoader($('#add-product-submit'), 'Add Product');
                                if (response.status == 'validation-error') {
                                    demo.showNotification(JSON.stringify(response.html), 'success');
                                } else if (response.status) {
                                    demo.showNotification(response.html, "success");
                                    dataTableReload();
                                    $('#addProduct').modal('toggle');
                                    $("#add-product-form").trigger('reset');
                                    $("#add-product-form").parsley().reset();
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
        /* Add product form submit end */

        /* Edit product form trigger */
        $(document).on('click', '.edit-product', function () {
            $('.dataTables_processing', $('#product_table').closest('.dataTables_wrapper')).show();

            let product_id = $(this).attr('data-product_id');
            $.ajax({
                url: '{{ route('get-product-form') }}',
                type: 'post',
                data: {_token: '{{ csrf_token() }}', product_id: product_id},
                loadSpinner: true,
                success: function (response) {
                    $('.dataTables_processing', $('#product_table').closest('.dataTables_wrapper')).hide();

                    if (response.status) {
                        $('#editProductModal').html(response.html);
                        $('#editProduct').modal('toggle');
                    } else {
                        demo.showNotification(response.html, "error");
                    }
                }
            });
        });
        /* Edit product form trigger end */

        /* Edit product form submit */
        $(document).on('click', '#edit-product-form-submit', function () {
            let button = $(this);
            let isModal = $(this).attr('data-isModal');
            let form = $('#edit-product-form').parsley();
            form.validate();

            let frm = $('#edit-product-form');
            let formData = new FormData(frm[0]);

            if (form.isValid()) {
                demo.showLoader(button);
                $.ajax({
                    url: '{{ route('products-update') }}',
                    type: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    loadSpinner: true,
                    success: function (response) {
                        demo.hideLoader(button, 'Update Product');
                        if (response.status === 'validation-error'){
                            demo.showNotification(JSON.stringify(response.html), 'error');
                        } else if (response.status) {
                            demo.showNotification(response.html,"success");
                            dataTableReload();
                            $('#editProduct').modal('toggle');
                        } else {
                            demo.showNotification(response.html, "error");
                        }
                    }
                });
            }
        });
        /* Edit product form submit end */

        /* Delete product */
        $(document).on('click', '.delete-product', function() {
            let product_id = $(this).attr('data-product_id');
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
                    $('.dataTables_processing', $('#product_table').closest('.dataTables_wrapper')).show();
                    $.ajax({
                        url: '{!! url('products') !!}'+'/'+product_id,
                        type: 'DELETE',
                        loadSpinner: true,
                        data: {_token: '{{ csrf_token() }}'},
                        success: function (response) {
                            if (response.status == 'success') {
                                demo.showNotification(response.html, 'success');
                                dataTableReload();
                            } else if (response.status == 'error') {
                                $('.dataTables_processing', $('#product_table').closest('.dataTables_wrapper')).hide();
                                demo.showNotification(response.html, 'error');
                            }
                        }
                    });
                }
            })
        });
        /* Delete product end */
    });
</script>
