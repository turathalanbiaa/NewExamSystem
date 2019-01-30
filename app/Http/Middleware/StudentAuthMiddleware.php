<?php

namespace App\Http\Middleware;
use App\Models\Course;
use App\Models\EduStudent;
use App\Models\Student;
use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class StudentAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Cookie::has('remember_me')){
           if(Session::has('newExamsChecked')){
                return $next($request);
            }
            else{
                $student=Student::where('remember_token',Cookie::get('remember_me'))->first();
                $eduStudent=EduStudent::find($student->edu_student_id);
                $courseExamsByLevel = Course::where(['level' => $eduStudent->level, 'state' => 1])->with('exams')->has('exams')->get(['id']);
                $examsIds = collect();
                foreach ($courseExamsByLevel as $course) {
                    $examsIds->push($course->exams->pluck('id'));
                }
                $student->exams()->sync($examsIds->collapse());
                Session::put('newExamsChecked',true);
                return $next($request);
            }
        }

       else {
           return redirect('info');
           }

       }

}
