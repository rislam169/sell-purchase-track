<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['expense_id', 'product_id', 'product_name', 'supplier_name', 'quantity', 'unit_price', 'budget_price', ];

    /* RELATION START */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
    /* RELATION END */
}
