<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Studnet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Validator;
use Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $data = ['menu' => 'projects'];
        return view('projects.index')->with($data);
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return $this->redirectFailure('login', 'Direct access is denied.');
        }

        $list = Project::with('expenses')->get();

        return Datatables::of($list)
            ->addColumn('action', function ($list) {
                return '<button  id="edit-' . $list->id . '" data-project_id="' . $list->id . '" class="edit-project btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                            <i class="fa fa-pencil-square-o"></i>
                        </button>
                        <button  id="delete-' . $list->id . '" data-project_id="' . $list->id . '" class="delete-project btn btn-sm btn-danger" data-toggle="tooltip" title="Delete">
                            <i class="fa fa-trash-o"></i>
                        </button> ';
            })
            ->editColumn('status', function ($list) {
                return '<span class="badge badge-' . ($list->status ? 'primary' : 'warning') . '">' . ($list->status ? 'Active' : 'Inactive') . '</span>';
            })
            ->editColumn('image', function ($list) {
                if (empty($list->image)) {
                    return '';
                } else {
                    return '<img width="50" src="'. asset('/upload/projectImage/'.$list->image) .'" />';
                }
            })
            ->addColumn('total_expense', function ($list) {
                return $list->expenses->sum('total_cost');
            })
            ->editColumn('created_at', function ($list) {
                return $list->created_at->format('M d, Y');
            })
            ->rawColumns(['status', 'image', 'action'])->make(true);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->only('project_name', 'total_budget', 'staff_person');

        $validator = Validator::make($input, Project::rules());

        if ($validator->fails()) {
            return response()->json([
                'html' => $validator->errors(),
                'status' => 'validation-error'
            ]);
        }

        $project = Project::create($request->except(['_token']));

        if ($project) {
            return response()->json(['status' => true, 'html' => 'Project Added Successfully!']);
        } else {
            return response()->json(['status' => false, 'html' => 'Project Not Added!']);
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
        $data['project'] = Project::with('examinees', 'examinees.exam')->find($id);
        $data['menu'] = 'projects';

        return view('projects.show-project')->with($data);
    }

    public function search(Request $request)
    {
        $project = Project::Where('title', 'Like', '%'.$request->get('term').'%')
            ->whereStatus(Project::ACTIVE)
            ->select('id', 'title')->get();
        return json_encode($project);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit(Request $request)
    {
        if (!$request->ajax()) {
            return $this->redirectFailure('login', 'Direct access is denied.');
        }

        $project = Project::find($request->get('project_id'));

        if (empty($project)) {
            return response()->json([
                'html' => "No data found",
                'status' => false
            ]);
        }

        return response()->json([
            'html' => view('projects.edit-project')->with(['project' => $project])->render(),
            'status' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) {
            return $this->redirectFailure('login', 'Direct access is denied.');
        }

        $input = $request->only('project_name', 'total_budget', 'staff_person');

        $validator = Validator::make($input, Project::rules($request->get('id')));

        if ($validator->fails()) {
            return response()->json([
                'html' => $validator->errors(),
                'status' => 'validation-error'
            ]);
        }

        $project = Project::where('id', $request->get('id'))->update($request->except(['_token', 'project_pic', 'old_image']));

        if ($project) {
            return response()->json([
                'html' => 'Project Updated successfully!',
                'status' => true
            ]);
        } else {
            return response()->json([
                'html' => 'Project not updated!',
                'status' => false
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Project $project)
    {
        if ($project) {
            if (!empty($project->image)) {
                unlink(public_path('upload/projectImage/' . $project->image));
            }

            $project->delete();

            return response()->json([
                'html' => 'Project deleted successfully!',
                'status' => 'success'
            ]);
        }
        return response()->json([
            'html' => 'Project not deleted!',
            'status' => 'error'
        ]);
    }
}
