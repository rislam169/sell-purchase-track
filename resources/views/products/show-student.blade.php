@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables-1.10.20/jquery.dataTables.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-user">
                <div class="image">
                    <div class="image-border"></div>
                </div>
                <div class="card-body">
                    <div class="author">
                        <a href="javascript:void(0)">
                            @if(!empty($student->image))
                            <img class="avatar border-gray" src="{{ asset('upload/studentImage/'.$student->image) }}" alt="Student Image">
                            @else
                            <img class="avatar border-gray" src="{{ asset('assets/img/default-avatar.png') }}" alt="Student Image">
                            @endif
                            <h5 class="title">{{ $student->student_name }}</h5>
                        </a>
                        <p class="description">
                            {{ $student->own_phone }}
                        </p>
                    </div>
                    <p class="description text-center">
                        {{ $student->present_address }}
                    </p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Exams</h4>
                </div>
                <div class="card-body">
                    @if($student->examinees->count() > 0)
                        <table class="table text-center student-result-table">
                            <thead class="text-primary">
                                <tr>
                                    <th width="25%">
                                        Name
                                    </th>
                                    <th width="25%">
                                        Marks
                                    </th>
                                    <th width="25%">
                                        Total
                                    </th>
                                    <th width="25%">
                                        Highest
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($student->examinees as $examinee)
                                <tr>
                                    <td>
                                        {{ $examinee->exam->exam_name }}
                                    </td>
                                    <td>
                                        {{ $examinee->marks }}
                                    </td>
                                    <td>
                                        {{ $examinee->exam->total_marks }}
                                    </td>
                                    <td>
                                        {{ $examinee->exam->highest_marks }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center">No Exam attended!</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card card-user">
                <div class="card-header">
                    <h5 class="card-title">Edit Profile</h5>
                </div>
                <div class="card-body">
                    @include('students.edit-student')
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-primary" id="edit-student-form-submit" data-isModal="0">
                        Update Student
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script src="{{ asset('assets/js/parsley/parsley.min.js') }}"></script>

    @include('students.script')
@endsection
