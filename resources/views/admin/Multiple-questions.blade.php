@extends('admin_layouts.master')
@section('content')

    <head>

    </head>
    <style>
        .course-alert {
            position: absolute;
            right: 0;
            top: 0;
        }
    </style>
    <div>
        <div class="lesson-page-content">
            <div class="course-alert">
                @if (session()->has('success'))
                    <div class="alert alert-success" id="alertID">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> {{ session('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger" id="alertID">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close"></a> {{ session('error') }}
                    </div>
                @endif
            </div>
            <div class="lesson-wrapper">
                <form action="{{ route('save.multiple.question') }}" method="POST">
                    @csrf
                    <div class="detail-head-arrea">
                        <div>
                            @if ($question_id == null)
                                <h1>New Question</h1>
                            @else
                                <h1>Update Question</h1>
                            @endif
                            <p class="head-subtitle">Multiple Choice Multiple Answers Question</p>
                        </div>
                        <div class="d-flex">
                            <!-- <div class="detail-input">
                                <input type="search" class="form-control" id="search-text" name="search-area" placeholder="Search here...">
                                <i class="fontello icon-search"></i>
                            </div> -->
                            <div>
                                @if ($question_id == null)
                                    <button type="submit" class="btn btn-warning custom-btn">Save Question</button>
                                @else
                                    <button type="submit" class="btn btn-warning custom-btn">Update Question</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($question_id == null)
                        <div class="multiptle-content-area">
                            <label for="basic-url" class="form-label">Quiz</label>
                            <div>
                                <select class="selectpicker mb-2 w-100" data-live-search="true" name="quiz_id">
                                    <option selected disabled>Select Quiz</option>
                                    @foreach ($getQuiz as $quiz)
                                        <option value="{{ $quiz->id }}"
                                            {{ $quiz->id == old('quiz_id') ? 'selected' : '' }}>{{ $quiz->quiz_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="basic-url" class="form-label">Question Points</label>
                            <div class="input-group mb-2">
                                <input type="number" name="points" value="{{ old('points') }}"
                                    class="form-control modal-input" id="" placeholder="Enter Question Points">
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="textarea">Question</label>
                                <textarea class="summernote" name="quiz_query" placeholder="Enter Description">{{ old('quiz_query') }}</textarea>
                            </div>
                            <div class="multiple-choice-area">
                                <div class="multiple-choice-sub-area">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <label class="form-label m-0" for="option1">Option 1</label>
                                            </div>
                                            <div>
                                                <label class="radio-container">Correct Annwer
                                                    <input type="radio" name="radio" value="option1">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control modal-input" name="option1"
                                            value="{{ old('option1') }}" id="" placeholder="Enter Option">
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <label class="form-label m-0" for="option1">Option 2</label>
                                            </div>
                                            <div>
                                                <label class="radio-container">Correct Annwer
                                                    <input type="radio" name="radio" value="option2">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" name="option2" value="{{ old('option2') }}"
                                            class="form-control modal-input" id="" placeholder="Enter Option">
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <label class="form-label m-0" for="option1">Option 3</label>
                                            </div>
                                            <div>
                                                <label class="radio-container">Correct Annwer
                                                    <input type="radio" name="radio" value="option3">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" name="option3" value="{{ old('option3') }}"
                                            class="form-control modal-input" id="" placeholder="Enter Option">
                                    </div>
                                    <div id="input-fields" class="mb-3"></div>
                                </div>
                                <div class="d-flex justify-content-center mb-3">
                                    <button onclick="addInputField()" type="button"
                                        class="btn btn-warning custom-btn custom-btn-outline w-100">Add Option</button>
                                </div>
                            </div>

                        </div>
                    @else
                        <div class=" ">
                            <input type="hidden" value="{{ $question_id }}" name="question_id">
                            <label for="basic-url" class="form-label">Quiz</label>
                            <select class="selectpicker mb-2 w-100" data-live-search="true" name="quiz_id">
                                <option selected disabled>Select Quiz</option>
                                @foreach ($getQuiz as $quiz)
                                    <option value="{{ $quiz->id }}"
                                        {{ $getQuestion->quiz_id == $quiz->id ? 'selected' : '' }}>{{ $quiz->quiz_title }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="basic-url" class="form-label">Question Points</label>
                            <div class="input-group mb-2">
                                <input type="number" value="{{ $getQuestion->points }}" name="points"
                                    class="form-control modal-input" id="" placeholder="Enter Question Points">
                            </div>
                            <div class="mb-2">
                                <label class="form-label" for="textarea">Question</label>
                                <textarea class="summernote" name="quiz_query" placeholder="Enter Description" id="update_summernote"></textarea>
                            </div>
                            <div class="multiple-choice-area">
                                <div class="multiple-choice-sub-area">
                                    @foreach ($getOptions as $key => $option)
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div>
                                                    <label class="form-label m-0" for="option1">Option
                                                        {{ $key + 1 }}</label>
                                                </div>
                                                <div>
                                                    <label class="radio-container">Correct Annwer
                                                        <input type="radio" name="radio"
                                                            value="option{{ $key + 1 }}"
                                                            {{ $getQuestion->correct_answer == $option->id ? 'checked' : '' }}>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control modal-input"
                                                value="{{ $option->quiz_options }}" name="option{{ $key + 1 }}"
                                                id="" placeholder="Enter Question">
                                        </div>
                                    @endforeach
                                    <div id="input-fields" class="mb-3"></div>
                                </div>
                                <div class="d-flex justify-content-center mb-3">
                                    <button onclick="addInputField()" type="button"
                                        class="btn btn-warning custom-btn custom-btn-outline w-100">Add Option</button>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>


        <script>
            $(document).ready(function() {
                $('.summernote').summernote({
                    callbacks: {
                        onPaste: function(e) {
                            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData)
                                .getData('Text');

                            // Insert the pasted content as plain text without filtering
                            e.preventDefault();
                            document.execCommand('insertText', false, bufferText);
                        }
                    },
                    height: 100,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ol', 'ul', 'paragraph', 'height']],
                        ['table', ['table']],
                        ['insert', ['link']],
                        ['view', ['codeview', 'help']]
                    ]
                });

            });
        </script>
        <script type="text/javascript">
            setTimeout(function() {
                $('#alertID').hide('slow')
            }, 2000);
        </script>
        <script type="text/javascript">
            var getQuestionId = {!! isset($question_id) ? $question_id : 'null' !!}
            if (getQuestionId === null) {
                var divLoop = 3
            } else {
                var countOptions = {!! isset($getOptions) ? count($getOptions) : 'null' !!}
                var divLoop = countOptions
            }

            function addInputField() {
                divLoop += 1;
                var newInput = document.createElement("input");
                newInput.setAttribute("type", "text");
                newInput.setAttribute("name", "option" + divLoop);
                newInput.setAttribute("placeholder", "Enter Option");
                newInput.classList.add("form-control", "modal-input", "mb-3");
                //first label section

                var newLabel = document.createElement("label");
                newLabel.classList.add("form-label", "m-0");
                newLabel.innerHTML = "Option " + divLoop;

                var firstLabelDiv = document.createElement("div");
                firstLabelDiv.appendChild(newLabel);
                //ends here

                //lower label section
                var newRadio = document.createElement("input");
                newRadio.setAttribute("type", "radio");
                newRadio.setAttribute("name", "radio");
                newRadio.setAttribute("value", "option" + divLoop);

                var radioSpan = document.createElement("span");
                radioSpan.classList.add("checkmark");

                var inputlabel = document.createElement("label");
                inputlabel.classList.add("radio-container");
                inputlabel.innerHTML = "Correct Annwer";

                inputlabel.appendChild(newRadio);
                inputlabel.appendChild(radioSpan);

                var radioDiv = document.createElement("div");
                radioDiv.appendChild(inputlabel);
                //ends here


                var newDiv1 = document.createElement("div");
                newDiv1.classList.add("d-flex", "justify-content-between", "mb-2");
                newDiv1.appendChild(firstLabelDiv);
                newDiv1.appendChild(radioDiv);

                var mb3Div = document.createElement("div");
                mb3Div.classList.add("mb-3");
                mb3Div.appendChild(newDiv1);
                mb3Div.appendChild(newInput);
                var newDiv = document.createElement("div");

                var getDiv = document.getElementsByClassName("multiple-choice-sub-area")[0];
                getDiv.appendChild(mb3Div);
            }
            $(document).ready(function() {
                if (getQuestionId !== null) {
                    var summernoteText = "{!! isset($getQuestion->quiz_query) ? $getQuestion->quiz_query : 'null' !!}";
                    var summernoteInstance = $('#update_summernote').summernote();
                    summernoteInstance.summernote('code', summernoteText);
                }
            })
        </script>
        <script></script>
    @endsection
