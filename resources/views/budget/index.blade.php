@extends('layouts.app', ['menu' => 'budget'])

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatable/css/datatables.min.css') }}">
    <style type="text/css">
        #add-budget-card .table td, #edit-budget-card .table td{
            border: none;
        }
        #add-budget-card .table > tbody > tr > td,  #edit-budget-card .table > tbody > tr > td{
            padding: 5px;
        }
        .form-group {
            margin-bottom: 0;
        }
        @if($type == 'add')
        #budget-list-card, #edit-budget-card {
            display: none;
        }
        @else
        #add-budget-card, #edit-budget-card {
            display: none;
        }
        @endif
        .btn-sm {
            padding: 5px 10px;
        }
        .dt-button {
            padding: 2.5px 15px !important;
            border-radius: 5px !important;
            color: #4c4c4c !important;
        }
        .dt-buttons {
            margin-left: 6px;
        }
        .preview-table tbody {
            display: block;
            max-height: 150px;
            overflow-y: scroll;
        }
        table.preview-table thead, table.preview-table tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card" id="add-budget-card">
                <div class="card-header bg-warning">
                    <div class="row w-100 m-0">
                        <div class="col-md-6 col-sm-12">
                            <h5 class="card-title">Add Budget</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="pull-right">
                                <button class="btn btn-sm btn-outline-default add_product" data-action="add" type="button">+ Add Product</button>
                                <button class="btn btn-sm btn-outline-default show-budget-list">
                                    <i class="fa fa-list-ul" aria-hidden="true"></i>
                                    Budget List
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="add-budget-form" autocomplete="off">
                        @csrf
                        <table class="table">
                            <tbody>
                            <tr>
                                <td width="33%">
                                    <div class="form-group">
                                        <label for="project">Select Project</label>
                                        <select name="project" id="project" class="form-control" required>
                                            <option value="">Select a project</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}" data-remaining_budget="{{ $project->total_budget - $project->total_expense }}">{{ $project->project_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td width="33%">
                                    <div class="form-group">
                                        <label for="project">Estimated Delivery Date</label>
                                        <input type="date" class="form-control" name="estimated_delivery_date" required>
                                    </div>
                                </td>
                                <td width="33%">
                                    <div class="form-group">
                                        <label for="project">Remaining Budget</label>
                                        <input readonly type="text" class="form-control remaining_budget" />
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="33%">Product</th>
                                    <th width="33%">Quantity</th>
                                    <th width="33%">Cost</th>
                                    <th width="1%"></th>
                                </tr>
                            </thead>
                            <tbody id="product-container">
                                <tr class="product_row">
                                    <td>
                                        <div class="form-group">
                                            <input type="text" placeholder="Enter product name" class="form-control product_name" name="product_name[]" value="" id="product_name" required>
                                            <input type="hidden" name="product_id[]" class="product_id">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" placeholder="Enter quantity" min="1" class="form-control quantity" name="quantity[]" id="quantity" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" placeholder="Enter unit price" min="1" class="form-control unit_price" id="unit_price" name="unit_price[]" required>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table">
                            <thead>
                            <tr>
                                <th width="33.3%"></th>
                                <th width="33.3%"></th>
                                <th width="33.3%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="2" align="right">
                                    <strong>Convince Bill</strong>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" value="0" class="form-control" id="convince_bill" name="convince_bill" required>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right">
                                    <strong>Total Cost</strong>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="number" placeholder="0" class="form-control" id="total_price" readonly required>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" id="add-budget-form-submit">
                        Add Budget
                    </button>
                </div>
            </div>
            <div class="card" id="budget-list-card">
                <div class="card-header bg-warning">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Budget List</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span>Total Quantity: <span id="filter_quantity">0</span>, </span> <br>
                                    <span>Total Cost: <span id="filter_price"></span>0</span>
                                </div>
                                <button class="btn btn-sm btn-outline-default" id="hide-budget-list">
                                    <i class="fa fa-undo" aria-hidden="true"></i>
                                    Back
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="budget_table" class="display responsive nowrap">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Remaining Quantity</th>
                                <th>Unit Price</th>
                                <th>Estimated Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card" id="edit-budget-card">
                <div class="card-header d-flex justify-content-between align-items-center bg-warning">
                    <h5 class="card-title">Update Budget</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-default add_product" data-action="update" type="button">+ Add Product</button>
                        <button class="btn btn-sm btn-outline-default show-budget-list">
                            <i class="fa fa-undo" aria-hidden="true"></i>
                            Back
                        </button>
                    </div>
                </div>
                <div class="card-body" id="edit-budget-form-container">

                </div>
            </div>
        </div>
    </div>

    <!--begin::Modal-->
    <div class="modal fade" id="viewBudget" tabindex="-1" role="dialog" aria-labelledby="viewBudgetLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="viewBudgetLabel">
                        View Budget
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="budget-container">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        Close
                    </button>
{{--                    <button type="button" class="btn btn-primary" id="edit-product-form-submit" data-isModal="1">--}}
{{--                        Update Product--}}
{{--                    </button>--}}
                </div>
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/datatable/js/datatables.min.js') }}"></script>

    <script src="{{ asset('assets/js/parsley/parsley.min.js') }}"></script>
    <script type="text/template" id="product-row">
        <tr class="product_row">
            <td>
                <div class="form-group">
                    <input type="text" placeholder="Enter product name" class="form-control product_name" name="product_name[]" value="" required>
                    <input type="hidden" name="product_id[]" class="product_id">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" placeholder="Enter quantity" min="1" class="form-control quantity" name="quantity[]" required>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" placeholder="Enter unit price" min="1" class="form-control unit_price" name="unit_price[]" required>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <button class="btn btn-sm remove-row" type="button"><i class="fa fa-trash"></i></button>
                </div>
            </td>
        </tr>
    </script>
    <script type="text/template" id="update-product-row">
        <tr class="update_product_row">
            <td>
                <div class="form-group">
                    <input type="text" placeholder="Enter product name" class="form-control update_product_name" name="product_name[]" value="" required>
                    <input type="hidden" name="product_id[]" class="product_id">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" placeholder="Enter quantity" min="1" class="form-control update_quantity" name="quantity[]" required>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" placeholder="Enter unit price" min="1" class="form-control update_unit_price" name="unit_price[]" required>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <button class="btn btn-sm remove-row" type="button"><i class="fa fa-trash"></i></button>
                </div>
            </td>
        </tr>
    </script>

    @include('budget.script')
@endsection
