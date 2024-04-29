<link rel="shortcut icon" href="{{ asset('assets/images/fav_icon.png') }}">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> -->
    <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script> -->
    <!-- <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script> -->
@extends('web.layouts.master')
@section('content')
<style>
    .head-icon_vt i{
        border-color:#ffffff !important;
    }
</style>
@include('web.layouts.partials.header')
<!-- Start Page Content here -->
<div class="content">
    <!-- start page title -->
@section('title', '- Quiz');

    <style>

.tab {
    display: none
}

.step {
    height: 20px;
    width: 20px;
    margin-bottom: 50px;
    /* margin: 30px 0px; */
    background-color: #ffffff;
    border: 4px solid #EBF0F5;
    /* border: none; */
    border-radius: 50%;
    display: block;
    opacity: 1
}
.step::after{
    content: "";
    width: 3px;
    height: 52px;
    display: block;
    background-color: #EBF0F5;
    text-align: center;
    margin: 0 auto;
    margin-top: 15px;
}
.step:last-child::after{
    display: none;
}

nav ul li:last-child::after { display: none; }

.step.active {
    opacity: 2
}

.step.finish {
    background-color: #FCBC45;
}


    </style>
<body>
    @if(Auth::check())

        <script>{{ 'var logged = true;' }}</script>

    @else
        <script>{{ 'var logged = false;' }}</script>

    @endif

    <div class="container-fluid px-0">
        <div class="quiz-main-page-content">
            <div class="quiz-wrapper">
                <div class="quiz-left-section mobile-1">
                    <div class="left-header overflow-hidden">
                    <h1 class="float-start">{{$course_title}}</h1>
                            @if($is_bought == 1)

                            @else
                                <div class="lock-icon-widget head-icon_vt float-end">
                                    <a href="#"><i class="fontello icon-lock text-white "></i></a>
                                </div>
                            @endif
                    </div>
                    <div class="left-accordian" id="session_area">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsea" aria-expanded="true" aria-controls="collapseOne">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="content-circle">
                                                <i class="fontello icon-book-open "></i>
                                            </div>
                                            <div class="content-intro">
                                                <h4 id="sessionData">{{$course_title}}</h4>
                                                <p>SERU MOCK TEST</p>
                                                <div class="d-flex mt-2">
                                                    <div class="topics_vt d-flex topic-first-vt">
                                                        <i class="fontello icon-book-alt"></i>
                                                        <p>1 Topic</p>
                                                    </div>
                                                    <div class="topics_vt d-flex">
                                                        <i class="fontello icon-lightbulb"></i>
                                                        <p>{{count($getMockQuiz)}} Quiz</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapsea" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <form id="regForm">
                                            <div class="all-steps" id="all-steps">
                                                <div class="d-flex steps-content">
                                                    <span class="step"></span>
                                                    <div class="pl-10 w-100">
                                                        <div class="d-flex justify-content-between lock-icon-widget">
                                                            <a href="#"><p>Sample Quiz</p></a>
                                                        </div>
                                                        @foreach($getMockQuiz as $key00=> $freeQuiz)
                                                            @if($freeQuiz->quiz_test_type == "multiple")
                                                                <a href="#" onclick="showQuestion({{$freeQuiz->id}})" id="testquiz">
                                                                    <div class="d-flex align-items-center quiz-topics-content">
                                                                        <i class="fontello icon-quiz-check"></i>
                                                                        <p>Multiple Choice for SERU MOCK TEST</p>
                                                                    </div>
                                                                </a>
                                                            @else
                                                                <a href="#" onclick="showQuestion({{$freeQuiz->id}})" id="testquiz">
                                                                    <div class="d-flex align-items-center quiz-topics-content">
                                                                        <i class="fontello icon-quiz-check"></i>
                                                                        <p>Drag & Drop for SERU MOCK TEST</p>
                                                                    </div>
                                                                </a>
                                                            @endif
                                                        @endforeach

                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                            @foreach($getcourseSessionDetails as $key=>$session)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapseOne">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="content-circle">
                                                <i class="fontello icon-book-open "></i>
                                            </div>
                                            <div class="content-intro">
                                                <h4 onclick="updateSessionData({{$session->id}})" id="sessionData">{{$session->session_title}}</h4>
                                                <p onclick="updateSessionData({{$session->id}})">{{$session->session_name}}</p>
                                                <div class="d-flex mt-2">
                                                    <div class="topics_vt d-flex topic-first-vt">
                                                        <i class="fontello icon-book-alt"></i>
                                                        <p>{{$session->topicCount}} Topic</p>
                                                    </div>
                                                    <div class="topics_vt d-flex">
                                                        <i class="fontello icon-lightbulb"></i>
                                                        <p>{{$session->quizCount > 0 ? $session->quizCount : 0}} Quiz</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{$key}}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <form id="regForm">
                                            <div class="all-steps" id="all-steps">
                                                @foreach($session->sessionTopic as $key1=> $topic)
                                                <div class="d-flex steps-content">
                                                    <span class="step"></span>
                                                    <div class="pl-10 w-100">
                                                        <div class="d-flex justify-content-between lock-icon-widget">
                                                            <a  href="#" onclick="showDiv({{$topic->id}})"><p onclick="getTopicHeading(this)">Topic{{$key1 + 1}}. {{$topic->topic_name}}</p></a>
                                                        </div>
                                                        @foreach($topic->topic as $key11=> $quiz_statement)
                                                            @if($quiz_statement->quiz_test_type == 'multiple')
                                                            <a href="#" onclick="showQuestion({{$quiz_statement->id}})" id="quizContent">
                                                                <div class="d-flex align-items-center quiz-topics-content">
                                                                    <i class="fontello icon-Multiple1"></i>
                                                                    <p>Multiple Choice for {{$topic->topic_name}}</p>
                                                                </div>
                                                            </a>
                                                            @else
                                                            <a href="#" onclick="showQuestion({{$quiz_statement->id}})">
                                                                <div class="d-flex align-items-center quiz-topics-content" id="quizContent">
                                                                    <i class="fontello icon-drag-drop"></i>
                                                                    <p>Drag & Drop for {{$topic->topic_name}}</p>
                                                                </div>
                                                            </a>
                                                            @endif
                                                        @endforeach

                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="quiz-right-section" >
                    <div class="alert-holder">
                        <div class="alert alert-danger" id="alertID" style="display:none; width:320px;">
                            <a href="#" class="close" data-dismiss="alert"
                                aria-label="close"></a> You have Already Attempted this Quiz
                        </div>
                    </div>
                    <div class="mt-2" style="display: none;" id="loginCheck">
                        <div class="">
                            <div class="quiz-detail">
                                <h1>{{$course_title}}<span id="course_title_quizs" style="font-weight:400;"></span></h1>
                                <h2 id="quiz_titles"></h2>
                                <div class="before-login-wrappe">
                                    <img src="{{asset('assets/images/lock.png')}}" alt="">
                                    <p>You must sign in or sign up to start the quiz.</p>
                                    <div class="pt-4">
                                        <button type="button" class="btn btn-warning custom-btn" onclick="window.location.href='{{route('web.login')}}'">Login</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2" style="display: none;" id="courseBuy">
                        <div class="">
                            <div class="quiz-detail">
                                <!-- <h1>{{$course_title}}<span id="course_title_quizs" style="font-weight:400;"></span></h1> -->
                                <h2 id="quiz_titles"></h2>
                                <div class="before-login-wrappe">
                                    <img src="{{asset('assets/images/coursebuy.png')}}" alt="">
                                    <p>Please join the course to attempt quiz.</p>
                                    <div class="pt-4">
                                        <button type="button" class="btn btn-warning custom-btn" onclick="window.location.href='{{route('course.detail',['id'=>$course_id])}}'">Join Course</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2" style="display: none;" id="noDataImage">
                        <div class="">
                            <div class="quiz-detail">
                                <h2 id="quiz_titles"></h2>
                                <div class="before-login-wrappe">
                                    <img src="{{asset('assets/images/nodata.jpeg')}}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="mt-2" id="topicSection">
                        <div class="quiz-detail" id="welcomeDiv1">
                            <h1>{{$course_title}}<span id="course_title" style="font-weight:400;"></span></h1>
                            <h2 id="session_name"></h2>
                            <div id="session_description"></div>
                        </div>
                        <div class="quiz-detail" style="display: none;" id="welcomeDiv">
                            <h1>{{$course_title}}<span id="course_title_topic" style="font-weight:400;"></span></h1>
                            <h1 id="course_topic_no">topic 1</h1>
                            <h2 id="topic_name">Licensing Requirements</h2>
                            <!-- <p>To get a licence:</p> -->
                            <div class="quiz-detail-disc">
                                    <p id="topic_description">You must be aged 21 or older when you apply for your licence; there is no upper age limit</p>
                            </div>
                        </div>
                        <div class="quiz-detail" style="display: none;" id="welcomeDiv2">
                            <h1>{{$course_title}}<span id="course_title_quiz" style="font-weight:400;"></span></h1>
                            <h2 id="quiz_title"></h2>
                            <!-- <p>To get a licence:</p> -->
                            <div class="mb-3" id="welcome123">
                                <div class="d-flex">
                                    <span>Duration :</span>
                                    <p id="duration">45 Minutes</p>
                                </div>
                                <div class="d-flex">
                                    <span>Questions :</span>
                                    <p id="quiz_questions"></p>
                                </div>
                                <div class="d-flex">
                                    <span>Passing grade :</span>
                                    <p id="passsing_grade"></p>
                                </div>
                            </div>
                            <div id="welcomeabc">
                            <span>Quiz Attempt Guidelines</span>
                            <p id="guidelines">You will get 45 minutes to complete the SERU mock test, you will have a total of 37 questions, which are split up into 17 Multiple choice questions, and 18 complete sentences dragging the correct words into the blank spaces. To pass the SERU assessment, you need to get a minimum of 60%. so let's start the online Mock test from section 1.</p>
                            <div class="pt-4">
                                <button type="button" class="btn btn-warning custom-btn" id="myButton" onclick="checkAuth(this.value)">Start Quiz</button>
                                <button type="button" class="btn btn-warning custom-btn" id="viewResult" style="display:none">View Results</button>
                            </div>
                            </div>
                        </div>
                        <div id="after_quiz_start">
                            <div class="test-details" id="test-timings">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="text-center">
                                            <span id="question_timer_update"></span>/<span id="question_timer">/</span>
                                            <p>Question</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <div class="text-center">
                                            <span id="timer"></span>
                                            <p>Time Remaining</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="result-section_vt" style="display:none;" id="result-section">
                                <div class="result-area_vt">
                                    <div class="result-widget">
                                        <h1>Your Result</h1>
                                        <p id="quiz_passed"></p>
                                        <div role="progressbar1" id="progressbar"
                                            aria-valuenow="89"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            style="--value:20">
                                        </div>
                                    </div>
                                    <div class="test-detail-area">
                                        <div class="test-detail-widget">
                                            <h4>Time Spend :</h4>
                                            <p id="time_spent"></p>
                                        </div>
                                        <div class="test-detail-widget">
                                            <h4>Points :</h4>
                                            <p id="points">29/35</p>
                                        </div>
                                        <div class="test-detail-widget">
                                            <h4>Total Questions :</h4>
                                            <p id="total_questions">35</p>
                                        </div>
                                        <div class="test-detail-widget">
                                            <h4>Correct :</h4>
                                            <p id="correct_answers">35</p>
                                        </div>
                                        <div class="test-detail-widget">
                                            <h4>Wrong :</h4>
                                            <p id="incorrect_answers">5</p>
                                        </div>
                                        <div class="test-detail-widget">
                                            <h4>Skipped :</h4>
                                            <p id="skipped_answers">1</p>
                                        </div>
                                    </div>

                                </div>
                                <div class="d-flex mt-3 justify-content-center mb-3">
                                    <button type="button" class="btn btn-warning custom-btn me-3" onclick="loadCompleteQuiz()">View Questions</button>
                                </div>
                            </div>
                            <div id="main_question_page">
                                <div class="mt-2 licensed_vt">
                                    <p Id="quiz_question"></p>
                                </div>
                                    <!-- drag and drop statements -->
                                <div class="test-section" id="test-section">
                                </div>
                                    <!-- incorrect Drop Box -->
                                <div class="drop-section my-3" style="display: none" id="drop-section">
                                    <p class="inccorrect_words">Add Incorrect Words Here</p>
                                </div>
                                    <!-- draggable Elements -->
                                <div id="dragable_options"></div>
                                <div class="d-flex justify-content-end mb-3" id="next-submit-button">
                                    <button type="button" class="btn btn-warning custom-btn" id="nextButton" value="" onclick="saveAnswer()" >Next</button>
                                    <button type="button" class="btn btn-warning custom-btn" id="SubmitButton" value="" style="display:none" onclick="submitQuiz()">Submit</button>
                                </div>
                            </div>
                            <div id="user-result-display"></div>
                        </div>
                    </div>
                    <div class="mt-2" style="display: none;" id="nodata">
                        <div class="">
                            <div class="quiz-detail">
                                <div class="before-login-wrappe">
                                    <img src="{{asset('assets/images/emptyBlogs.jpeg')}}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script> -->


