<table>
    <tbody>
        <tr>
            <td>Estimated Expense Date: <strong>{{ \Carbon\Carbon::parse($budget->estimated_delivery_date)->format('M d, Y') }}</strong></td>
        </tr>
        <tr>
            <td>Total Quantity: <strong>{{ $budget->total_quantity }}</strong></td>
        </tr>
        <tr>
            <td>Total Price: <strong> ৳ {{ number_format($budget->total_cost, 2) }}</strong></td>
        </tr>
    </tbody>
</table>
<h4>Item List</h4>
<table class="table preview-table">
        <thead class="text-primary">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>`
        </tr>
        </thead>
        <tbody class="preview-items">
        @foreach($budget->budget_details as $key => $budget_detail)
            <tr>
                <td>{{ $budget_detail->product->title }}</td>
                <td>{{ $budget_detail->quantity }}</td>
                <td>৳ {{ $budget_detail->unit_price }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
