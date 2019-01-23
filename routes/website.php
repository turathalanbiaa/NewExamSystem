<?php
/**
 * Created by PhpStorm.
 * User: Emad
 * Date: 12/10/2018
 * Time: 1:44 PM
 */

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

Route::get('/', 'Website\HomeController@index')->middleware('StudentAuth');
Route::get('/student-auth', 'Website\HomeController@studentAuth');
Route::get('/info', 'Website\HomeController@info');

//for view
Route::get('/student/getStudentExam', function (){
    $studentExam=App\Models\Student::where('edu_student_id',900)->with('exams')->has('exams')->first();
        return response()
            ->json($studentExam->exams);
});

Route::get('/student/getCourseExamByLevel', function (){
    $courseExams=App\Models\Course::where(['level'=>1,'state'=>1])->with('exams')->has('exams')->get(['id','name']);
    foreach ($courseExams as $courseExam) {
        $examsIds[]  = $courseExam->exams->pluck('id');
    }
    return response()
        ->json($examsIds);
});



Route::get('/student/insertStudentAndExam', function (){
if (!Cookie::has('remember_me')){
  $student=App\Models\Student::where('edu_student_id',900)->first();
        if (!$student) {
            $student = new App\Models\Student;
            $student->edu_student_id = "900";
            $remember_token=Hash::make(rand(1,10));
            $student->remember_token =$remember_token;
            $student->save();
            Cookie::queue(Cookie::forever('remember_me', $remember_token));
            $courseExams = App\Models\Course::where(['level' => 1, 'state' => 1])->with('exams')->has('exams')->get(['id']);
            $examsIds = collect();
            foreach ($courseExams as $courseExam) {
                $examsIds->push($courseExam->exams->pluck('id'));
            }
            $student->exams()->attach($examsIds->collapse());
            return response()->json($examsIds->collapse());
        }
        else
            {
            $student=App\Models\Student::where('edu_student_id',900)->first();
                $courseExams = App\Models\Course::where(['level' => 1, 'state' => 1])->with('exams')->has('exams')->get(['id']);
                $examsIds = collect();
                foreach ($courseExams as $courseExam) {
                    $examsIds->push($courseExam->exams->pluck('id'));
                }
                $student->exams()->sync($examsIds->collapse());
                return response($examsIds->collapse());
            }
}
else{
    /*$student=App\Models\Student::where('remember_token',Cookie::get('remember_me'))->first();
    $courseExams = App\Models\Course::where(['level' => 1, 'state' => 1])->with('exams')->has('exams')->get(['id']);
    $examsIds = collect();
    foreach ($courseExams as $courseExam) {
        $examsIds->push($courseExam->exams->pluck('id'));
    }
    $student->exams()->sync($examsIds->collapse());
    return response($examsIds->collapse());*/
    return response( $student=App\Models\Student::where('remember_token',Cookie::get('remember_me'))->first());
}
});
