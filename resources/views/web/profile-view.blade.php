@extends(Auth::user()->role == '2'? 'web.layouts.master' : 'admin_layouts.master')
@section('content')
@if(Auth::user()->role == '2')
    @include('web.layouts.partials.header')
@endif
@section('title', '- Profile')

    <div class="container-fluid px-0">    
        
        <div class="container-fluid">
            <div class="profile-page-content">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="profile-details text-center mb-2">
                            <div class="img-holder_vt">
                                @if(Auth::user()->role == '2')
                                    <img src="{{Auth::user()->image != null && file_exists(public_path().'/assets/profile-pic/'.Auth::user()->image) ? asset('assets/profile-pic/'.Auth::user()->image) : asset('assets/images/default-user.png')}}" alt="" srcset="" class="img-circle-profile">
                                @else
                                    <img src="{{$userDetail->image != null && file_exists(public_path().'/assets/profile-pic/'.$userDetail->image) ? asset('assets/profile-pic/'.$userDetail->image) : asset('assets/images/default-user.png')}}" alt="" srcset="" class="img-circle-profile">
                                @endif
                                    <!-- <label>
                                    <input type="file" name="myImage" accept="image/*" />
                                </label> -->
                            </div>
                            <h1>{{$userDetail->full_name}}</h1>
                            <p>{{$userDetail->email}}</p>
                            
                            <div class="widget-overviews-area">
                                <div class="widget-overviews-box">
                                    <h2>{{count($userCourses)}}</h2>
                                    <p>Enrolled Courses</p>
                                </div>
                                <div class="widget-overviews-box">
                                    <h2>{{$userCertificates}}</h2>
                                    <p>Completed</p>
                                </div>
                                <div class="widget-overviews-box">
                                    <h2>{{$course_points}}</h2>
                                    <p>Points</p>
                                </div>
                            </div>
                            @if(Auth::user()->role == '2')
                            <div class="mt-3">
                                <a href="{{route('update.profile')}}"><button type="button" class="btn btn-warning custom-btn profile-btn w-100">Edit Profile</button></a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="quiz-taken-widget" style="border: 2px solid #D2D4E5;">
                            <h5 class="header-title-profile border-bottom">{{$courses->course_title	}}</h5>
                        
                            <div class="quiz-taken-area border-0">
                                <h3 class="mb-2">Attempted Quiz</h3>
                                <div class="quiz-content">
                                    @forelse($userAttemptedQuizes as $quiz)
                                    @if($quiz->stats != null)
                                    <div class="py-3 border-bottom">
                                        <div class="quiz-taken-btn">
                                            <h4>{{$quiz->quiz_title}}</h4>
                                            @if(Auth::user()->role == '2')
                                                <button type="button" class="btn btn-warning custom-btn profile-btn" onclick="fetchQUizDetails('{{$quiz->id}}','{{$quiz->quiz_title}}',null)">View Details</button>
                                            @else
                                                <button type="button" class="btn btn-warning custom-btn profile-btn" onclick="fetchQUizDetails('{{$quiz->id}}','{{$quiz->quiz_title}}','{{$userDetail->id}}')">View Details</button>

                                            @endif
                                            </div>
                                        <?php
                                        $dateStr = \Carbon\Carbon::parse($quiz->stats->created_at)->format('Y-m-d');
                                        
                                        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $dateStr);
                                        $formattedDate = $date->format('d F Y');
                                        $time = \Carbon\Carbon::parse($quiz->stats->created_at)->format('h:i A');
                                        ?>
                                        <p>Scored {{$quiz->stats->total_questions - $quiz->stats->skipped}} out of {{$quiz->stats->total_questions}} question(s) on {{$formattedDate}} at {{$time}}</p>
                                        <div class="d-flex align-items-center time-schedule mt-2">
                                            <i class="fontello icon-statistics"></i>
                                            <p style="color: #00E540;"><bold style="color: #1B1B1E; font-weight: 700;">Statistics :</bold>{{$quiz->stats->result}}%</p>
                                        </div>
                                        <div class="d-flex align-items-center time-schedule mt-2">
                                            <i class="fontello icon-point"></i>
                                            <p><bold style="color: #1B1B1E; font-weight: 700;">Points :</bold> {{$quiz->stats->obtained_marks}} / {{$quiz->stats->total_marks}}</p>
                                        </div>
                                    </div>
                                    @endif
                                    @empty<div class="course-detail-img">
                                        <img src="{{asset('assets/images/emptyBlogs.jpeg')}}" style="height:100px; width:100px"alt="">
                                        <p>No Quizzes Found!</p>
                                    </div>
                                @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal_vt">
            <div class="modal-content">
                <div class="modal-header">
                <div class="modal-head">
                    <h5 class="modal-title" id="quizTitleLable"></h5>
                    <!-- <p id="dataTime">April 8, 2023 9:22 am</p> -->
                </div>
                <button type="button" class="btn btn-warning custom-btn profile-btn" data-bs-dismiss="modal" aria-label="Close">Close</button>
                </div>
                <div class="modal-body">
                    <div style="overflow:auto;">
                        <div class="result-section_vt" id="result-section">
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
                                    @if(Auth::user()->role == '2')
                                    <button type="button" class="btn btn-warning custom-btn me-3" onclick="loadCompleteQuiz(null)">View Questions</button>
                                    @else
                                    <button type="button" class="btn btn-warning custom-btn me-3" onclick="loadCompleteQuiz('{{$userDetail->id}}')">View Questions</button>
                                    @endif
                                </div>
                            </div>
                      </div>
                       <div id="user-result-display"></div>
                </div>
            </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
    var Quiz_id = null;
    var Quiz_Title = null;
    function fetchQUizDetails(quizId,quizTitle,user_id){
        $('#user-result-display').empty();
        Quiz_id = quizId;
        Quiz_Title = quizTitle;
        $.ajax({
            url: "{{route('show.results')}}",
            type: "POST",
            data: {
                quiz_id: quizId,
                user_id : user_id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    var model = new bootstrap.Modal(document.getElementById("exampleModal"));
                    model.show();
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
                    document.getElementById('quizTitleLable').textContent = Quiz_Title;
                    document.getElementById('quiz_passed').textContent = response.data.result_percentage >= response.data.passing_marks ? "You have successfully passed your Quiz" : "You have failed the quiz"
                }}
            });
    }
     function loadCompleteQuiz(user_id){
        $('#user-result-display').empty();
        $.ajax({
            url: "{{route('load.complete.quiz')}}",
            type: "POST",
            data: {
                quiz_id: Quiz_id,
                user_id : user_id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success){
                    $('#user-result-display').empty();
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
                                    if(response.data[i].userSelection.attempted_answer == response.data[i].options[j].id){
                                        if(response.data[i].correct_answer == response.data[i].userSelection.attempted_answer){
                                        radioDiv.classList.add('choice-widgit','success_vt');
                                        }
                                        else{
                                            radioDiv.classList.add('choice-widgit','error_vt');
                                             radio.checked = true;
                                            // radio.classList.add('error_vt') ;
                                        }
                                    }
                                    else{
                                        radioDiv.classList.add('choice-widgit');
                                         radio.checked = false;
                                    }
                                    if(response.data[i].correct_answer == response.data[i].options[j].id){
                                        radioDiv.classList.add('choice-widgit','success_vt');
                                    }



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
</script>
</html>
@endsection