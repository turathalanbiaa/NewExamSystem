<?php
namespace App\Http\Controllers\Website;
use App\Models\Course;
use App\Models\EduStudent;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class StudentAuthController extends Controller
{
    public function studentAuth(Request $request)
    {
        $crypted_token = Input::get("token");

        list($crypted_token, $enc_iv) = explode("<-::->", $crypted_token);;
        $cipher_method = 'AES-128-CTR';
        $enc_key = "TurathExamSystem";
        $enc_options = 0;
        $token = openssl_decrypt($crypted_token, $cipher_method, $enc_key, $enc_options, hex2bin($enc_iv));

        //Id come from request
        $requestId = $token;
        $eduStudent=EduStudent::find($requestId);
        $student=Student::where('edu_student_id',$eduStudent->ID)->first();

        if (!$student) {
            $student = new Student;
            $student->edu_student_id = $eduStudent->ID;
            $remember_token=Hash::make(rand(1,10));
            $student->remember_token =$remember_token;
            $student->date =Carbon::now();
            $student->save();
            $courseExamsByLevel = Course::where(['level' => $eduStudent->Level, 'state' => 1])->with('exams')->has('exams')->get(['id']);
            $examsIds = collect();
            foreach ($courseExamsByLevel as $course) {
                $examsIds->push($course->exams->pluck('id'));
            }
            $student->exams()->attach($examsIds->collapse(),['date'=>Carbon::now()]);
            Cookie::queue(cookie()->forever('remember_me', $remember_token));
            Session::put('newExamsChecked',true);
            return redirect('/');
        }
        else
        {
            $student=Student::where('edu_student_id',$eduStudent->ID)->first();
            $courseExamsByLevel = Course::where(['level' => $eduStudent->Level, 'state' => 1])->with('exams')->has('exams')->get(['id']);
            $newExamsIds = collect();
            foreach ($courseExamsByLevel as $course) {
                $newExamsIds->push($course->exams->pluck('id'));
            }
            $oldExamsIds =$student->exams->pluck('id');
            $examsIdsDiff =$newExamsIds->collapse()->diff($oldExamsIds);
            if ($examsIdsDiff->isNotEmpty()){
                $student->exams()->attach($examsIdsDiff,['date' => Carbon::now()]);
            }
            Session::put('newExamsChecked',true);
            Cookie::queue(cookie()->forever('remember_me', $student->remember_token));
            Session::put('newExamsChecked',true);
            return redirect('/');
        }
    }
    public function info(){
        return view('Website.info');
    }
    public function logout(){
        Cookie::queue(Cookie::forget('remember_me'));
        return redirect('/info');
    }
}
