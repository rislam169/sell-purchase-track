<script type="text/javascript">
    $(document).ready( function () {
        var remaining_budget = 0;
        var total_cost = 0;
        /* Exam datatable */
        var filter = 2;
        var project_filter = '';
        let dataTable = $('#budget_table').DataTable( {
            processing: true,
            serverSide: false,
            responsive: true,
            language: {
                "lengthMenu": "Display _MENU_"
            },
            ajax: {
                url: '{{ route('budgets.list') }}',
                data: function (data) {
                    data.filter = filter;
                    data.project_filter = project_filter;
                }
            },
            columns: [
                {data: 'id', name: 'id', orderable: true, searchable: true, visible: false},
                {data: 'product_name', name: 'product_name', orderable: true, searchable: true, width: "20%"},
                {data: 'quantity', name: 'quantity', orderable: true, searchable: true, width: "10%"},
                {data: 'remaining_quantity', name: 'remaining_quantity', orderable: true, searchable: true, width: "20%"},
                {data: 'unit_price', name: 'unit_price', orderable: true, searchable: true, width: "15%"},
                {data: 'estimated_delivery_date', name: 'estimated_delivery_date', orderable: true, searchable: true, width: "20%"},
                {data: 'action', name: 'action', orderable: false, searchable: false, width: "15%"},

            ],
            "drawCallback": function (settings) {
                $('[data-toggle="tooltip"]').tooltip();

                if (!$('.dt-buttons').find('#filter').length) {
                    $('.dt-buttons').append('<select id="filter" autocomplete="off">\n' +
                        '                        <option value="1">Today</option>\n' +
                        '                        <option value="2" selected>This Month</option>\n' +
                        '                        <option value="3">This Year</option>\n' +
                        '                    </select>');
                }

                if (!$('.dt-buttons').find('#project_filter').length) {
                    $('.dt-buttons').append('<select id="project_filter" autocomplete="off">' +
                        '<option value="">All</option>\n'+
                        @foreach($projects as $project)
                            '<option value="{{ $project->id }}">{{ $project->project_name }}</option>\n' +
                        @endforeach
                            '</select>');
                }

                if (settings.json) {
                    $('#filter_quantity').html(settings.json.total_quantity);
                    $('#filter_price').html(settings.json.cost_summary);
                }
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
                    filename: 'Budget_List_'+(new Date).getTime(),
                    exportOptions: {
                        columns: [ 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'excelHtml5',
                    filename: 'Budget_List_'+(new Date).getTime(),
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
                    filename: 'Budget_List_'+(new Date).getTime(),
                    title: 'Budget List',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    exportOptions: {
                        columns: [ 1, 2, 3, 4]
                    },
                    customize : function(doc) {
                        doc.content[1].table.widths = ['25%', '25%', '25%', '25%'];
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    exportOptions: {
                        columns: [ 1, 2, 3, 4]
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
        /* Exam datatable end */

        /* DATATABLE FILTER */
        $('#filter').change(function () {
            filter = $(this).val();
            dataTable.ajax.reload();
        });
        /* DATATABLE FILTER END */

        /* DATATABLE FILTER */
        $('#project_filter').change(function () {
            project_filter = $(this).val();
            console.log(project_filter);
            dataTable.ajax.reload();
        });
        /* DATATABLE FILTER END */

        /* Show budget list */
        function showAddCard() {
            $('#edit-budget-card, #budget-list-card').hide();
            $('#add-budget-card').show();
        }
        function showUpdateCard() {
            $('#add-budget-card, #budget-list-card').hide();
            $('#edit-budget-card').show();
        }
        function showListCard() {
            $('#edit-budget-card, #add-budget-card').hide();
            $('#budget-list-card').show();
        }

        $('.show-budget-list').click(function () {
            showListCard();
        });

        $('#hide-budget-list').click(function () {
            showAddCard();
        });
        /* Show budget list end */

        /* autoComplete product */
        function initializeProduct(target) {
            $(target).autocomplete({
                source: function (request, response) {
                    $.ajax({
                        type: "POST",
                        url:"{{ route('products-search') }}",
                        data: {term: request.term, _token: "{{ csrf_token() }}"},
                        success: function (data) {
                            response($.map(data, function (item) {
                                return {
                                    label: item.title,
                                    id: item.id
                                };
                            }));
                        },
                        dataType: 'json'
                    });
                },
                minLength: 2,
                delay: 500,
                select: function( event, ui ) {
                    (event.target).nextElementSibling.value = ui.item.id;
                }
            });
        }

        initializeProduct('.product_name');
        /* autoComplete product end */

        /* ADD MULTIPLE PRODUCT ROW */
        $('.add_product').click(function () {
            if ($(this).attr('data-action') == 'add') {
                if ($('.product_name').last().val()) {
                    $('#product-container').append($('#product-row').html());
                    initializeProduct('.product_name');
                } else {
                    $('.product_name').last().focus();
                    demo.showNotification('Complete the first product!', 'error');
                }
            } else {
                if ($('.update_product_name').last().val()) {
                    $('#edit-budget-form-container').find('#update-product-container').append($('#update-product-row').html());
                    initializeProduct('.update_product_name');
                } else {
                    $('.update_product_name').last().focus();
                    demo.showNotification('Complete the first product!', 'error');
                }
            }
        });
        /* ADD MULTIPLE PRODUCT ROW END */

        /* REMOVE PRODUCT ROW */
        $(document).on('click', '.remove-row', function () {
            $(this).parent().parent().parent().remove();
            updateTotalPrice();
            updateTotalPriceUpdateForm();
        });
        /* REMOVE PRODUCT ROW END */

        /* UPDATE TOTAL PRICE */
        $(document).on('input', '.quantity', function () {
            updateTotalPrice();
        });
        $(document).on('input', '.unit_price', function () {
            updateTotalPrice();
        });
        $(document).on('input', '#convince_bill', function () {
            updateTotalPrice();
        });

        function updateTotalPrice() {
            total_cost = 0
            $('.product_row').each(function(i, obj) {
                total_cost += $(this).find('.quantity').val() * $(this).find('.unit_price').val();
            });

            total_cost += parseInt($('#convince_bill').val());
            $('#total_price').val(total_cost);
        }
        /* UPDATE TOTAL PRICE END */

        /* Add mark form submit */
        $('#add-budget-form-submit').click(function () {
            if (remaining_budget < total_cost) {
                demo.showNotification('You are crossing budget limit', "error");
                return false;
            }
            let button = $(this);
            let form = $('#add-budget-form').parsley();
            form.validate();

            if (form.isValid()) {
                demo.showLoader(button);
                $.ajax({
                    url: '{{ route('budgets.store') }}',
                    type: 'post',
                    data: $('#add-budget-form').serialize(),
                    loadSpinner: true,
                    success: function (response) {
                        demo.hideLoader(button, 'Add Budget');
                        if (response.status == 'validation-error'){
                            demo.showNotification(JSON.stringify(response.message), 'error');
                        } else if (response.status) {
                            demo.showNotification(response.message,"success");

                            $("#add-budget-form").trigger('reset');
                            $("#add-budget-form").parsley().reset();
                            dataTable.ajax.reload();
                        } else {
                            demo.showNotification(response.message, "error");
                        }
                    }
                });
            }
        });
        /* Add mark form submit end */

        /* Edit budget form trigger */
        $(document).on('click', '.edit-budget', function () {
            var budget_id = $(this).attr('data-budget_id');
            if (budget_id) {
                $('.szn-preloader').show();
                $.ajax({
                    url: '{{ route('budgets.edit') }}',
                    type: 'post',
                    data: {_token: '{{ csrf_token() }}', budget_id: budget_id},
                    loadSpinner: true,
                    success: function (response) {
                        $('.szn-preloader').fadeOut('slow');
                        if (response.status == 'validation-error'){
                            demo.showNotification(JSON.stringify(response.html), 'error');
                        } else if (response.status) {
                            $('#edit-budget-form-container').html(response.html);
                            showUpdateCard();

                            /* Edit user form submit */
                            $('#edit-budget-form')
                                .parsley()
                                .on('form:success', function (formInstance) {
                                    if (formInstance.isValid()) {
                                        demo.showLoader($('#edit-budget-submit'));
                                        $.ajax({
                                            url: '{!! url('budget/update') !!}',
                                            type: 'post',
                                            data: $('#edit-budget-form').serialize(),
                                            loadSpinner: true,
                                            success: function (response) {
                                                demo.hideLoader($('#edit-budget-submit'), 'Update Budget');
                                                if (response.status == 'validation-error'){
                                                    demo.showNotification(JSON.stringify(response.message), 'error');
                                                } else if (response.status) {
                                                    demo.showNotification(response.message,"success");
                                                    dataTable.ajax.reload();
                                                    $('#edit-budget-form-container').html('');
                                                    showListCard();
                                                } else {
                                                    demo.showNotification(response.message, "error");
                                                }
                                            }
                                        });
                                    }
                                }).on('form:submit', function () {
                                return false;
                            });
                            /* Edit user form submit end */
                        } else {
                            demo.showNotification(response.message, "error");
                        }
                    }
                });
            }
        });
        /* Edit budget form trigger end */

        /* UPDATE TOTAL PRICE */
        $(document).on('input', '.update_quantity', function () {
            updateTotalPriceUpdateForm();
        });
        $(document).on('input', '.update_unit_price', function () {
            updateTotalPriceUpdateForm();
        });
        $(document).on('input', '#update_convince_bill', function () {
            updateTotalPriceUpdateForm();
        });

        function updateTotalPriceUpdateForm() {
            var total_price = 0
            $('.update_product_row').each(function(i, obj) {
                total_price += $(this).find('.update_quantity').val() * $(this).find('.update_unit_price').val();
            });

            total_price += parseInt($(document).find('#update_convince_bill').val());
            $(document).find('#update_total_price').val(total_price);
        }
        /* UPDATE TOTAL PRICE END */

        /* Delete user */
        $(document).on('click','.delete-budget', function() {
            let budget_id = $(this).attr('data-budget_id');
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
                    $('.szn-preloader').show();
                    $.ajax({
                        url: '{!! url('budgets') !!}'+'/'+budget_id,
                        type: 'DELETE',
                        loadSpinner: true,
                        data: {_token: '{{ csrf_token() }}'},
                        success: function (response) {
                            $('.szn-preloader').fadeOut('slow');
                            if (response.status) {
                                demo.showNotification(response.message, 'success');
                                dataTable.ajax.reload();
                            } else {
                                demo.showNotification(response.message, 'error');
                            }
                        }
                    });
                }
            })
        });
        /* Delete user end */
        /* UPDATE TOTAL PRICE END */

        /* VIEW BUDGET */
        $(document).on('click','.view-budget', function() {
            let budget_id = $(this).attr('data-budget_id');
            if (budget_id) {
                $('.szn-preloader').show();
                $.ajax({
                    url: '{{ route('budgets.show') }}',
                    type: 'post',
                    loadSpinner: true,
                    data: {_token: '{{ csrf_token() }}', budget_id: budget_id},
                    success: function (response) {
                        $('.szn-preloader').fadeOut('slow');
                        if (response.status) {
                            $('#budget-container').html(response.html);
                            $('#viewBudget').modal('toggle');
                        } else {
                            demo.showNotification(response.message, 'error');
                        }
                    }
                });
            }
        });
        /* VIEW BUDGET END */

        $('#project').change(function () {
            remaining_budget = $(this).find(':selected').attr('data-remaining_budget');
            var value = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'BDT',
            }).format(remaining_budget);
            $('.remaining_budget').val(value);
        });
    });
</script>
