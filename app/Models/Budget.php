<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'total_quantity', 'convince_bill', 'total_cost', 'estimated_delivery_date'];

    public static function rules($id = 0, $merge = [])
    {
        return array_merge(
            [
                'project' => 'required',
                'convince_bill' => 'required',
                'quantity' => 'required|array',
                'unit_price' => 'required|array',
                'estimated_delivery_date' => 'required',
            ],
            $merge);
    }

    public static function updateRules($id = 0, $merge = [])
    {
        return array_merge(
            [
                'convince_bill' => 'required',
                'quantity' => 'required|array',
                'unit_price' => 'required|array',
                'estimated_delivery_date' => 'required',
            ],
            $merge);
    }

    public function budget_details()
    {
        return $this->hasMany(BudgetDetail::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
