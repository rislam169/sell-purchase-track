<table>
    <tbody>
        <tr>
            <td>Total Quantity: <strong>{{ $expense->total_quantity }}</strong></td>
        </tr>
        <tr>
            <td>Total Price: <strong>৳ {{ number_format($expense->total_cost, 2) }}</strong></td>
        </tr>
        <tr>
            <td>Expense Date: <strong>{{ \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') }}</strong></td>
        </tr>
    </tbody>
</table>
<h4>Item List</h4>
<table class="table preview-table">
        <thead class="text-primary">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody class="preview-items">
        @foreach($expense->expense_details as $key => $expense_detail)
            <tr>
                <td>{{ $expense_detail->product->title }}</td>
                <td>{{ $expense_detail->quantity }}</td>
                <td>${{ $expense_detail->unit_price }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