<script type="text/javascript">
    var topic_name;
    var total_questions = 0;
    var attempting_answer = 0;
    let quiz_duration = null;
    var draggable = 0;
    var quiz_title;
    var timer;
    var quiz_id;
    var AnswerId = null;
    var timer_set = null;
    var Question_Id = null;

    window.onload = function() {
        document.getElementById("test-timings").style.display = "none";
        document.getElementById("nextButton").style.display = "none";
        var CheckMock = {!! count($getMockQuiz) > 0 ? $getMockQuiz : 'null' !!}
        if(CheckMock !== null){
            var p =document.getElementById('testquiz');
            p.click();
        }
        else{
            var p =document.getElementById('quizContent');
            p.click();
        }

      };
      // function for the quiz questions
      function checkAuth(value){
        var is_Bought = {!! $is_bought !!};

       // value is the quiz id
       if(logged == false){
        document.getElementById('loginCheck').style.display = "block";
        document.getElementById('topicSection').style.display = "none";
       }
    //    else if( is_Bought === 0){
    //         document.getElementById('courseBuy').style.display = "block";
    //         document.getElementById('topicSection').style.display = "none";
    //    }
       else{
        $.ajax({
                url: "{{route('check.paid.quiz')}}",
                type: "POST",
                data: {
                    quiz_id: value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.error) {
                        document.getElementById('courseBuy').style.display = "block";
                        document.getElementById('topicSection').style.display = "none"
                    }
                    else{
                        $('#timer').empty();
                        timer_set = null;
                        quiz_id = value;
                        $.ajax({
                            url: "{{route('check.attempted.quiz')}}",
                            type: "POST",
                            data: {
                                quiz_id: value,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {

                                    if(response.questionExists == 0){
                                    document.getElementById('nodata').style.display = "block";
                                    document.getElementById('topicSection').style.display = "none";
                                    }
                                    else{
                                        if(response.question_id !== ""){
                                           Question_Id =  response.question_id;
                                        }
                                        var myDiv = document.getElementById("accordionExample");
                                    myDiv.style.pointerEvents = "none";
                                        document.getElementById('nodata').style.display = "none";
                                        document.getElementById('loginCheck').style.display = "none";
                                        document.getElementById('topicSection').style.display = "block";

                                        if( response.time !== ""){
                                            document.getElementById("timer").textContent = "";
                                            var Quiz_timer = document.getElementById("timer");
                                            console.log(Quiz_timer.textContent);
                                            $('#timer').empty();

                                            quiz_duration = (response.time) * 60 * 1000;
                                            const startTime = new Date().getTime();
                                            const endTime = startTime + quiz_duration;

                                            const timer = setInterval(() => {
                                                const currentTime = new Date().getTime();
                                                const remainingTime = endTime - currentTime;


                                                if (remainingTime <= 0) {
                                                    clearInterval(timer);
                                                    console.log("Time's up!");
                                                    var button = document.getElementById("SubmitButton");
                                                    button.dispatchEvent(new Event('click'));
                                                        return;
                                                    // alert("Time's up!");
                                                }

                                                const minutes = Math.floor(remainingTime / 60000);
                                                const seconds = Math.floor((remainingTime % 60000) / 1000);

                                                Quiz_timer.textContent=  `${minutes}:${seconds.toString().padStart(2, '0')}`;
                                                timer_set = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                                            }, 1000);
                                        }else{
                                            var Quiz_timer = document.getElementById("timer");
                                            $('#timer').empty();
                                            quiz_duration = quiz_duration * 60 * 1000;
                                            const startTime = new Date().getTime();
                                            const endTime = startTime + quiz_duration;
                                            clearInterval(timer);
                                            timer = setInterval(() => {
                                                const currentTime = new Date().getTime();
                                                const remainingTime = endTime - currentTime;

                                                if (remainingTime <= 0) {
                                                    clearInterval(timer);
                                                    console.log("Time's up!");
                                                    var button = document.getElementById("SubmitButton");
                                                    button.dispatchEvent(new Event('click'));
                                                        return;
                                                    // alert("Time's up!");
                                                }

                                                const minutes = Math.floor(remainingTime / 60000);
                                                const seconds = Math.floor((remainingTime % 60000) / 1000);

                                                Quiz_timer.textContent=  `${minutes}:${seconds.toString().padStart(2, '0')}`;
                                                timer_set = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                                            }, 1000);


                                        }

                                        getQuizQuestion(quiz_id,null);

                                        window.addEventListener('beforeunload', function(event) {
                                        alert("you're trying to relaoad the page")
                                        event.preventDefault();
                                        // Chrome requires the event.returnValue to be set
                                        event.returnValue = '';
                                        });
                                    }
                                }
                                else{
                                    document.getElementById("alertID").style.display = "block";
                                    setTimeout(function(){
                                    $('#alertID').hide('slow')
                                    }, 2000);
                                }
                            }
                        });
                    }
                }
            })

       }
    }
    function getQuizQuestion(quiz_id,value){
        var selected_option = null ;
        $.ajax({
            url: "{{route('get.quiz.question')}}",
            type: "POST",
            data: {
                id: quiz_id,
                questionId: Question_Id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    document.getElementById('user-result-display').style.display = "none";
                    document.getElementById('after_quiz_start').style.display = "block";
                    document.getElementById('test-timings').style.display = "block";
                    document.getElementById('main_question_page').style.display = "block";
                    document.getElementById('welcomeDiv1').style.display = "none";
                    document.getElementById('welcomeDiv').style.display = "none";
                    document.getElementById('welcome123').style.display = "none";
                    document.getElementById('welcomeabc').style.display = "none";
                    document.getElementById('nodata').style.display = "none";

                    document.getElementById('welcomeDiv2').style.display = "block";

                    attempting_answer = response.questionCount;
                    document.getElementById("question_timer_update").innerHTML = attempting_answer + 1;



                    var title = document.getElementById("quiz_question");
                    title.innerHTML =  response.data[0].quiz_query;
                    const container = document.getElementById('test-section');
                    container.innerHTML = "";
                    if(response.data !== null){
                            document.getElementById('nextButton').style.display = "block";
                            var button = document.getElementById("SubmitButton")
                            button.style.display = 'none'
                        }
                    if(response.data[0].quiz_type == "multiple_choice") {
                        document.getElementById('drop-section').style.display= 'none';

                        var divsToHide = document.getElementsByClassName("draggable_vt"); //divsToHide is an array
                        for(var i = 0; i < divsToHide.length; i++){
                            divsToHide[i].style.visibility = "hidden"; // or
                            divsToHide[i].style.display = "none"; // depending on what you're doing
                        }
                        draggable = 0;
                        for (let i = 0; i < response.data[0].options.length; i++) {

                            const radioDiv = document.createElement('div');
                            radioDiv.classList.add('choice-widgit');

                                const radio = document.createElement('input');
                                radio.type = 'radio';
                                radio.name = 'option';
                                radio.value = response.data[0].options[i].id;

                                const label = document.createElement('label');
                                label.innerHTML = response.data[0].options[i].quiz_options;
                                label.classList.add('radio-container');

                                const span = document.createElement('span');
                                span.classList.add('checkmark')

                                label.appendChild(radio);
                                radioDiv.appendChild(label);
                                label.appendChild(span);

                                container.appendChild(radioDiv);
                                Question_Id = response.data[0].id;

                                radioDiv.addEventListener('change', function() {
                                selected_option = radioDiv.querySelector('input[name="option"]:checked').value;
                                console.log(selected_option);

                                AnswerId = selected_option;
                            });
                            if(selected_option == null){
                                AnswerId = null
                            }

                        }
                    }
                    else{
                        Question_Id = response.data[0].id;
                        for (let i = 0; i < response.data[0].statements.length; i++) {
                            draggable = 1;
                            const statementMainDiv = document.createElement('div');
                            statementMainDiv.classList.add('licensed_vt');

                            const statementDiv = document.createElement('div');
                            statementDiv.id = response.data[0].statements[i].id;
                            // statementDiv.disabled = true;
                            statementDiv.classList.add('square-section');

                            const statement = document.createElement('p')
                            statement.classList.add('droppable');
                            statement.id = response.data[0].statements[i].id
                            statement.innerHTML = response.data[0].statements[i].statement;

                            const replacedContent = statement.innerHTML.replace("##", statementDiv.outerHTML);
                            statement.innerHTML = replacedContent;

                             $(statement).droppable({
                                helper: 'clone',
                                revert: 'invalid'
                            });

                            statementMainDiv.appendChild(statement);
                            // statementMainDiv.appendChild(statementDiv);

                            container.appendChild(statementMainDiv);
                        }
                        const optionsDiv = document.createElement("div");
                        optionsDiv.classList.add('drag-content_vt');
                        document.getElementById('drop-section').style.display= 'block';
                        for(let j = 0; j < response.data[0].options.length; j++){

                            const optionMainDiv = document.createElement('div');
                            optionMainDiv.id = response.data[0].options[j].id;
                            optionMainDiv.classList.add('move-this','ui-widget-content','draggable_vt','ui-draggable-handle');

                            const option = document.createElement('p')
                            option.classList.add('p_tagss');
                            option.innerHTML = response.data[0].options[j].quiz_options;

                            optionMainDiv.appendChild(option);
                            optionsDiv.appendChild(optionMainDiv);

                        }
                        const optionContainer = document.getElementById('dragable_options');
                        optionContainer.innerHTML = "";
                        optionContainer.appendChild(optionsDiv);


                    }
                }

            }
        });
    }


    function saveAnswer(){
        $.ajax({
            url: "{{route('save.user.option')}}",
            type: "POST",
            data: {
                question_Id: Question_Id,
                answerId : AnswerId,
                timer: timer_set,
                draggable : draggable,
                quiz_id: quiz_id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    dragDrop.length = 0;
                    Question_Id = response.data[1];
                    getQuizQuestion(response.data[0],response.data[1])
                }
                else{
                    var button = document.getElementById("SubmitButton")
                    button.style.display = 'block'
                    button.value = quiz_id;
                    // button.addEventListener("click", function() {
                    //     submitQuiz();
                    // });

                    // add button to the page
                    document.getElementById('nextButton').style.display = "none";
                    // document.getElementById('next-submit-button').appendChild(button);
                }
            }
        })
    }
    function submitQuiz(){
        $('#timer').empty();
        Question_Id = null;
        attempting_answer = 0;
        $('#user-result-display').empty();
        document.getElementById('result-section').style.display = "none";
        document.getElementById('test-timings').style.display = "none";
        $.ajax({
            url: "{{route('show.results')}}",
            type: "POST",
            data: {
                quiz_id: quiz_id,
                timer: timer_set,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // $('#result-section').empty();
                if (response.success) {
                    var myDiv = document.getElementById("accordionExample");
                           myDiv.style.pointerEvents = "auto";
                    // alert(response.data.result_percentage)
                    document.getElementById('test-timings').style.display = "none";
                    document.getElementById('nodata').style.display = "none";
                    document.getElementById('result-section').style.display = "block";
                    document.getElementById("main_question_page").style.display = "none";
                    var progressbar =  document.getElementById('progressbar');
                    var percantage = response.data.result_percentage;
                    progressbar.style.setProperty('--value', percantage);
                    if(response.data.result_percentage < response.data.passing_marks){
                        progressbar.setAttribute('role','progressbar123')
                    }
                    document.getElementById('points').textContent = response.data.obtained_marks + '/' + response.data.total_marks;
                    document.getElementById('total_questions').textContent = response.data.total_questions;
                    document.getElementById('correct_answers').textContent = response.data.correct_answers;
                    document.getElementById('incorrect_answers').textContent = response.data.incorrect_answers;
                    document.getElementById('skipped_answers').textContent = response.data.skipped;
                    document.getElementById('time_spent').textContent = response.data.time_taken;
                    document.getElementById('quiz_passed').textContent = response.data.result_percentage >= response.data.passing_marks ? "You have successfully passed your Quiz" : "You have failed the quiz"
                    //the code for result goes here........
                }
            }
        })

    }
    function loadCompleteQuiz(){
        $('#user-result-display').empty();
        $.ajax({
            url: "{{route('load.complete.quiz')}}",
            type: "POST",
            data: {
                quiz_id: quiz_id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success){
                    $('#user-result-display').empty();
                    document.getElementById('user-result-display').style.display = "block";
                    const container = document.getElementById('user-result-display');
                    container.innerHTML = "";
                    for(i=0; i<response.data.length; i++){

                        var quiz_title = document.createElement('p');
                        // quiz_title.classList.add('question-title_vt');

                        var quiz_subtitle = document.createElement('span');
                        quiz_subtitle.classList.add('question-title_vt');
                                    quiz_title.appendChild(quiz_subtitle);


                        quiz_subtitle.innerHTML =  "Question:" + [i+1] + " " + response.data[i].quiz_query;

                        if(response.data[i].quiz_type == "multiple_choice") {
                            for (let j = 0; j < response.data[i].options.length; j++) {

                                const radioDiv = document.createElement('div');
                                var radioDiv2 = document.createElement('div');
                                radioDiv2.classList.add('choice-widgit');

                                    const radio = document.createElement('input');
                                    radio.type = 'radio';
                                    radio.name = 'option';
                                    radio.value = response.data[i].options[j].id;
                                    // if(response.data[i].userSelection !== null) {
                                        if (response.data[i].userSelection.attempted_answer == response.data[i].options[j].id) {
                                            if (response.data[i].correct_answer == response.data[i].userSelection.attempted_answer) {
                                                radioDiv.classList.add('choice-widgit', 'success_vt');
                                            } else {
                                                radioDiv.classList.add('choice-widgit', 'error_vt');
                                                radio.checked = true;
                                                // radio.classList.add('error_vt') ;
                                            }
                                        } else {
                                            radioDiv.classList.add('choice-widgit');
                                            radio.checked = false;
                                        }
                                        if (response.data[i].correct_answer == response.data[i].options[j].id) {
                                            radioDiv.classList.add('choice-widgit', 'success_vt');
                                        }
                                    // }



                                    const label = document.createElement('label');
                                    label.innerHTML = response.data[i].options[j].quiz_options;
                                    label.classList.add('radio-container');

                                    const span = document.createElement('span');
                                    // span.classList.add('checkmark')

                                    label.appendChild(radio);
                                    radioDiv.appendChild(label);
                                    label.appendChild(span);

                                    quiz_title.appendChild(radioDiv);
                                    container.appendChild(quiz_title);

                            }
                        }
                        else{
                            var correctAnswer;

                            for (let k = 0; k < response.data[i].statements.length; k++) {
                                const statementMainDiv = document.createElement('div');
                                statementMainDiv.classList.add('licensed_vt');

                                const parentMainDiv = document.createElement('div');
                                parentMainDiv.classList.add('square-section-area');


                                const statementDiv = document.createElement('div');
                                statementDiv.id = response.data[i].statements[k].id;
                                statementDiv.innerHTML = response.data[i].statements[k].UserSelectedOptionText;

                                parentMainDiv.append(statementDiv);

                                if(response.data[i].statements[k].UserSelectedOptionText == response.data[i].statements[k].correctionOptionText){
                                    statementDiv.classList.add('square-section','selected-text','drag-success_vt');
                                }
                                else{
                                    statementDiv.classList.add('square-section','selected-text','drag-error_vt');
                                    var correctAnswer = document.createElement('div');
                                    correctAnswer.classList.add('square-section_vt');
                                    correctAnswer.innerHTML = response.data[i].statements[k].correctionOptionText;
                                    parentMainDiv.append(correctAnswer);
                                }

                                const statement = document.createElement('p')
                                statement.id = response.data[i].statements[k].id
                                statement.innerHTML = response.data[i].statements[k].statement;
                                // if(statementDiv.classList.contains('drag-error_vt')){
                                //     statement.innerHTML = response.data[i].statements[k].correctionOptionText + response.data[i].statements[k].statement;
                                // }

                                var replacedContent = statement.innerHTML.replace("##", parentMainDiv.outerHTML);
                                statement.innerHTML = replacedContent




                                statementMainDiv.appendChild(statement);
                                // statementMainDiv.appendChild(statementDiv);

                                quiz_title.appendChild(statementMainDiv );
                                container.appendChild(quiz_title);
                            }
                        }
                    }
                }

            }
        });

    }

    function updateSessionData(id){
        document.getElementById('welcomeDiv1').style.display = "block";
        document.getElementById('welcomeDiv').style.display = "none";
        document.getElementById('welcomeDiv2').style.display = "none";
        document.getElementById('noDataImage').style.display = "none";
        document.getElementById('after_quiz_start').style.display = "none";
        $.ajax({
            url: "{{route('session.detail')}}",
            type: "POST",
            data: {
                session_id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {

                    document.getElementById("course_title").value = "";
                    document.getElementById("session_name").value = "";
                    document.getElementById("session_description").value = "";
                    var title = document.getElementById("course_title");
                    title.innerHTML =  " > " +response.data.session_name;

                    var heading = document.getElementById("session_name");
                    heading.innerHTML = response.data.session_name;


                    var description = response.data.description;
                    $('#session_description').html(description);
                }
            }
        })
    }
