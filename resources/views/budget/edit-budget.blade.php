<form id="edit-budget-form">
    @csrf
    <input type="hidden" name="budget_id" value="{{ $budget->id }}">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="project">Project Name</label>
                <p class="form-control m-0">{{ $budget->project->project_name }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group row">
                <label for="project">Estimated Delivery Date</label>
                <input type="date" class="form-control" name="estimated_delivery_date" value="{{ \Carbon\Carbon::parse($budget->estimated_delivery_date)->format('Y-m-d') }}" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="project">Remaining Budget</label>
                <input readonly type="text" class="form-control update_remaining_budget" value="{{ $budget->project->total_budget - $budget->project->total_expense }}" />
            </div>
        </div>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th width="33%">Product</th>
            <th width="33%">Quantity</th>
            <th width="33%">Price</th>
            <th width="1%"></th>
        </tr>
        </thead>
        <tbody id="update-product-container">
        @foreach($budget->budget_details as $key => $budget_detail)
            <tr class="update_product_row">
            <td>
                <div class="form-group">
                    <input type="text" placeholder="Enter product name" class="form-control update_product_name" name="product_name[]" value="{{ $budget_detail->product->title }}" id="product_name" required>
                    <input type="hidden" name="product_id[]" value="{{ $budget_detail->product_id }}" class="update_product_id">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" placeholder="Enter quantity" min="1" class="form-control update_quantity" name="quantity[]" value="{{ $budget_detail->quantity }}" required>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" placeholder="Enter unit price" min="1" class="form-control update_unit_price" id="unit_price" name="unit_price[]" value="{{ $budget_detail->unit_price }}" required>
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
                <strong>Convince Bill</strong>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" value="{{ $budget->convince_bill }}" class="form-control" id="update_convince_bill" name="convince_bill" required>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <strong>Total Price</strong>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" placeholder="0" class="form-control" value="{{ $budget->total_cost }}" id="update_total_price" readonly required>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary" id="edit-budget-form-submit">
        Update Budget
    </button>
</form>
