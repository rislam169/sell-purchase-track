<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const ACTIVE = 1;
    const NOT_ACTIVE = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'category', 'description', 'image', 'status'];

    public static function rules($id = 0, $merge = [])
    {
        return array_merge(
            [
                'title' => 'required',
                'category' => 'required',
            ],
            $merge);
    }

    /**
     * Relation
     */

    public function budget_details()
    {
        return $this->hasMany(BudgetDetail::class);
    }

}