</script>
<script>
    var TopicTitle = null;
    function getTopicHeading(topicElement){
         var getTopicsHeading  = topicElement.textContent;
         var separatedString = getTopicsHeading.split(".")[0];
         var number = separatedString.match(/\d+/)[0];
         TopicTitle = "Topic " + number;

    }
    function showDiv(id) {
        document.getElementById('welcomeDiv').style.display = "block";
        document.getElementById('welcomeDiv1').style.display = "none";
        document.getElementById('welcomeDiv2').style.display = "none";
        document.getElementById('noDataImage').style.display = "none";
        document.getElementById('after_quiz_start').style.display = "none";

        $.ajax({
            url: "{{route('course.des.detail')}}",
            type: "POST",
            data: {
                topic_id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    var title = document.getElementById("course_title_topic");
                    title.innerHTML = " > " + response.data.topic_name;

                    var title = document.getElementById("topic_name");
                    title.innerHTML = response.data.topic_name;

                    var heading = document.getElementById("topic_description");
                    heading.innerHTML = response.data.description;

                    var titless = document.getElementById("course_topic_no");
                    titless.innerHTML = TopicTitle;
                    topic_name = response.data.topic_name;
                }
            }
        })

    }
    function showQuestion(id) {
        quiz_duration = null;
        // document.getElementById('welcomeDiv2').style.display = "block";
        document.getElementById('welcomeDiv1').style.display = "none";
        document.getElementById('loginCheck').style.display = "none";
        document.getElementById('welcomeDiv').style.display = "none";
        document.getElementById('courseBuy').style.display = "none";
        // document.getElementById('topicSection').style.display = "none";
        document.getElementById('after_quiz_start').style.display = "none";
        document.getElementById('result-section').style.display = "none";


        $.ajax({
            url: "{{route('session.quiz.detail')}}",
            type: "POST",
            data: {
                topic_id: id,
                // course_id : course_id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    var checkQuiz = response.data[1]
                if(checkQuiz === null){
                        document.getElementById('noDataImage').style.display = "block";
                        document.getElementById('welcomeDiv2').style.display = "none";
                }
                else{
                    quiz_id = response.data[1].id
                    var viewResult = response.data[3];
                        document.getElementById('noDataImage').style.display = "none";
                        document.getElementById('topicSection').style.display = "block";
                        document.getElementById('welcome123').style.display = "block";
                        document.getElementById('welcomeabc').style.display = "block";
                        document.getElementById('welcomeDiv2').style.display = "block";
                        document.getElementById('courseBuy').style.display = "none";
                        document.getElementById('viewResult').style.display = "none";
                        document.getElementById('nodata').style.display = "none";
                        document.getElementById('welcomeDiv2').style.display = "block";
                        document.getElementById('myButton').style.display = "block";
                        var title = document.getElementById("course_title_quiz");
                        if(response.data[0] !== null){
                            var titles = document.getElementById("course_title_quizs");
                            if(response.data[1].quiz_type == "MOCK")
                            {
                              title.innerHTML = " > " + response.data[1].quiz_title;
                              titles.innerHTML = " > " + response.data[1].quiz_title;
                            }
                            else{
                                title.innerHTML = " > " + response.data[0].topic_name + " > " + response.data[1].quiz_title;
                                titles.innerHTML = " > " + response.data[0].topic_name + " > " + response.data[1].quiz_title;
                            }
                        }
                        var heading = document.getElementById("quiz_title");
                        heading.innerHTML = response.data[1].quiz_title;

                        var headings = document.getElementById("quiz_titles");
                        headings.innerHTML = response.data[1].quiz_title;

                        duration = document.getElementById("duration");
                        duration.innerHTML = response.data[1].duration + " Minutes"
                        quiz_duration = response.data[1].duration;

                        var passing_grad = document.getElementById("passsing_grade");
                        passing_grad.innerHTML = response.data[1].passing_grade + "%";

                        var guidelines = document.getElementById("guidelines");
                        guidelines.innerHTML = response.data[1].quiz_guidelines;

                        total_questions = document.getElementById("quiz_questions");
                        total_questions.innerHTML = response.data[2] + " Question(s)";

                        document.getElementById("question_timer_update").innerHTML = 1;
                        document.getElementById("question_timer").innerHTML = response.data[2];


                        var myButton = document.getElementById("myButton");
                        myButton.value =  response.data[1].id;
                        // myButton.value = response.data[1].topic_id + "," + response.data[1].id;

                        quiz_title = response.data[1].quiz_title;

                        if(viewResult !== null && viewResult !== undefined && viewResult !== ''){
                            document.getElementById('viewResult').style.display = "block";
                            document.getElementById('myButton').style.display = "none";
                            var button = document.getElementById('viewResult');
                            button.addEventListener("click", function() {
                                document.getElementById('after_quiz_start').style.display = "block";
                                document.getElementById('result-section').style.display = "block";
                                document.getElementById('topicSection').style.display = "block";
                                document.getElementById('welcomeDiv2').style.display = "none";
                                document.getElementById('welcomeDiv1').style.display = "none";
                                document.getElementById('welcomeDiv').style.display = "none";
                            submitQuiz();
                            });
                        }
                    }
                }
            }
        })
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.7/dist/interact.min.js"></script>
<script>
    
    var dragDrop = [];
    //DO NOT REMOVE THIS CODE
    // $(document).ready(function() {
    //     $(document).on('mouseenter', '.move-this', function() {
    //         alert("mouseenter")
    //         $(this).draggable();
    //     });
    //     $(document).on('touchstart', '.move-this', function(e) {
    //         e.preventDefault(); // Prevent default touch behavior
    //         $(this).draggable(); // Assign draggable behavior
    //         // alert("Touchstart event");
    //     });
    // });

    const position = { x: 0, y: 0 }
        interact('.move-this').draggable({
        autoScroll: true,
        inertia: true,
        modifiers: [
        interact.modifiers.restrictRect({
            endOnly: true
        })
    ],
        listeners: {
            start (event) {
            // console.log(event.type, event.target)
            position.x = 0;
            position.y = 0;
            },
            move (event) {
            position.x += event.dx
            position.y += event.dy
            event.target.style.transform =
                `translate(${position.x}px, ${position.y}px)`
            },
        }
        })

        interact('.droppable').dropzone({  
            accept: '.move-this',
            overlap: 0.5,
            ondropactivate: function(event) {
                event.target.classList.add('drop-active');
            },
            ondragenter: function(event) {
                event.target.classList.add('drag-over');
            },
            ondragleave: function(event) {
                event.target.classList.remove('drag-over');
            },
            ondrop: function(event) {
                var dropableValue = event.target.id;
                var draggedId = event.relatedTarget.id;
                var statementIdToCheck = dropableValue;
                var index = dragDrop.findIndex(item => item.statement_id === statementIdToCheck);

                if (index !== -1) {
                    dragDrop.splice(index, 1);
                }
                dragDrop.push({
                    statement_id: dropableValue,
                    answer_id: draggedId
                });
                console.log(dragDrop, dropableValue, draggedId);
                AnswerId = dragDrop;
                
                event.target.classList.remove('drag-over');
            },
            ondropdeactivate: function(event) {
                event.target.classList.remove('drop-active');
            }
        });

            //DO NOT REMOVE THIS CODE
    // $(document).on('mouseover', '.droppable', function() {
    // // Handle click on dynamically created dropable element\
    //     $('p').droppable({
    //         drop: function(event, ui) {
    //                 var dropableValue = $(this).attr('id');
    //                 var draggedId = ui.draggable.attr('id');
    //                 var statementIdToCheck = dropableValue;
    //                 var index = dragDrop.findIndex(item => item.statement_id === statementIdToCheck);
    //                 if (index !== -1) {
    //                     dragDrop.splice(index, 1);
    //                 }
    //                 dragDrop.push({
    //                 statement_id: dropableValue,
    //                 answer_id: draggedId
    //             });
    //                 console.log(dragDrop, dropableValue, draggedId);
    //         }
    //     });
    //     AnswerId = dragDrop;

    // });
var currentTab = 0;
document.addEventListener("DOMContentLoaded", function(event) {


    // showTab(currentTab);

});
function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    if (n == 1 && !validateForm()) return false;
    x[currentTab].style.display = "none";
    currentTab = currentTab + n;
    if (currentTab >= x.length) {
        // document.getElementById("regForm").submit();
        // return false;
        //alert("sdf");
        document.getElementById("nextprevious").style.display = "none";
        document.getElementById("all-steps").style.display = "none";
        document.getElementById("register").style.display = "none";
        document.getElementById("text-message").style.display = "block";




    }
    showTab(currentTab);
}

function showTab(n) {
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
    } else {
        document.getElementById("nextBtn").innerHTML = "Next";
    }
    fixStepIndicator(n)
}
function fixStepIndicator(n) {
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) { x[i].className = x[i].className.replace(" active", ""); }
    x[n].className += " active";
}
</script>

</html>
