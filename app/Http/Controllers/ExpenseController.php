<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseDetail;
use App\Models\Product;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Validator;
use Response;
use DB;

class ExpenseController extends Controller
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
        return view('expense.index')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->only('project', 'expense_date', 'product_name', 'product_id', 'quantity', 'unit_price');

        $validator = Validator::make($input, Expense::rules());

        if ($validator->fails()) {
            return response()->json(['status' => 'validation-error', 'message' => $validator->errors()]);
        }

        $totalQuantity = 0;
        $totalPrice = 0;
        foreach ($request->get('product_id') as $key => $product_id) {
            if (!$product_id) {
                return response()->json(['status' => false, 'message' => 'Product found out of budget!']);
            }
            $totalQuantity += $request->get('quantity')[$key];
            $totalPrice += $request->get('unit_price')[$key] * $request->get('quantity')[$key];
        }
        $totalPrice += $request->get('convince_bill');

        DB::beginTransaction();

        try {
            $expense = Expense::create([
                'project_id' => $request->get('project'),
                'total_quantity' => $totalQuantity,
                'convince_bill' => $request->get('convince_bill'),
                'total_cost' => $totalPrice,
                'expense_date' => Carbon::parse($request->get('expense_date'))->format('Y-m-d h:m:s'),
            ]);

            if ($expense) {
                foreach ($request->get('product_id') as $key => $product_id) {
                    ExpenseDetail::create([
                        'expense_id' => $expense->id,
                        'product_id' => $product_id,
                        'product_name' => $request->get('product_name')[$key],
                        'supplier_name' => $request->get('supplier_name')[$key],
                        'quantity' => $request->get('quantity')[$key],
                        'unit_price' => $request->get('unit_price')[$key],
                        'budget_price' => $request->get('budget_price')[$key]
                    ]);
                }
            }

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Expense Added Successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Expense Not Added!']);
        }
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return redirect()->route('login', 'Direct access is denied.');
        }

        if ($request->get('filter') == 1) {
            $list = ExpenseDetail::whereHas('expense', function($query) {
                $query->whereDate('expense_date', Carbon::today());
            });
        } elseif ($request->get('filter') == 3) {
            $list = ExpenseDetail::whereHas('expense', function($query) {
                $query->whereYear('expense_date', Carbon::now()->year);
            });
        } else {
            $list = ExpenseDetail::whereHas('expense', function($query) {
                $query->whereMonth('expense_date', Carbon::now()->month);
            });
        }

        if ($request->get('project_filter')) {
            $list->whereHas('expense', function($query) use ($request) {
                $query->where('project_id', $request->get('project_filter'));
            });
        }

        $list = $list->get();

        $total_cost = 0;
        $total_profit = 0;
        foreach ($list as $item) {
            $total_cost += $item->quantity * $item->unit_price;
            $total_profit += $item->quantity * ($item->budget_price - $item->unit_price);
        }

        return Datatables::of($list)
            ->addColumn('action', function ($list) {
                return '<button data-expense_id="' . $list->expense->id . '" class="view-expense btn btn-sm btn-success" data-toggle="tooltip" title="View">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button data-expense_id="' . $list->expense->id . '" class="edit-expense btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                            <i class="fa fa-pencil-square-o"></i>
                        </button>
                        <button data-expense_id="' . $list->expense->id . '" class="delete-expense btn btn-sm btn-danger" data-toggle="tooltip" title="Delete">
                            <i class="fa fa-trash-o"></i>
                        </button> ';
            })
            ->addColumn('expense_date', function ($list) {
                return Carbon::parse($list->expense->expense_date)->format('M d, Y');
            })
            ->addColumn('profit', function ($list) {
                return $list->budget_price - $list->unit_price;
            })
            ->editColumn('unit_price', function ($list) {
                return number_format($list->unit_price, 2);
            })
            ->with('total_quantity', $list->sum('quantity'))
            ->with('total_cost', number_format($total_cost), 2)
            ->with('total_profit', number_format($total_profit), 2)
            ->make(true);
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

        $data['expense'] = Expense::with('expense_details', 'expense_details.product')->where('id', $request->get('expense_id'))->first();
        $data['projects'] = Project::all();
        if ($data['expense']) {
            return response()->json([
                'status' => true,
                'html' => view('expense.edit-expense')->with($data)->render()
            ]);
        } else {
            return response()->json(['status' => false, 'message' => 'Expense not found!']);
        }
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

        $data['expense'] = Expense::with('expense_details', 'expense_details.product')->where('id', $request->get('expense_id'))->first();

        if ($data['expense']) {
            return response()->json([
                'status' => true,
                'html' => view('expense.view-expense')->with($data)->render()
            ]);
        } else {
            return response()->json(['status' => true, 'message' => 'Expense not found!']);
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

        $input = $request->only('product_name', 'expense_date', 'product_id', 'quantity', 'unit_price');

        $validator = Validator::make($input, Expense::updateRules());

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
            $expense = Expense::where('id', $request->get('expense_id'))->update([
                'total_quantity' => $totalQuantity,
                'convince_bill' => $request->get('convince_bill'),
                'total_cost' => $totalPrice,
                'expense_date' => Carbon::parse($request->get('expense_date'))->format('Y-m-d h:m:s'),
            ]);

            if ($expense) {
                ExpenseDetail::where('expense_id', $request->get('expense_id'))->delete();

                foreach ($request->get('product_id') as $key => $product_id) {
                    ExpenseDetail::create([
                        'expense_id' => $request->get('expense_id'),
                        'product_id' => $product_id,
                        'product_name' => $request->get('product_name')[$key],
                        'supplier_name' => $request->get('supplier_name')[$key],
                        'quantity' => $request->get('quantity')[$key],
                        'unit_price' => $request->get('unit_price')[$key],
                        'budget_price' => $request->get('budget_price')[$key],
                    ]);
                }
            }

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Expense Updated Successfully!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => 'Expense Not Updated!']);
        }
    }

    public function productSearch(Request $request)
    {
        $products = Product::join('budget_details', 'products.id', '=', 'budget_details.product_id')
            ->join('budgets', 'budget_details.budget_id', '=', 'budgets.id')
            ->where('project_id', $request->get('project_id'))
            ->select('products.id', 'products.title', 'budget_details.quantity', 'budget_details.unit_price')
            ->get();

        $expenses = Expense::with('expense_details')->get();
        $finalResult = [];
        foreach ($products as $product) {
            $availableQuantity = $this->getRemainingProduct($request->get('project_id'), $product->id, $product->quantity, $expenses);
            if ($availableQuantity) {
                $product->quantity = $availableQuantity;
                $finalResult[] = $product;
            }
        }
        return json_encode($finalResult);
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
     * Remove the specified resource from storage.
     *
     * @param Exam $exams
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $expense = Expense::find($id);
        if ($expense) {
            $expense->delete();

            return response()->json([
                'message' => 'Expense deleted successfully!',
                'status' => true
            ]);
        }
        return response()->json([
            'message' => 'Expense not deleted!',
            'status' => false
        ]);
    }
}
