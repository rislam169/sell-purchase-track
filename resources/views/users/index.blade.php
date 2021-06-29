@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables-1.10.20/jquery.dataTables.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Users</h5>
                    <button class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#addUser">
                        <i class="fa fa-plus"></i> Add User
                    </button>
                </div>
                <div class="card-body">
                    <table id="user_table" class="display">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('users.add-user')
    @include('users.edit-user')
@endsection

@section('script')

    <script src="{{ asset('assets/js/dataTables-1.10.20/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/parsley/parsley.min.js') }}"></script>

    @include('users.script')
@endsection
