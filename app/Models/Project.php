<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_name', 'total_budget', 'remaining_budget', 'staff_person'];

    public static function rules($id = 0, $merge = [])
    {
        return array_merge(
            [
                'project_name' => 'required',
                'total_budget' => 'required',
                'staff_person' => 'required',
            ],
            $merge);
    }

    /**
     * Relation
     */

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

}
