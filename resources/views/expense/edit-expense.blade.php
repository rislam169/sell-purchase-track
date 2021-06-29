<form id="edit-expense-form">
    @csrf
    <input type="hidden" name="expense_id" value="{{ $expense->id }}">
    <table class="table">
        <tbody>
        <tr>
            <td width="33%">
                <div class="form-group">
                    <label for="project">Select Project</label>
                    <p class="form-control m-0">{{  $expense->project->project_name }}</p>
                </div>
            </td>
            <td width="33%">
                <div class="form-group">
                    <label for="project">Estimated Delivery Date</label>
                    <input type="date" class="form-control" name="expense_date" value="{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}" required>
                </div>
            </td>
            <td width="33%"></td>
        </tr>
        </tbody>
    </table>
    <table class="table">
        <thead>
        <tr>
            <th width="25%">Product</th>
            <th width="25%">Supplier</th>
            <th width="25%">Quantity</th>
            <th width="24%">Price</th>
            <th width="1%"></th>
        </tr>
        </thead>
        <tbody id="update-product-container">
        @foreach($expense->expense_details as $key => $expense_detail)
            <tr class="update_product_row">
            <td>
                <div class="form-group">
                    <input type="text" placeholder="Enter product name" class="form-control update_product_name" name="product_name[]" value="{{ $expense_detail->product->title }}" id="product_name" required>
                    <input type="hidden" name="product_id[]" value="{{ $expense_detail->product_id }}" class="update_product_id">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" placeholder="Enter Supplier Name" class="form-control supplier_name" name="supplier_name[]" value="{{ $expense_detail->supplier_name }}">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" placeholder="Enter quantity" min="1" class="form-control update_quantity" name="quantity[]" value="{{ $expense_detail->quantity }}" required>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" placeholder="Enter unit price" min="1" class="form-control update_unit_price" id="unit_price" name="unit_price[]" value="{{ $expense_detail->unit_price }}" required>
                    <input type="hidden" class="budget_price" name="budget_price[]" value="{{ $expense_detail->budget_price}}">
                </div>
            </td>
            <td>
                @if($key)
                    <div class="form-group">
                        <button class="btn btn-sm remove-row" type="button"><i class="fa fa-trash"></i></button>
                    </div>
                @endif
            </td>
        </tr>
        @endforeach
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
                <strong>Convence Bill</strong>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" value="{{ $expense->convince_bill ?? 0 }}" class="form-control" id="update_convince_bill" name="convince_bill" required>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <strong>Total Price</strong>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" placeholder="0" class="form-control" value="{{ $expense->total_cost }}" id="update_total_price" readonly required>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary" id="edit-expense-form-submit">
        Update Purchase
    </button>
</form>
