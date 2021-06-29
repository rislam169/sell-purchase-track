<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Studnet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Validator;
use Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $data = ['menu' => 'products'];
        return view('products.index')->with($data);
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return $this->redirectFailure('login', 'Direct access is denied.');
        }

        $list = Product::get();

        return Datatables::of($list)
            ->addColumn('action', function ($list) {
                return '<button  id="edit-' . $list->id . '" data-product_id="' . $list->id . '" class="edit-product btn btn-sm btn-info" data-toggle="tooltip" title="Edit">
                            <i class="fa fa-pencil-square-o"></i>
                        </button>
                        <button  id="delete-' . $list->id . '" data-product_id="' . $list->id . '" class="delete-product btn btn-sm btn-danger" data-toggle="tooltip" title="Delete">
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
                    return '<img width="50" src="'. asset('/upload/productImage/'.$list->image) .'" />';
                }
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
        $input = $request->only('title', 'category');

        $validator = Validator::make($input, Product::rules());

        if ($validator->fails()) {
            return response()->json([
                'html' => $validator->errors(),
                'status' => 'validation-error'
            ]);
        }
        if ($request->hasFile('product_pic')) {
            $fileName = time().".".$request->file('product_pic')->getClientOriginalExtension();
            $request->merge(['image' => $fileName]);

            $request->file('product_pic')->move('upload/productImage/', $fileName);
            exec ("chmod -R 777 upload/");
        }


        $product = Product::create($request->except(['_token', 'product_pic']));

        if ($product) {
            return response()->json(['status' => true, 'html' => 'Product Added Successfully!']);
        } else {
            return response()->json(['status' => false, 'html' => 'Product Not Added!']);
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
        $data['product'] = Product::with('examinees', 'examinees.exam')->find($id);
        $data['menu'] = 'products';

        return view('products.show-product')->with($data);
    }

    public function search(Request $request)
    {
        $product = Product::Where('title', 'Like', '%'.$request->get('term').'%')
            ->whereStatus(Product::ACTIVE)
            ->select('id', 'title')->get();
        return json_encode($product);

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

        $product = Product::find($request->get('product_id'));

        if (empty($product)) {
            return response()->json([
                'html' => "No data found",
                'status' => false
            ]);
        }

        return response()->json([
            'html' => view('products.edit-product')->with(['product' => $product])->render(),
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

        $input = $request->only('title', 'category');

        $validator = Validator::make($input, Product::rules($request->get('id')));

        if ($validator->fails()) {
            return response()->json([
                'html' => $validator->errors(),
                'status' => 'validation-error'
            ]);
        }

        if ($request->hasFile('product_pic')) {
            $productDetails = Product::find($request->get('id'));
            $fileName = time().'.'.$request->file('product_pic')->getClientOriginalExtension();
            $request->merge(['image' => $fileName]);

            $request->file('product_pic')->move('upload/productImage/', $fileName);
            exec ("chmod -R 777 upload/productImage");

            if ($request->get('old_image') && file_exists('upload/productImage/'.$request->get('old_image'))) {
                unlink('upload/productImage/'.$request->get('old_image'));
            }
        }

        $product = Product::where('id', $request->get('id'))->update($request->except(['_token', 'product_pic', 'old_image']));

        if ($product) {
            return response()->json([
                'html' => 'Product Updated successfully!',
                'status' => true
            ]);
        } else {
            return response()->json([
                'html' => 'Product not updated!',
                'status' => false
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Product $product)
    {
        if ($product) {
            if (!empty($product->image)) {
                unlink(public_path('upload/productImage/' . $product->image));
            }

            $product->delete();

            return response()->json([
                'html' => 'Product deleted successfully!',
                'status' => 'success'
            ]);
        }
        return response()->json([
            'html' => 'Product not deleted!',
            'status' => 'error'
        ]);
    }
}
