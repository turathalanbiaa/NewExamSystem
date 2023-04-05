<?php

namespace App\Models;

use App\Enums\ExamType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cookie;

class Course extends Model
{
    protected $table = "course";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function exams(): HasMany
    {
        return $this->hasMany('App\Models\Exam', 'course_id')
            ->orderBy("type");
    }

    public function assessments(): HasMany
    {
        return $this->hasMany('App\Models\Assessment', 'course_id');
    }

    public function lecturer()
    {
        return $this->belongsTo("App\Models\Lecturer", "lecturer_id", "id");
    }

    public function getDecisionScore(): int {
        $sys_vars = SystemVariables::find(1);
        $student = Student::where('remember_token', Cookie::get('remember_me'))->first();

        return StudentDocument::where([
            "student_id" => $student->id,
            "course_id"  => $this->id,
            "season"     => $sys_vars->current_season,
            "year"       => $sys_vars->current_year
        ])
            ->first()
            ->decision_score ?? 0;
    }

    public function getAssessmentScore(): int {
        $student = Student::where('remember_token', Cookie::get('remember_me'))->first();

        return Assessment::where([
            "student_id" => $student->id,
            "course_id"  => $this->id
        ])
            ->first()
            ->score ?? 0;
    }
}
