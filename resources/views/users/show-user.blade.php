@extends('layouts.app')

@section('css')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-user">
                <div class="image">
                    <img src="{{ asset('assets/img/damir-bosnjak.jpg') }}" alt="Background Image">
                </div>
                <div class="card-body">
                    <div class="author">
                        <a href="javascript:void(0)">
                            <img class="avatar border-gray" src="{{ asset('assets/img/default-avatar.png') }}" alt="Profile Image">
                            <h5 class="title">{{ $user->name }}</h5>
                        </a>
                        <p class="description">
                            {{ $user->email }}
                        </p>
                    </div>
                    <p class="description text-center">
                        {{ $user->phone }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">Change Password</h5>
                </div>
                <form id="password-change-form" autocomplete="off">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="old_password" class="form-control-label">Old Password</label>
                            <input type="password" class="form-control" name="password" id="old_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password" class="form-control-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="form-control-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" data-parsley-equalto = '#new_password' required>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary" id="password-change-form-submit">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script src="{{ asset('assets/js/parsley/parsley.min.js') }}"></script>
    @include('users.script')
@endsection
