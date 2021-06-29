<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Validator;
use Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $data = ['menu' => 'users'];
        return view('users.index')->with($data);
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return $this->redirectFailure('login', 'Direct access is denied.');
        }

        $list = User::get();

        return Datatables::of($list)
            ->addColumn('action', function ($list) {
                return '<button  id="edit-' . $list->id . '" data-user_id="' . $list->id . '" data-user_name="' . $list->name . '" data-user_email="' . $list->email . '" data-status="' . $list->status . '" class="edit-user btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                            <i class="fa fa-pencil-square-o"></i>
                        </button>
                        <button  id="delete-' . $list->id . '" data-user_id="' . $list->id . '" class="btn btn-sm btn-danger delete-user '. ($list->id == User::default ? "d-none" : "") .'" data-toggle="tooltip" title="Delete">
                            <i class="fa fa-trash-o"></i>
                        </button> ';
            })
            ->editColumn('status', function ($list) {
                return '<span class="badge badge-' . ($list->status ? 'warning' : 'primary') . '">' . (!$list->status ? 'Active' : 'Inactive') . '</span>';
            })
            ->rawColumns(['status', 'action'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->only('name', 'email');

        $validator = Validator::make($input, User::rules());

        if ($validator->fails()) {
            return Response::json([
                'html' => $validator->errors(),
                'status' => 'validation-error'
            ]);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone', null),
            'password' => bcrypt($request->input('password')),
            'status' => $request->input('status')
        ]);

        if ($user) {
            return Response::json(['status' => 'success', 'html' => 'User Added Successfully!']);
        } else {
            return Response::json(['status' => 'error', 'html' => 'User Not Added!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $data['user'] = User::find(Auth::id());
        $data['menu'] = 'users';

        return view('users.show-user')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) {
            return $this->redirectFailure('login', 'Direct access is denied.');
        }

        $input = $request->only('name', 'email');

        $validator = Validator::make($input, User::rules($request->get('id')));

        if ($validator->fails()) {
            return Response::json([
                'html' => $validator->errors(),
                'status' => 'validation-error'
            ]);
        }

        $user = User::where('id', $request->get('id'))
            ->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone', null),
                'status' => $request->input('status'),
            ]);

        if ($user) {
            return Response::json([
                'html' => 'User Updated successfully!',
                'status' => 'success'
            ]);
        } else {
            return Response::json([
                'html' => 'User not updated!',
                'status' => 'error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $default = User::findOrFail(User::default);

        if ($user->id != $default->id) {
            $user->delete();

            return Response::json([
                'html' => 'User deleted successfully!',
                'status' => 'success'
            ]);
        }
        return Response::json([
            'html' => ($user->id == $default->id) ? "Admin can't be deleted" : "User not deleted!",
            'status' => 'error'
        ]);
    }

    public function passwordChange(Request $request)
    {
        if ( Hash::make($request->get('new_password')) == Auth::user()->password) {
            return response()->json(['status' => 'error', 'html' => "Your current password can't be with new password"]);
        }

        if ($request->get('new_password') != $request->get('confirm_password')) {
            return response()->json(['status' => 'error', 'html' => "Please confirm password correctly"]);
        }

        $user = User::find(Auth::id());

        if (Hash::check($request->get('password'), $user->password)) {
            $userUpdate = $user->update(['password' => Hash::make($request->get('new_password'))]);
            if ($userUpdate) {
                return response()->json(['status' => 'success', 'html' => 'Password Changed Successfully!']);
            } else {
                return response()->json(['status' => 'error', 'html' => 'Password Not Changed!']);
            }
        } else {
            return response()->json(['status' => 'error', 'html' => 'Wrong password. Please provide correct password!']);
        }
    }
}
