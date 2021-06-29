<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_id', 'total_quantity', 'convince_bill', 'total_cost', 'expense_date', 'miscellaneous'];

    public static function rules($id = 0, $merge = [])
    {
        return array_merge(
            [
                'project' => 'required',
                'expense_date' => 'required',
                'product_name' => 'required|array',
                'product_id' => 'required|array',
                'unit_price' => 'required|array',
                'quantity' => 'required|array',
            ],
            $merge);
    }

    public static function updateRules($id = 0, $merge = [])
    {
        return array_merge(
            [
                'expense_date' => 'required',
                'product_name' => 'required|array',
                'product_id' => 'required|array',
                'unit_price' => 'required|array',
                'quantity' => 'required|array',
            ],
            $merge);
    }

    public function expense_details()
    {
        return $this->hasMany(ExpenseDetail::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
