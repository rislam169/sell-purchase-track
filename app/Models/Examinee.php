<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examinee extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'exam_id', 'student_id', 'marks'];

    public static function rules($id = 0, $merge = [])
    {
        return array_merge(
            [
                'exam_id' => 'required',
                'student_id' => 'required',
            ],
            $merge);
    }

    /**
     * Relation
     */
    public function exam()
    {
        return $this->belongsTo('App\Models\Exam');
    }
    public function student()
    {
        return $this->belongsToMany('App\Models\Student');
    }

}
