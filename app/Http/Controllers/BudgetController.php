<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Examinee;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Budget;
use App\Models\BudgetDetail;
use App\Models\Project;
use App\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Validator;
use Response;
use DB;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function index($type)
    {
        $data['type'] = $type;
        $data['projects'] = Project::all();
        return view('budget.index')->with($data);
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
        $input = $request->only('project', 'product_name', 'product_id', 'quantity', 'unit_price', 'convince_bill', 'estimated_delivery_date');

        $validator = Validator::make($input, Budget::rules());

        if ($validator->fails()) {
            return response()->json(['status' => 'validation-error', 'message' => $validator->errors()]);
        }

        $totalQuantity = 0;
        $totalPrice = 0;
        foreach ($request->get('product_id') as $key => $product_id) {
            $totalQuantity += $request->get('quantity')[$key];
            $totalPrice += $request->get('unit_price')[$key] * $request->get('quantity')[$key];
        }
        $totalPrice += $request->get('convince_bill');

        DB::beginTransaction();

        try {
            $budget = Budget::create([
                'project_id' => $request->get('project'),
                'total_quantity' => $totalQuantity,
                'convince_bill' => $request->get('convince_bill'),
                'total_cost' => $totalPrice,
                'estimated_delivery_date' => Carbon::parse($request->get('estimated_delivery_date'))->format('Y-m-d h:m:s'),
            ]);

            if ($budget) {
                foreach ($request->get('product_id') as $key => $product_id) {

                    if (!$product_id) {
                        $product = Product::create(['title' => $request->get('product_name')[$key]]);
                        $product_id = $product->id;
                    }

                    BudgetDetail::create([
                        'budget_id' => $budget->id,
                        'product_id' => $product_id,
                        'quantity' => $request->get('quantity')[$key],
                        'unit_price' => $request->get('unit_price')[$key]
                    ]);
                }
            }

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Budget Added Successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Budget Not Added!']);
        }
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return redirect()->route('login', 'Direct access is denied.');
        }

        if ($request->get('filter') == 1) {
            $list = BudgetDetail::whereHas('budget', function($query) {
                $query->whereDate('estimated_delivery_date', Carbon::today());
            });
        } elseif ($request->get('filter') == 3) {
            $list = BudgetDetail::whereHas('budget', function($query) {
                $query->whereYear('estimated_delivery_date', Carbon::now()->year);
            });
        } else {
            $list = BudgetDetail::whereHas('budget', function($query) {
                $query->whereMonth('estimated_delivery_date', Carbon::now()->month);
            });
        }

        if ($request->get('project_filter')) {
            $list->whereHas('budget', function($query) use ($request) {
                $query->where('project_id', $request->get('project_filter'));
            });
        }

        $list = $list->get();

        $expenses = Expense::with('expense_details')->get();

        $total_cost = 0;
        foreach ($list as $item) {
            $total_cost += $item->quantity * $item->unit_price;
        }

        return datatables()->of($list)
            ->addColumn('action', function ($list) {
                return '<button data-budget_id="' . $list->budget->id . '" class="view-budget btn btn-sm btn-success" data-toggle="tooltip" title="View">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button data-budget_id="' . $list->budget->id . '" class="edit-budget btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                            <i class="fa fa-pencil-square-o"></i>
                        </button>
                        <button data-budget_id="' . $list->budget->id . '" class="delete-budget btn btn-sm btn-danger" data-toggle="tooltip" title="Delete">
                            <i class="fa fa-trash-o"></i>
                        </button> ';
            })
            ->editColumn('estimated_delivery_date', function ($list) {
                return Carbon::parse($list->budget->estimated_delivery_date)->format('M d, Y');
            })
            ->editColumn('unit_price', function ($list) {
                return number_format($list->unit_price, 2);
            })
            ->addColumn('product_name', function ($list) {
                return $list->product->title;
            })
            ->addColumn('remaining_quantity', function ($list) use ($expenses) {
                return $this->getRemainingProduct($list->budget->project_id, $list->product_id, $list->quantity, $expenses);
            })
            ->with('total_quantity', $list->sum('quantity'))
            ->with('cost_summary', number_format($total_cost, 1))
            ->make(true);
    }

    public function getRemainingProduct($projectId, $productId, $budgetQuantity, $expenses)
    {
        $expenseQuantity = 0;
        foreach ($expenses as $expense) {
            if ($expense->project_id == $projectId) {
                foreach ($expense->expense_details as $expense_detail) {
                    if ($expense_detail->product_id == $productId) {
                        $expenseQuantity += $expense_detail->quantity;
                    }
                }
            }
        }

        return $budgetQuantity - $expenseQuantity;
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function show(Request $request)
    {
        if (!$request->ajax()) {
            return redirect()->route('login', 'Direct access is denied.');
        }

        $data['budget'] = Budget::with('budget_details', 'budget_details.product')->where('id', $request->get('budget_id'))->first();

        if ($data['budget']) {
            return response()->json([
                'status' => true,
                'html' => view('budget.view-budget')->with($data)->render()
            ]);
        } else {
            return response()->json(['status' => true, 'message' => 'Budget not found!']);
        }

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
            return redirect()->route('login', 'Direct access is denied.');
        }

        $data['budget'] = Budget::with('project', 'budget_details', 'budget_details.product')->where('id', $request->get('budget_id'))->first();

        if ($data['budget']) {
            return response()->json([
                'status' => true,
                'html' => view('budget.edit-budget')->with($data)->render()
            ]);
        } else {
            return response()->json(['status' => true, 'message' => 'Budget not found!']);
        }
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
            return redirect()->route('login', 'Direct access is denied.');
        }

        $input = $request->only('product_name', 'product_id', 'quantity', 'unit_price', 'convince_bill', 'estimated_delivery_date');

        $validator = Validator::make($input, Budget::updateRules());

        if ($validator->fails()) {
            return response()->json(['status' => 'validation-error', 'message' => $validator->errors()]);
        }

        $totalQuantity = 0;
        $totalPrice = 0;
        foreach ($request->get('product_id') as $key => $product_id) {
            $totalQuantity += $request->get('quantity')[$key];
            $totalPrice += $request->get('unit_price')[$key] * $request->get('quantity')[$key];
        }
        $totalPrice += $request->get('convince_bill');

        DB::beginTransaction();

        try {
            $budget = Budget::where('id', $request->get('budget_id'))->update([
                'total_quantity' => $totalQuantity,
                'convince_bill' => $request->get('convince_bill'),
                'total_cost' => $totalPrice,
                'estimated_delivery_date' => $request->get('estimated_delivery_date'),
            ]);

            if ($budget) {
                BudgetDetail::where('budget_id', $request->get('budget_id'))->delete();

                foreach ($request->get('product_id') as $key => $product_id) {

                    if (!$product_id) {
                        $product = Product::create(['title' => $request->get('product_name')[$key]]);
                        $product_id = $product->id;
                    }

                    BudgetDetail::create([
                        'budget_id' => $request->get('budget_id'),
                        'product_id' => $product_id,
                        'quantity' => $request->get('quantity')[$key],
                        'unit_price' => $request->get('unit_price')[$key]
                    ]);
                }
            }

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Budget Updated Successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Budget Not Updated!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Exam $exams
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $budget = Budget::find($id);
        if ($budget) {
            $budget->delete();

            return response()->json([
                'message' => 'Budget deleted successfully!',
                'status' => true
            ]);
        }
        return response()->json([
            'message' => 'Budget not deleted!',
            'status' => false
        ]);
    }
}
