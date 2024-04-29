<?php

namespace App\Http\Controllers\Web;

use App\Http\Models\UserDevice;
use App\Http\Controllers\Admin\CommunicationController;
use App\RecentSearch;
use Illuminate\Support\Facades\DB;
use Mail;
use App\User;
use App\Country;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\UserCourses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Models\NotificationEmail;
use App\Http\Models\Setting;
use App\Http\Models\Blogs;
use App\Http\Models\UserQuizResultStats;
use App\Http\Models\QuizOptions_Drag_Drop;
use App\Http\Models\Category;
use App\Http\Models\QuizOptions_Multiple_Choices;
use App\Http\Models\Files;
use App\Http\Models\Feature;
use App\Http\Models\UserQuizResults;
use App\Http\Models\SubCategory;
use App\Http\Models\BlogTypes;
use App\Http\Models\Courses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Pagination\Paginator;
use App\Rules\OldPasswordMatch;
use Illuminate\Support\Str;
use Spatie\PdfToImage\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Exception;

use Illuminate\Support\Facades\Validator;

// use Illuminate\Support\Facades\Session;
class WebsiteController extends Controller
{
    public function index()
    {
        $getCourses = Courses::where('is_active','1')->where('is_delete','0')->orderBy('created_at','DESC')->limit(3)->get();
        $getBlogs = Blogs::where('is_active','1')->where('is_delete','0')->limit(3)->latest()->get();
        foreach($getBlogs as $blog){
            if($blog->description != null && $blog->description != ""){
                $blog->description = Str::limit(strip_tags($blog->description), $limit = 150, $end = '...');
            }
            else{
                $blog->description = "";
            }
            $setdate = date('j-F-Y',strtotime($blog->created_at));
            $blog->date = str_replace("-"," ",$setdate);
        }
        // return $getBlogs;
        $currencySymbol = Setting::where('perimeter','base_currency')->first()['value'];
        $usersCount = User::where('role',2)->count();
         return view('web.index',compact('getCourses','getBlogs','currencySymbol','usersCount'));
    }
    public function allCourses()
    {
        $getCourses = Courses::where('is_active','1')->where('is_delete','0')->orderBy('created_at','DESC')->paginate(10);
        $currencySymbol = Setting::where('perimeter','base_currency')->first()['value'];
        return view('web.course-list',compact('getCourses','currencySymbol'));
    }
    public function courseDetails($id){
        if(!(Auth::user())){
            $is_joined = 0;
        }
        else if(UserCourses::where('user_id',Auth::user()->id)->where('course_id',$id)->exists()){
            $is_joined = true;
        }
        else{
            $is_joined = false;
        }
        $getCourses = Courses::where('id',$id)->where('is_active','1')->where('is_delete','0')->first();
        if($getCourses != null && $getCourses != ""){
            $getSessions = Feature::where('course_id',$getCourses->id)->where('is_active','1')->where('is_delete','0')->get();
            if($getSessions != null && $getSessions !=""){
                foreach($getSessions as $session){
                    $allTopics = Category::where('session_id',$session->id)->where('is_active','1')->where('is_delete','0')->get();
                    if($allTopics != null && $allTopics != ""){
                        $session->totalTopics = $allTopics->count();
                        foreach($allTopics as $topic){
                            $session->totalQuizes = SubCategory::where('topic_id',$topic->id)->where('is_active','1')->where('is_delete','0')->count();
                        }
                    }
                }
            }
        }
        $currencySymbol = Setting::where('perimeter','base_currency')->first()['value'];
        return view('web.course-detail',compact('getSessions','getCourses','is_joined','currencySymbol'));
    }
    public function joinCourse($id){
        if(!(Auth::user())){
            return redirect()->route('web.login');
        }
        if(UserCourses::where('user_id',Auth::user()->id)->where('course_id',$id)->exists()){
            Session::flash('error', 'You have already bought this course');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
        else{
            $addCourse = new UserCourses();
            $addCourse->user_id = Auth::user()->id;
            $addCourse->course_id = $id;
            $addCourse->save();
            if($addCourse){
                Session::flash('success', 'You have successfully joined the course');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back();
            }
        }

    }
    public function courseSession($id){
        $course_id = $id;
        $course_title = Courses::where('id',$id)->where('is_active','1')->where('is_delete','0')->first()['course_title'];
        if(!(Auth::user())){
            $is_bought = 0;
        }
        else if(UserCourses::where('course_id',$course_id)->where('user_id',Auth::user()->id)->exists()){
            $is_bought = 1;
        }
        else{
            $is_bought= 0;
        }
        $getcourseSessionDetails = Feature::where('course_id',$id)->where('is_active','1')->where('is_delete','0')->get();
        foreach($getcourseSessionDetails as $allSession){
            $count = 0;
            $getSessionTopics = Category::where('session_id',$allSession->id)->where('is_active','1')->where('is_delete','0')->get();
            $allSession->topicCount = Category::where('session_id',$allSession->id)->where('is_active','1')->where('is_delete','0')->count();
            if($getSessionTopics !=null && $getSessionTopics != ""){
                $allSession->sessionTopic = $getSessionTopics;
            }
            foreach($allSession->sessionTopic as $quiz){
                $topicQuiz = SubCategory::where('topic_id',$quiz->id)->where('quiz_type','PAID')->where('is_active','1')->where('is_delete','0')->select('id','topic_id','quiz_test_type','quiz_title','duration','passing_grade','quiz_guidelines')->get();
                if($topicQuiz != null && $topicQuiz != ""){
                    $quiz->topic = $topicQuiz;
                    $count+= count($topicQuiz);
                }
                else{
                    $quiz->topic = null;
                }
            }
            $allSession->quizCount = $count;
        }
        if(SubCategory::where('course_id',$id)->where('quiz_type','MOCK')->exists()){
            $getMockQuiz  = SubCategory::where('course_id',$id)->where('quiz_type','MOCK')->get();
        }
        else{
            $getMockQuiz = [];
        }
        // return $getcourseSessionDetails;
        return view('web.course_sessions',compact('course_id','getcourseSessionDetails','course_title','is_bought','getMockQuiz'));
    }
    public function checkPaidQuiz(Request $request){
        $checkPaid = SubCategory::find($request->quiz_id);
        if($checkPaid->quiz_type == 'MOCK'){
            return response()->json(['success'=> '1', 'data'=>'MOCK']);
        }
        if($checkPaid->quiz_type == 'PAID'){
            $getTopic = Category::where('id',$checkPaid->topic_id)->first();
            $getSession = Feature::where('id',$getTopic->session_id)->first();
            $checkIfBought = UserCourses::where('course_id',$getSession->course_id)->where('user_id',Auth::user()->id)->first();
            if(isset($checkIfBought) && $checkIfBought != null){
                $checkIfExpired = \Carbon\Carbon::parse($checkIfBought->end_date);
                $currentdate = \Carbon\Carbon::now()->format('Y-m-d');
                if($checkIfExpired < $currentdate){
                    return response()->json(['error'=> '1', 'data'=>$checkIfBought]);
                }
                else{
                    return response()->json(['success'=> '1', 'data'=>$checkIfBought]);
                }
            }
            else{
                return response()->json(['error'=> '1', 'data'=>$checkIfBought]);
            }
        }
    }
    public function sessionDetail(Request $request){
       $getSession = Feature::where('id',$request->session_id)->first();
       return response()->json(['success'=> '1', 'data' => $getSession]);
    }
    public function courseDesDetail(Request $request){
        $getTopic = Category::where('id',$request->topic_id)->first();
        return response()->json(['success'=> '1', 'data' => $getTopic]);
    }
    public function sessionQuizDetail(Request $request){
        // if($request->course_id != null){
        //     $getTopicDetail = null;
        //     $checkIfAttempted = null;
        //     $getQuestionsCount = 0;
        //     if( SubCategory::where('course_id',$request->course_id)->where('quiz_type','MOCK')->where('is_active','1')->where('is_delete','0')->exists()){
        //     $getQuiz = SubCategory::where('course_id',$request->course_id)->where('quiz_type','MOCK')->where('is_active','1')->where('is_delete','0')->first();
        //     if(Files::where('quiz_id',$getQuiz->id)->where('is_active','1')->where('is_delete','0')->exists()){
        //         $getQuestionsCount = Files::where('quiz_id',$getQuiz->id)->where('is_active','1')->where('is_delete','0')->count();
        //     }
        //     if(Auth::user() &&  (UserQuizResultStats::where('user_id',Auth::user()->id)->where('quiz_id',$getQuiz->id)->exists())){
        //         $checkIfAttempted = UserQuizResultStats::where('user_id',Auth::user()->id)->where('quiz_id',$getQuiz->id)->first();
        //     }
        // }
        // else{
        //     $getQuiz= null;
        // }

        // }
        // else{
            $getTopicDetail = null;
            $checkIfAttempted = null;
            $getQuestionsCount = 0;
            if( SubCategory::where('id',$request->topic_id)->where('is_active','1')->where('is_delete','0')->exists()){
                $getQuiz = SubCategory::where('id',$request->topic_id)->where('is_active','1')->where('is_delete','0')->first();
                if(Files::where('quiz_id',$getQuiz->id)->where('is_active','1')->where('is_delete','0')->exists()){
                    $getQuestionsCount = Files::where('quiz_id',$getQuiz->id)->where('is_active','1')->where('is_delete','0')->count();
                }
                if(Auth::user() &&  (UserQuizResultStats::where('user_id',Auth::user()->id)->where('quiz_id',$getQuiz->id)->exists())){
                    $checkIfAttempted = UserQuizResultStats::where('user_id',Auth::user()->id)->where('quiz_id',$getQuiz->id)->first();
                }
                $getTopicDetail = Category::where('id',$getQuiz->topic_id)->first();
            }
            else{
                $getQuiz= null;
            }
        // }
        return response()->json(['success'=> '1', 'data' => [$getTopicDetail,$getQuiz,$getQuestionsCount,$checkIfAttempted]]);
    }
    public function allBlogs(){
        $getBlogs = Blogs::where('is_active','1')->where('is_delete','0')->orderBy('created_at','ASC')->get();
        foreach($getBlogs as $blog){
            if($blog->description != null && $blog->description != ""){
                $blog->description = Str::limit(strip_tags($blog->description), $limit = 150, $end = '...');
            }
            else{
                $blog->description = "";
            }
            $setdate = date('j-F-Y',strtotime($blog->created_at));
            $blog->date = str_replace("-"," ",$setdate);
        }
         return view('web.blog-list',compact('getBlogs'));
    }
    public function blogDetail($id){
        $blog = Blogs::find($id);
        if($blog->type_id != null && $blog->type_id !=""){
                $blogType = BlogTypes::where('id',$blog->type_id)->where('is_active','1')->where('is_delete','0')->first()['blog_type'];
                $blog->blog_type = strtoupper($blogType);
            }
            else{
                $blog->blog_type = "";
            }
            $setdate = date('j-F-Y',strtotime($blog->created_at));
            $blog->date = str_replace("-"," ",$setdate);
         return view('web.blog-details',compact('blog'));
    }
    public function checkAttemptedQuiz(Request $request){

        $checkAttemptedQuiz = UserQuizResultStats::where('user_id',Auth::user()->id)->where('quiz_id',$request->quiz_id)->first();
        if(!(isset($checkAttemptedQuiz))){
            $checkQuizQuestion = Files::where('quiz_id',$request->quiz_id)->where('is_active','1')->where('is_delete','0')->first();
            $totalseconds = "";
            if(UserQuizResults::where('quiz_id',$request->quiz_id)->where('user_id',Auth::user()->id)->exists()){
                $getLatestTime = UserQuizResults::where('quiz_id',$request->quiz_id)->where('user_id',Auth::user()->id)->latest()->first();
                if($getLatestTime->remaining_time != null && $getLatestTime->remaining_time !=""){
                    list($minutes, $seconds) = explode(":", $getLatestTime->remaining_time);
                    $totalseconds = $minutes + ($seconds / 60);
                }
            }
            $question_id = "";
            $getAllQuestions = Files::where('quiz_id',$request->quiz_id)->where('is_active','1')->where('is_delete','0')->pluck('id');
            foreach($getAllQuestions as $qustion){
                 if(UserQuizResults::where('quiz_id',$request->quiz_id)->where('user_id',Auth::user()->id)->where('question_id',$qustion)->exists()){
                    continue;
                 }
                 else{
                    $question_id = $qustion;
                    break;
                 }
            }
            if(!(isset($checkQuizQuestion))){
                return response()->json(['success'=> '1', 'data' => $checkAttemptedQuiz, 'questionExists' =>0]);
            }
            else{
                return response()->json(['success'=> '1', 'data' => $checkAttemptedQuiz,'time' => $totalseconds, 'questionExists' =>1,'question_id'=> $question_id]);
            }
        }
        else{
            return response()->json(['error'=> '1', 'data' => '']);

        }

    }
    public function getQuizQuestion(Request $request){
        // $getQuestion = DB::table('quiz_questions')
        // ->leftJoin('user_quiz_result', 'quiz_questions.id', '=', 'user_quiz_result.question_id')
        // ->where('quiz_questions.quiz_id',$request->id)->where('quiz_questions.is_active','1')->where('quiz_questions.is_delete','0')
        // ->whereNull('user_quiz_result.question_id')
        // ->select('quiz_questions.*')
        // ->get();
        $getQuestion = Files::where('quiz_id',$request->id)->where('is_active','1')->where('is_delete','0')->get();
        if($request->questionId != null){
            $getQuestion = Files::where('quiz_id',$request->id)->where('is_active','1')->where('is_delete','0')->where('id',$request->questionId)->get();
        }
        foreach($getQuestion as $question){
            // return $question->id;
            if($question->quiz_type == "multiple_choice"){
                $question->options = QuizOptions_Multiple_Choices::where('question_id',$question->id)->get();
            }
            if($question->quiz_type == "drag_drop"){
                $question->statements = QuizOptions_Drag_Drop::where('question_id',$question->id)->get();
                $options = QuizOptions_Multiple_Choices::where('question_id',$question->id)->get();
                $question->options = $options->shuffle();
            }
        }
        $getTotalQuestions = Files::where('quiz_id',$request->id)->where('is_active','1')->where('is_delete','0')->count();
        $attemptedQuestions = UserQuizResults::where('quiz_id',$request->id)->where('user_id',Auth::user()->id)->groupBy('question_id')->get();
        $attemptedQuestions = $attemptedQuestions->count();
        $totalseconds = "";
        if(UserQuizResults::where('quiz_id',$request->id)->where('user_id',Auth::user()->id)->exists()){
            $getLatestTime = UserQuizResults::where('quiz_id',$request->id)->where('user_id',Auth::user()->id)->latest()->first();
            if($getLatestTime->remaining_time != null && $getLatestTime->remaining_time !=""){
                list($minutes, $seconds) = explode(":", $getLatestTime->remaining_time);
                $totalseconds = $minutes + ($seconds / 60);
            }
        }
        return response()->json(['success'=> '1', 'data' => $getQuestion , 'questionCount' =>$attemptedQuestions, 'time'=>$totalseconds]);
    }
    public function saveUserOption(Request $req){
        $Quiz_id = $req->quiz_id;
        if($req->question_Id != null){
            if($req->draggable == '0'){
                $checkIfExists = UserQuizResults::where('question_id',$req->question_Id)->where('user_id',Auth::user()->id)->first();
                if($checkIfExists != null && $checkIfExists != "" ){
                    $checkIfExists->attempted_answer = $req->answerId;
                    if($checkIfExists->attempted_type == "Not-Attempted"){
                        $checkIfExists->attempted_type = "Attempted";
                    }
                    $checkIfExists->save();
                }
                else{
                    $saveUserAnswer = new UserQuizResults();
                    $saveUserAnswer->question_id = $req->question_Id;
                    $saveUserAnswer->attempted_answer = $req->answerId;
                    $saveUserAnswer->remaining_time = $req->timer;
                    $saveUserAnswer->quiz_id = $req->quiz_id;
                    $saveUserAnswer->user_id = Auth::user()->id;
                    $saveUserAnswer->attempted_type = $req->answerId != null ? "Attempted" : "Not-Attempted";
                    $saveUserAnswer->save();
                }
            }
            elseif($req->draggable == '1'){
                if($req->answerId != null){
                    $answers = $req->answerId;
                    foreach($answers as $dragAnswers){
                        if(count($dragAnswers) == 2){
                           if(UserQuizResults::where('quiz_id',$req->quiz_id)->where('question_id',$req->question_Id)->where('statement_id',$dragAnswers['statement_id'])->where('user_id',Auth::user()->id)->exists()){
                            continue;
                           }
                           else{
                                $saveUserAnswer = new UserQuizResults();
                                $saveUserAnswer->question_id = $req->question_Id;
                                $saveUserAnswer->statement_id = $dragAnswers['statement_id'];
                                $saveUserAnswer->remaining_time = $req->timer;
                                $saveUserAnswer->attempted_answer = $dragAnswers['answer_id'] ?? NULL;
                                $saveUserAnswer->quiz_id = $req->quiz_id;
                                $saveUserAnswer->user_id = Auth::user()->id;
                                $saveUserAnswer->attempted_type = $dragAnswers['answer_id'] != null ? "Attempted" : "Not-Attempted";
                                $saveUserAnswer->save();
                           }
                        }
                    }
                }
                else{
                    $getAllStatement = QuizOptions_Drag_Drop::where('quiz_id',$req->quiz_id)->where('question_id',$req->question_Id)->get();
                    foreach($getAllStatement as $stats){
                        $saveUserAnswer = new UserQuizResults();
                        $saveUserAnswer->question_id = $req->question_Id;
                        $saveUserAnswer->remaining_time = $req->timer;
                        $saveUserAnswer->statement_id = $stats->id;
                        $saveUserAnswer->attempted_answer = NULL;
                        $saveUserAnswer->quiz_id = $req->quiz_id;
                        $saveUserAnswer->user_id = Auth::user()->id;
                        $saveUserAnswer->attempted_type = "Not-Attempted";
                        $saveUserAnswer->save();
                    }
                }
            }
        }
        $checkIfAnswered =  UserQuizResults::where('user_id',Auth::user()->id)->where('quiz_id',$req->quiz_id)->pluck('question_id')->toArray();
        if((Files::where('quiz_id',$req->quiz_id)->whereNotIn('id',$checkIfAnswered)->where('is_delete','0')->where('is_active','1')->first()) != null){
            $getQuestion = Files::where('quiz_id',$req->quiz_id)->where('is_delete','0')->whereNotIn('id',$checkIfAnswered)->where('is_delete','0')->where('is_active','1')->first()['id'];
            return response()->json(['success'=> '1', 'data' => [(int)$Quiz_id,$getQuestion]]);
        }
        else{
             return response()->json(['error'=> '0', 'data' => []]);
        }

    }
    public function quizResult(Request $request){
        $user_id = Auth::user()->id;
        if($request->user_id != null){
            $user_id = $request->user_id;
        }
        if(UserQuizResultStats::where('quiz_id',$request->quiz_id)->where('user_id',$user_id)->exists()){
            $data = UserQuizResultStats::where('quiz_id',$request->quiz_id)->where('user_id',$user_id)->first();
        }
        else{
            $getQuizQuestions = Files::where('quiz_id',$request->quiz_id)->where('is_active','1')->where('is_delete','0')->get();
            $passingMarks = SubCategory::where('id',$request->quiz_id)->first()['passing_grade'];
            $getQuizQuestionsCount = Files::where('quiz_id',$request->quiz_id)->where('is_active','1')->where('is_delete','0')->count();
            $getQuizQuestionsTotalPoints = Files::where('quiz_id',$request->quiz_id)->where('is_active','1')->where('is_delete','0')->sum('points');
            $correct = 0;
            $skipped = 0;
            $incorrect = 0;
            $points = 0;
            $quizEachStatementPoint = 0;
            $quizEachStatementCount = null;
            foreach($getQuizQuestions as  $question){
                if($question->quiz_type == 'multiple_choice'){
                    $getUserAnswer = UserQuizResults::where('quiz_id',$request->quiz_id)->where('question_id',$question->id)->where('user_id',$user_id)->first();
                    if(!(isset($getUserAnswer))){
                        $addquestionStats = new UserQuizResults();
                        $addquestionStats->question_id = $question->id;
                        $addquestionStats->quiz_id = $request->quiz_id;
                        $addquestionStats->user_id = $user_id;
                        $addquestionStats->attempted_answer = null;
                        $addquestionStats->attempted_type = "Not-Attempted";
                        $addquestionStats->save();
                    $skipped+=1;
                    }
                    elseif($getUserAnswer->attempted_answer == null){
                        $skipped+=1;
                    }
                    elseif($getUserAnswer->attempted_answer == $question->correct_answer){
                        $points+=$question->points;
                        $correct+=1;
                    }
                    else{
                        $incorrect+=1;
                    }
                }
                if($question->quiz_type == 'drag_drop'){
                    $getUserQuizAnswer = QuizOptions_Drag_Drop::where('quiz_id',$request->quiz_id)->where('question_id',$question->id)->get();
                    $quizEachStatementCount+= QuizOptions_Drag_Drop::where('quiz_id',$request->quiz_id)->where('question_id',$question->id)->count();
                    $eachStatementPoint = $question->points / count($getUserQuizAnswer);
                    foreach($getUserQuizAnswer as $answers){
                        if(UserQuizResults::where('question_id',$question->id)->where('quiz_id',$request->quiz_id)->where('statement_id',$answers->id)->where('attempted_type','Attempted')->where('user_id',$user_id)->exists()){
                            $getUserAttemptedAnswer = UserQuizResults::where('question_id',$question->id)->where('quiz_id',$request->quiz_id)->where('statement_id',$answers->id)->where('attempted_type','Attempted')->where('user_id',$user_id)->first();
                            if($getUserAttemptedAnswer->attempted_answer == $answers->correct_answer){
                                $points+=$eachStatementPoint;
                                $correct+=1;
                            }
                            else{
                                $incorrect+=1;
                            }
                        }
                        else{
                            $skipped+=1;
                        }
                    }
                }
            }
            $getTime = SubCategory::where('id',$request->quiz_id)->first()['duration'];
            $quizTime = $getTime * 60;
            $timerComponents = explode(':', $request->timer);
            $minutes = sprintf("%02d", $timerComponents[0]);
            $seconds = $timerComponents[1];
            $carbonInterval = \Carbon\Carbon::createFromFormat('i:s', $minutes . ':' . $seconds);
            $totalSeconds = $carbonInterval->minute * 60 + $carbonInterval->second;
            $timeTakenForQuiz = $quizTime - $totalSeconds;
            $formattedTime = \Carbon\Carbon::createFromTimestamp($timeTakenForQuiz)->format('i:s');

            $totalQuestions = isset($quizEachStatementCount) ? $quizEachStatementCount : $getQuizQuestionsCount;
            $calculateResult = $points / $getQuizQuestionsTotalPoints;
            $resultPercentage = $calculateResult * 100;
            $data=[
                'passing_marks' => $passingMarks,
                'result_percentage' => round($resultPercentage),
                'correct_answers' => $correct,
                'incorrect_answers' => $incorrect,
                'skipped' => $skipped,
                'total_questions' => $totalQuestions,
                'time_taken' => $formattedTime,
                'obtained_marks' => round($points),
                'total_marks' => $getQuizQuestionsTotalPoints
            ];
            if(UserQuizResultStats::where('user_id',$user_id)->where('quiz_id',$request->quiz_id)->exists()){
                $getUserQuizStats = UserQuizResultStats::where('user_id',$user_id)->where('quiz_id',$request->quiz_id)->first();
                $getUserQuizStats->result =  round($points);
                $getUserQuizStats->update();
            }
            else{
                $addUserQuizStats = new UserQuizResultStats();
                $addUserQuizStats->result =  round($points);
                $addUserQuizStats->time_taken =  $formattedTime;
                $addUserQuizStats->passing_marks =  $passingMarks;
                $addUserQuizStats->obtained_marks =  $points;
                $addUserQuizStats->total_marks =  $getQuizQuestionsTotalPoints;
                $addUserQuizStats->correct_answers =  $correct;
                $addUserQuizStats->result_percentage =  round($resultPercentage);
                $addUserQuizStats->incorrect_answers =  $incorrect;
                $addUserQuizStats->skipped =  $skipped;
                $addUserQuizStats->total_questions =  $totalQuestions;
                $addUserQuizStats->user_id =  $user_id;
                $addUserQuizStats->quiz_id =  $request->quiz_id;
                $addUserQuizStats->save();
            }
        }
        return response()->json(['success'=> '1', 'data' => $data]);

    }
    public function completeQuizResult(Request $request){
        $user_id = Auth::user()->id;
        if($request->user_id != null){
            $user_id = $request->user_id;
        }
    $userResult = array();
       $getQuizQuestions = Files::where('quiz_id',$request->quiz_id)->where('is_delete','0')->get();
       foreach($getQuizQuestions as $question){
            if($question->quiz_type == 'multiple_choice'){
                $question->options = QuizOptions_Multiple_Choices::where('question_id',$question->id)->get();
                $question->userSelection = UserQuizResults::where('question_id',$question->id)->where('quiz_id',$request->quiz_id)->where('user_id',$user_id)->first();
                if(isset($question->userSelection) && (isset($question->userSelection->attempted_answer)) && $question->correct_answer == $question->userSelection->attempted_answer){
                    $question->correct = true;
                }
                else{
                    $question->correct = false;
                }
            }
            elseif($question->quiz_type == 'drag_drop'){
                $question->statements = QuizOptions_Drag_Drop::where('quiz_id',$request->quiz_id)->where('question_id',$question->id)->get();
                foreach($question->statements as $answers){
                    $answers->correctionOptionText = QuizOptions_Multiple_Choices::where('id',$answers->correct_answer)->first()['quiz_options'];
                    $UserSelectedOptionText = UserQuizResults::where('question_id',$question->id)->where('quiz_id',$request->quiz_id)->where('statement_id',$answers->id)->where('user_id',$user_id)->first();
                    if(isset($UserSelectedOptionText) && $UserSelectedOptionText->attempted_answer != null){
                        $answers->UserSelectedOptionText = QuizOptions_Multiple_Choices::where('id',$UserSelectedOptionText->attempted_answer)->first()['quiz_options'];
                    }
                    else{
                        $answers->UserSelectedOptionText = "";
                    }
                    array_push($userResult, $answers);
                }
                $question->options= QuizOptions_Multiple_Choices::where('question_id',$question->id)->get();
                $question->userSelection = $userResult;
            }
       }
       return response()->json(['success'=> '1', 'data' => $getQuizQuestions]);
    }
    public function contactUs(){
        $getCompanyAddress = Setting::where('perimeter','company_address')->first()['value'];
        $getCompanyEmail = Setting::where('perimeter','company_email')->first()['value'];
        $getCompanyPhone = Setting::where('perimeter','company_phone')->first()['value'];
        return view('web.contact-us',compact('getCompanyAddress','getCompanyEmail','getCompanyPhone'));
    }
    public function userProfile(){
        $userDetail = Auth::user();
        $userCourses = DB::table('user_courses')
        ->join('courses', 'user_courses.course_id', '=', 'courses.id')
        ->select('user_courses.*')
        ->where('user_courses.user_id',Auth::user()->id)
        ->where('courses.is_active','1')->where('courses.is_delete','0')
        ->get();
        $userCertificates = UserCourses::where('user_id',Auth::user()->id)->where('is_completed','1')->count();
        $points= 0;
        foreach($userCourses as $course){
            if(Courses::where('id',$course->course_id)->where('is_active','1')->where('is_delete','0')->exists()){
                $course->courseDetail = Courses::where('id',$course->course_id)->where('is_active','1')->where('is_delete','0')->first();
            $courseSessions = Feature::where('course_id',$course->course_id)->pluck('id');
            $getTopics = Category::whereIn('session_id',$courseSessions)->pluck('id');
            $course->getQuizes = SubCategory::whereIn('topic_id',$getTopics)->where('quiz_type','PAID')->pluck('id');
            //count total number of quized to make the progress bar; getquizzes can be used as array count
            $getAttemptedQuiz = UserQuizResults::whereIn('quiz_id',$course->getQuizes)->where('user_id',Auth::user()->id)->groupBy('quiz_id')->pluck('quiz_id');
            $course->userAttemptedQuizes = SubCategory::whereIn('id',$getAttemptedQuiz)->where('quiz_type','PAID')->get();
                foreach($course->userAttemptedQuizes as $quiz){
                    if(UserQuizResultStats::where('quiz_id',$quiz->id)->where('user_id',Auth::user()->id)->exists()){
                        $quiz->stats = UserQuizResultStats::where('quiz_id',$quiz->id)->where('user_id',Auth::user()->id)->first()['result'];
                        $points+=$quiz->stats;
                    }
                }
            }
        }
            $course_points = $points;
        return view('web.profile',compact('userDetail','userCourses','userCertificates','course_points'));
    }
    public function updateProfile(){
        $userDetail = Auth::user();
        return view('web.update-profile',compact('userDetail'));
    }
  public function deleteProfileImage($id)
    {
        $user = User::find($id);
        if ($user) {
            $imagePath = public_path('/assets/profile-pic/' . $user->image);
            if (file_exists($imagePath) && is_file($imagePath)) {
                unlink($imagePath);
            }
            $user->image = null;
            $user->save();

            Session::flash('success', 'User Image Deleted Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back();
        } else {
            Session::flash('error', 'An Error occurred! Please try again later');
            Session::flash('alert-class', 'alert-error');
            return redirect()->back()->with('Error', 'An Error occurred! Please try again later');
        }
    }


    public function currentPasssword(Request $req){
        $validator = Validator::make($req->all(), [
            'current_pass' => ['required', new OldPasswordMatch],
        ]);

        if ($validator->fails()) {

            return response()->json(['success'=> 0, 'message'=>"The Current Password is Incorrect"]);
        }
        else{
             return response()->json(['success'=> '1', 'message' => "Password Matched"]);
        }
    }
    public function saveProfile(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'unique:users,username,'.Auth::user()->username.',username',
            'email' => 'unique:users,email,'.Auth::user()->email.',email',
            'current_password' => ['nullable', new OldPasswordMatch],
        ]);
        if ($validator->fails()) {
            Session::flash('error', $validator->errors()->first());
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
        else{
            $user = User::find(Auth::user()->id);
            $user->full_name = $request->full_name;
            $user->username = $request->username;
            if($request->new_password !=""){
                $user->password = Hash::make($request->new_password);
            }
            if($request->has('image')){
                $files = $request->image;
                $profilePic = date("dmyHis."). '_' . $files->getClientOriginalName();
                $files->move(public_path() . '/assets/profile-pic/', $profilePic);
                $user->image = $profilePic;
            }
            $user->save();
            if($user){
                Session::flash('success', 'Profile Update Successfully!');
                Session::flash('alert-class', 'alert-success');
                return redirect()->back();
            }
        }
    }

    public function notifications()
    {
        $not = Notification::where('user_id', Auth::user()->id)->where('read_status', 'N')->get();
        foreach ($not as $item) {
            $item->read_status = 'Y';
            $item->save();
        }
        $notifications = Notification::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(12);
        return view('web.notifications', compact('notifications'));
    }

    public function addReview(Request $request)
    {
        // update product rating
        if (ProductRating::where('user_id', Auth::user()->id)->where('product_id', $request->product_id)->exists()) {
            $userInfo = ProductRating::where('user_id', Auth::user()->id)->where('product_id', $request->product_id)->first();
            if (Products::where('id', $request->product_id)->exists()) {
                $productInfo = Products::where('id', $request->product_id)->first()['user_id'];
            } else {
                $productInfo = 1;
            }
            $userInfo->update([
                'rating' => $request->rating,
                'comment' => $request->review
            ]);
            if ($userInfo) {
                $user = User::findOrFail(auth()->user()->id);
                $notification = new Notification();
                $notification->user_id = isset($productInfo) != 1 ? $productInfo : $userInfo->company_id;
                $notification->type_id = $request->product_id;
                $notification->schedule_date = \Carbon\Carbon::now();
                $notification->is_msg_app = 'Y';
                $notification->notification_type = 'Product';
                $notification->title = 'Add Rating';
                $notification->description = $user->name . ' Added Rating to your product';
                $notification->save();
//                $this->send_comm_app_notification();
                Session::flash('success', 'Review Updated Successfully');
                Session::flash('alert-class', 'alert-success');
                return response()->json(['success' => 1, 'message' => 'Review Updated Successfully']);
            } else {
                Session::flash('error', 'Review did not updated');
                Session::flash('alert-class', 'alert-danger');
                return response()->json(['error' => 1, 'message' => 'Review did not updated']);
            }
        } //create product rating
        else {
            $reviews = new ProductRating();
            $reviews->product_id = $request->product_id;
            $reviews->company_id = Products::where('id', $request->product_id)->exists() ? Products::where('id', $request->product_id)->first()['user_id'] : null;
            $reviews->rating = $request->rating;
            $reviews->user_id = Auth::user()->id;
            $reviews->comment = $request->review;
            $result = $reviews->save();
            if ($result) {

                $user = User::findOrFail(auth()->user()->id);
                $notification = new Notification();
                $notification->user_id = $reviews->company_id;
                $notification->type_id = $request->product_id;
                $notification->schedule_date = \Carbon\Carbon::now();
                $notification->is_msg_app = 'Y';
                $notification->notification_type = 'Product';
                $notification->title = 'Add Rating';
                $notification->description = $user->name . ' Added Rating to your product';
                $notification->save();
//                $this->send_comm_app_notification();

                Session::flash('success', 'Review Added Successfully');
                Session::flash('alert-class', 'alert-success');
                return response()->json(['success' => 1, 'message' => 'Review Updated Successfully']);
            } else {
                Session::flash('error', 'Review did not added');
                Session::flash('alert-class', 'alert-danger');
                return response()->json(['error' => 1, 'message' => 'Review did not updated']);
            }
        }
    }


    public function sendMessage(Request $request)
    {

        if ($request->message != '' || $request->file('files') != '') {
            $chatMessage = new ChatMessage();
            $chatMessage->message = $request->message;
            $chatMessage->user_id = Auth::user()->id;
            $chatMessage->chat_id = $request->chat_id;
            // dd($chatMessage);
            $chatMessage->save();
            $message_id = ChatMessage::orderBy('id', 'desc')->first()['id'];
            $chat = Chat::where('id', $request->chat_id)->first();
            if ($request->file('files')) {
                $files = $request->file('files');
                $chatFiles = date("dmyHis.") . gettimeofday()["usec"] . '_' . $files->getClientOriginalName();
                $files->move(public_path('/images/chat'), $chatFiles);
                $chatImage = new ChatAttachment();
                $chatImage->message_id = $message_id;
                $chatImage->attachment = $chatFiles;
                $chatImage->user_id = auth()->user()->id;
                $chatImage->save();
            }
            if ($chatMessage) {

                $notification = new Notification();
                if ($chat->seller_id == auth()->user()->id) {
                    $notification->user_id = $chat->buyer_id;
                } else {
                    $notification->user_id = $chat->seller_id;
                }
                $notification->type_id = $chat->id;
                $notification->schedule_date = \Carbon\Carbon::now();
                $notification->is_msg_app = 'Y';
                $notification->notification_type = 'Chat';
                $notification->title = 'Chat Message';
                $notification->description = 'You Received a new message';
                $notification->save();
                $this->send_comm_app_notification();
                return ['status' => true,];
            } else {
                return ['status' => false,];
            }

        }


    }

    public function contactusdetail(Request $request)
    {
        $email = $request->email;
        $text = $this->contactusemail($email,$request->text,$request->name);
        $mail = Setting::where('perimeter', 'company_email')->first()['value'];

        $noti_email = new NotificationEmail();

        $noti_email->to_email = $mail;
        // $noti_email->from_email = 'fa';
        $noti_email->email_subject = 'Contact Us Email';
        $noti_email->email_body = $text;
        $noti_email->schedule_date = \Carbon\Carbon::now();
        $noti_email->email_sent_status = 'N';
        $noti_email->campaign_entry = '0';
        $noti_email->save();
        $SendNotification = new CommunicationController();
        $SendNotification->send_comm_email();

        return response()->json(['message' => 'Thanks for the Contact Detail']);

        // }
    }
    public function contactusemail($email,$text,$name){
        return view('web.contactusEmailTemplate',compact('email','text','name'));
    }

    public function home()
    {
        // $cities = cities::all();
        // $globalCurrency = Setting::where('perimeter','global_currency')->first()['value'];
        return view('index');
    }

    public function changepassword(Request $request)
    {
        $user = auth()->user();
        $password = $user->password;


        if (Hash::check($request->password, $password)) {

            User::find(auth()->user()->id)->update(['password' => bcrypt($request->new_password)]);
            return response()->json(['success' => 1, 'message' => 'your password has been changed']);
        } else {
            return response()->json(['error' => 0, 'message' => 'Your pervious Password is not matching']);

        }

    }
    public function editprofile(Request $request)
    {
        // dd($request->all());
        // dd($request->file('files'));
        if (User::where('id', auth()->user()->id)->exists()) {
            $user = User::where('id', auth()->user()->id)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->location = $request->location;
            if ($request->file('files')) {
                $files = $request->file('files');
                $profilePic = date("dmyHis.") . gettimeofday()["usec"] . '_' . $files->getClientOriginalName();
                $files->move(public_path() . '/images/profile-pic/', $profilePic);
                $user->image = $profilePic;
            }
            $user->save();
            // return view('web.profille');

            return response()->json(['success' => 'Your Profile have been edited successfully ']);
        } else {
            // return view('web.profile');

            return response()->json(['error' => 'Some things went wrong ']);
        }
    }

    public function phoneCall(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'seller_id' => 'required|not_in:0',
        ]);
        if ($validator->fails()) {
            return $this->sendError(false, $validator->errors()->first());
        }
        $phoneCall = new PhoneCall();
        $phoneCall->seller_id = $request->seller_id;
        $phoneCall->buyer_id = auth()->user()->id;
        $phoneCall->save();
        $count = PhoneCall::where('seller_id', $request->seller_id)->count();
        // return $this->sendResponse(1, 'success', ['callCount' => $count]);
        return response()->json(['success' => 'success']);
    }

    public function switchrole()
    {
        if (auth()->user()->role == 5) {
            $user = User::where('id', auth()->user()->id)->first();
            $user->role = 4;
            $user->save();
            return redirect(url('/buyer'));
        } elseif (auth()->user()->role == 4) {
            $user = User::where('id', auth()->user()->id)->first();
            $user->role = 5;
            $user->save();
            return redirect(url('/tracking-list/company-profile/' . auth()->user()->id));


        }
    }



    public function getunits(Request $request)
    {
        // $products = Products::where('sub_category')

// $products['sub_cat'] = Products::where('id',$products->id)->first()['sub_category'];
        if (SubCategory::where('sub_category', $request->sub_category)->exists()) {
            $products['sub_cat_id'] = SubCategory::where('sub_category', $request->sub_category)->first()['id'];
        } else {
            $products['sub_cat_id'] = '';
        }

        $products['sub_cat_unitId'] = SubCategoryUnit::where('sub_category_id', $products['sub_cat_id'])->pluck('unit_id');
        $products['all_unit'] = Unit::whereIn('id', $products['sub_cat_unitId'])->where('status', 'Active')->get();
        return response()->json(['unit_all' => $products['all_unit']]);

    }

    public function verifynum(Request $request)
    {
        if (User::where('phone', 'like', '%' . $request->phone . '%')->exists()) {
            return response()->json(['success' => 1]);
        } else {
            return response()->json(['error' => 0]);
        }
    }

    public function resetpass(Request $request)
    {
        //    dd($request->all());
        if (User::where('phone', 'like', '%' . $request->number . '%')->exists()) {
            $user = User::where('phone', 'like', '%' . $request->number . '%')->first();
            $user->password = $request->password;
            $user->save();
            return response()->json(['success' => 1]);
        } else {
            return response()->json(['error' => 'Password can/t change please try again']);
        }

    }

    public function privacypolicys()
    {
        return view('web.privacy-policy');
    }

    public function userProfileView($id)
    {
        $userDetail = Auth::user();
        $userCourses = UserCourses::where('user_id',Auth::user()->id)->get();
        $userCertificates = UserCourses::where('user_id',Auth::user()->id)->where('is_completed','1')->count();
        $courses = Courses::find($id);
        $courseSessions = Feature::where('course_id',$id)->pluck('id');
        $getTopics = Category::whereIn('session_id',$courseSessions)->pluck('id');
        $getQuizes = SubCategory::whereIn('topic_id',$getTopics)->pluck('id');
        //count total number of quized to make the progress bar; getquizzes can be used as array count
        $getAttemptedQuiz = UserQuizResults::whereIn('quiz_id',$getQuizes)->where('user_id',Auth::user()->id)->groupBy('quiz_id')->pluck('quiz_id');
        $userAttemptedQuizes = SubCategory::whereIn('id',$getAttemptedQuiz)->where('quiz_type','PAID')->get();
        $points = 0;
            foreach($userAttemptedQuizes as $quiz){
                if(UserQuizResultStats::where('quiz_id',$quiz->id)->where('user_id',Auth::user()->id)->exists()){
                    $quiz->stats = UserQuizResultStats::where('quiz_id',$quiz->id)->where('user_id',Auth::user()->id)->first();
                    $points+= UserQuizResultStats::where('quiz_id',$quiz->id)->where('user_id',Auth::user()->id)->first()['result'];
                }
            }
            $course_points = UserQuizResultStats::where('user_id',Auth::user()->id)->sum('result');
            // return $userAttemptedQuizes;
        return view('web.profile-view',compact('userDetail','courses','userAttemptedQuizes','userCourses','userCertificates','course_points'));
    }

    public function getcachedata(Request $request)
    {

        cache::put('productName', $request->product, 600);
        cache::put('quantity', $request->quantity, 600);
        if ($request->unit_id) {
            $unit = unit::where('id', $request->unit_id)->first()['unit'];
            cache::put('unit', $unit, 600);
        }
        cache::put('unit_id', $request->unit_id, 600);

        cache::put('category_id', $request->category_id, 600);
        if ($request->category_id) {
            $category = ProductCategory::where('id', $request->category_id)->first()['category'];
            cache::put('category', $category, 600);
        }
        if($request->lead_type=='urgent')
        {

            $lead_type = Leads::where('is_urgent','Y')->first()['is_urgent'];
            cache::put('is_urgent', $lead_type ,600);
        }

        return view('web.login');
    }

    public function send_comm_app_notification()
    {
        $number_noti = 1000;
        /*api_key available in:
        Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/
        $server_key = 'AAAAB8kX-H4:APA91bEI_W9gLV043qjuvD8SrRw5Tmb1TKIUiaHrYf_PUF5LD7qt1yF6x19vncMdTdelKsNbLnLO1OTVH3illOOgiXE82ugDxeU_IZTEugtslHrqM6GtxSacNl-5QO21wtrIUAvophZ6';
        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';
        $currentDate = date('Y-m-d H:i:s');

        $noti = Notification::where(('sent_status'), '=', "N")->where("schedule_date", "<=", $currentDate)->where("is_notification_required", "Y")->orderBy('schedule_date', 'DESC')->limit($number_noti)->get();

        if (count($noti) > 0) {
            foreach ($noti as $element) {
                #send App notification
                if (($element->is_msg_app) == 'Y') {
                    $title = $element->title;
                    $description = $element->description;
                    $user_id = $element->user_id;
                    if ($element->device_type == 'all') {

                        $q = UserDevice::where('user_id', $user_id)->where('status', '=', 'A')->get();
                    } else {

                        $q = UserDevice::where('user_id', $user_id)->where('status', '=', 'A')->where('platform', $element->device_type)->get();
                    }
                    if (!empty($q) && count($q) > 0) {
                        foreach ($q as $row) {
                            // dd($row);

                            if (is_null($row->token)) {
                                DB::table('notification')
                                    ->where('id', '=', $element->id)
                                    ->update(array('message_error' => "Device token is null"));
                                continue;
                            }
                            $key = $row->token;
                            $headers = array(
                                'Authorization:key=' . $server_key,
                                'Content-Type:application/json'
                            );
                            $fields = array(
                                'to' => $key,
                                'notification' => array('title' => $title, 'body' => $description, 'sound' => 1, 'vibrate' => 1),
                                'data' => array('notification_type' => $element->notification_type, 'title' => $title, 'body' => $description)
                            );

                            $payload = json_encode($fields);
                            $curl_session = curl_init();
                            curl_setopt($curl_session, CURLOPT_URL, $url);
                            curl_setopt($curl_session, CURLOPT_POST, true);
                            curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                            curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);
                            $curlResult = curl_exec($curl_session);

                            if ($curlResult === FALSE) {
                                die('FCM Send Error: ' . curl_error($curl_session));
                            }
                            curl_close($curl_session);


                            $res = json_decode($curlResult, true);

                            //   dd($res);
                            if ($res["failure"]) {
                                $array = $res['results'];
                                $error = $array[0]['error'];
                                DB::table('notification')
                                    ->where('id', '=', $element->id)
                                    ->update(array('message_error' => $error));
                            } else {
                                DB::table('notification')
                                    ->where('id', '=', $element->id)
                                    ->update(array('message_error' => '', 'sent_status' => 'Y', 'app_sent_date' => $currentDate));
                            }
                        }
                    }
                }
            }
            //return response(['success' => 1, 'message' => 'Sending all notifications', 'result' =>true], 200);
            // return true;
        }
    }
    // public function pdfToImage()
    // {
    //     $pdfPath = public_path('assets/subcategory/300V POWER 0W-8 TDS.pdf');
    //     $imagePath = public_path('assets/pdfviewer/first_page.png');
    //     $pdf = new TCPDF();
    //     $pdf->setSourceFile($pdfPath);
    //     $pdf->AddPage();
    //     $page = $pdf->importPage(1);
    //     $pdf->useTemplate($page);
    //     $pdf->Image($imagePath, 'PNG');
    //     $pdf->close();
    //     return asset('assets/pdfviewer/first_page.png');
    // }

    public function pdfToImage()
    {
        $pdfPath = public_path('assets/subcategory/300V POWER 0W-8 TDS.pdf');
        $imagePath = public_path('assets/pdfviewer/first_page');
    
        // Ensure the output directory exists
        if (!file_exists($imagePath)) {
            mkdir($imagePath, 0777, true);
        }
    
        // Use pathinfo with $pdfPath
        $pathInfo = pathinfo($pdfPath);
    
        $pdf = new Pdf($pdfPath);
        $pdf->setOutputFormat('png');
        $pdf->setPage(1);
    
        // Save the image directly without saving to Storage
        $pdf->saveImage($imagePath . '/first_page-1.png');
    
        // Optionally, you can return a response or redirect
        $imageName = 'first_page-1.png';
        $filePath = $imagePath . '/' . $imageName;
    
        return response()->download($filePath, $imageName)->deleteFileAfterSend(true);
    }
}
