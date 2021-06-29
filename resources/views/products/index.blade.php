@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatable/css/datatables.min.css') }}">
    <style type="text/css">
        .dt-button {
            padding: 2.5px 15px !important;
            border-radius: 5px !important;
            color: #4c4c4c !important;
        }
        .dt-buttons {
            margin-left: 6px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header d-flex justify-content-between align-items-center bg-warning">
                    <h5 class="card-title">Products</h5>
                    <button class="btn btn-sm btn-outline-default" data-toggle="modal" data-target="#addProduct">
                        <i class="fa fa-plus"></i> Add Products
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table id="product_table" class="display responsive nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>#No</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Added Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('products.add-product')

    <!--begin::Modal-->
    <div class="modal fade" id="editProduct" tabindex="-1" role="dialog" aria-labelledby="editProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="editProductLabel">
                        Edit Product
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <h4 class="mt-0">Product Information</h4>
                    </div>
                    <div id="editProductModal">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" id="edit-product-form-submit" data-isModal="1">
                        Update Product
                    </button>
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

    @include('products.script')
@endsection
