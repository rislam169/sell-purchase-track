<script type="text/javascript">
    $(document).ready( function () {

        /* Exam datatable */
        var filter = 2;
        var project_filter = '';
        let dataTable = $('#expense_table').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
            language: {
                "lengthMenu": "Display _MENU_"
            },
            ajax: {
                url: '{{ route('expenses.list') }}',
                data: function (data) {
                    data.filter = filter;
                    data.project_filter = project_filter;
                }
            },
            columns: [
                {data: 'id', name: 'id', expenseable: true, searchable: true, visible: false},
                {data: 'product_name', name: 'product_name', expenseable: true, searchable: true, width: "20%"},
                {data: 'supplier_name', name: 'supplier_name', expenseable: true, searchable: true, width: "20%"},
                {data: 'quantity', name: 'quantity', expenseable: true, searchable: true, width: "10%"},
                {data: 'unit_price', name: 'unit_price', expenseable: true, searchable: true, width: "10%"},
                {data: 'profit', name: 'profit', expenseable: true, searchable: true, width: "10%"},
                {data: 'expense_date', name: 'expense_date', expenseable: true, searchable: true, width: "15%"},
                {data: 'action', name: 'action', expenseable: false, searchable: false, width: "15%"},

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
                    $('#filter_price').html(settings.json.total_cost);
                    $('#filter_profit').html(settings.json.total_profit);
                }
            },
            dom: '<"top-toolbar row"<"top-left-toolbar col-md-9"lB><"top-right-toolbar col-md-3"f>>rt<"bottom-toolbar"<"bottom-left-toolbar"i><"bottom-right-toolbar"p>>',
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-copy"></i>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    filename: 'Expense_List_'+(new Date).getTime(),
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'excelHtml5',
                    filename: 'Expense_List_'+(new Date).getTime(),
                    title: 'User List',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    sheetName: 'Exported data',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    pageSize: 'A4',
                    filename: 'Expense_List_'+(new Date).getTime(),
                    title: 'Expense List',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
                    },
                    customize : function(doc) {
                        doc.content[1].table.widths = ['5%', '25%', '25%', '15%', '15%', '15%'];
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5]
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

        /* Show expense list */
        function showAddCard() {
            $('#edit-expense-card, #expense-list-card').hide();
            $('#add-expense-card').show();
        }
        function showUpdateCard() {
            $('#add-expense-card, #expense-list-card').hide();
            $('#edit-expense-card').show();
        }
        function showListCard() {
            $('#edit-expense-card, #add-expense-card').hide();
            $('#expense-list-card').show();
        }

        $('.show-expense-list').click(function () {
            showListCard();
        });

        $('#hide-expense-list').click(function () {
            showAddCard();
        });
        /* Show expense list end */

        /* autoComplete product */
        function initializeProduct(target) {
            $(target).autocomplete({
                source: function (request, response) {
                    $.ajax({
                        type: "POST",
                        url:"{{ route('expense.product.search') }}",
                        data: {term: request.term, _token: "{{ csrf_token() }}", project_id: $('#project').val()},
                        success: function (data) {
                            response($.map(data, function (item) {
                                return {
                                    id: item.id,
                                    label: item.title,
                                    value: item.title,
                                    quantity: item.quantity,
                                    unit_price: item.unit_price,
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
                    (event.target).parentElement.parentElement.nextElementSibling.nextElementSibling.children[0].children[0].value = ui.item.quantity;
                    (event.target).parentElement.parentElement.nextElementSibling.nextElementSibling.children[0].children[0].attributes.max = ui.item.quantity;
                    (event.target).parentElement.parentElement.nextElementSibling.nextElementSibling.children[0].children[0].attributes[3].nodeValue = ui.item.quantity;

                    (event.target).parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.children[0].children[0].value = ui.item.unit_price;
                    (event.target).parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.children[0].children[1].value = ui.item.unit_price;
                    (event.target).parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.children[0].children[0].attributes.max = ui.item.unit_price;
                    (event.target).parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.children[0].children[0].attributes[3].nodeValue = ui.item.unit_price;

                    updateTotalPrice();
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
                    $('#edit-expense-form-container').find('#update-product-container').append($('#update-product-row').html());
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
        $(document).on('input', '#miscellaneous', function () {
            updateTotalPrice();
        });

        function updateTotalPrice() {
            var total_price = 0
            $('.product_row').each(function(i, obj) {
                total_price += $(this).find('.quantity').val() * $(this).find('.unit_price').val();
            });

            total_price += parseInt($('#convince_bill').val());
            total_price += parseInt($('#miscellaneous').val());
            $('#total_price').val(total_price);
        }
        /* UPDATE TOTAL PRICE END */

        /* Add mark form submit */
        $('#add-expense-form-submit').click(function () {
            let button = $(this);
            let form = $('#add-expense-form').parsley();
            form.validate();

            if (form.isValid()) {
                demo.showLoader(button);
                $.ajax({
                    url: '{{ route('expenses.store') }}',
                    type: 'post',
                    data: $('#add-expense-form').serialize(),
                    loadSpinner: true,
                    success: function (response) {
                        demo.hideLoader(button, 'Add Expense');
                        if (response.status == 'validation-error'){
                            demo.showNotification(JSON.stringify(response.message), 'error');
                        } else if (response.status) {
                            demo.showNotification(response.message,"success");

                            $("#add-expense-form").trigger('reset');
                            $("#add-expense-form").parsley().reset();
                            dataTable.ajax.reload();
                        } else {
                            demo.showNotification(response.message, "error");
                        }
                    }
                });
            }
        });
        /* Add mark form submit end */

        /* Edit expense form trigger */
        $(document).on('click', '.edit-expense', function () {
            var expense_id = $(this).attr('data-expense_id');
            if (expense_id) {
                $('.szn-preloader').show();
                $.ajax({
                    url: '{{ route('expenses.edit') }}',
                    type: 'post',
                    data: {_token: '{{ csrf_token() }}', expense_id: expense_id},
                    loadSpinner: true,
                    success: function (response) {
                        $('.szn-preloader').fadeOut('slow');
                        if (response.status == 'validation-error'){
                            demo.showNotification(JSON.stringify(response.html), 'error');
                        } else if (response.status) {
                            $('#edit-expense-form-container').html(response.html);
                            showUpdateCard();

                            /* Edit user form submit */
                            $('#edit-expense-form')
                                .parsley()
                                .on('form:success', function (formInstance) {
                                    if (formInstance.isValid()) {
                                        demo.showLoader($('#edit-expense-submit'));
                                        $.ajax({
                                            url: '{!! url('expense/update') !!}',
                                            type: 'post',
                                            data: $('#edit-expense-form').serialize(),
                                            loadSpinner: true,
                                            success: function (response) {
                                                demo.hideLoader($('#edit-expense-submit'), 'Update Expense');
                                                if (response.status == 'validation-error'){
                                                    demo.showNotification(JSON.stringify(response.message), 'error');
                                                } else if (response.status) {
                                                    demo.showNotification(response.message,"success");
                                                    dataTable.ajax.reload();
                                                    $('#edit-expense-form-container').html('');
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
        /* Edit expense form trigger end */

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
        $(document).on('click','.delete-expense', function() {
            let expense_id = $(this).attr('data-expense_id');
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
                        url: '{!! url('expenses') !!}'+'/'+expense_id,
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

        /* VIEW PURCHASE */
        $(document).on('click','.view-expense', function() {
            let expense_id = $(this).attr('data-expense_id');
            if (expense_id) {
                $('.szn-preloader').show();
                $.ajax({
                    url: '{{ route('expense.show') }}',
                    type: 'post',
                    loadSpinner: true,
                    data: {_token: '{{ csrf_token() }}', expense_id: expense_id},
                    success: function (response) {
                        $('.szn-preloader').fadeOut('slow');
                        if (response.status) {
                            $('#expense-container').html(response.html);
                            $('#viewExpense').modal('toggle');
                        } else {
                            demo.showNotification(response.message, 'error');
                        }
                    }
                });
            }
        });
        /* VIEW PURCHASE END */

        $('#project').change(function () {
            if ($(this).val()) {
                $('.expense-product').show();
            } else {
                $('.expense-product').hide();
            }
        });
    });
</script>
