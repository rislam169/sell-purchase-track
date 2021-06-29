<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['budget_id', 'product_id', 'quantity', 'unit_price'];

    /* RELATION START */
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    /* RELATION END */
}
