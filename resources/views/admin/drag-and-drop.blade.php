@extends('admin_layouts.master')
@section('content')

    <head>
        <link rel="stylesheet" href="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
    </head>
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
                <form action="{{ route('save.drag.drop.question') }}" method="POST">
                    @csrf
                    <div class="detail-head-arrea">
                        <div>
                            @if ($question_id == null)
                                <h1>New Question</h1>
                            @else
                                <h1>Update Question</h1>
                            @endif
                            <p class="head-subtitle">Drag and Drop Question</p>
                        </div>
                        <div class="d-flex">
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
                            <div class="note-section">
                                <h2><i class="fontello icon-file-pdf"></i> Note</h2>
                                <p>Wrap the word or words you wish to make a blank with ##(DOUBLE HASH). E.g.
                                    ##BLANK_ITEM##. The system will automatically convert them to empty blanks, and users
                                    will be provided with text boxes to enter their responses.</p>
                            </div>
                            <label for="basic-url" class="form-label">Quiz</label>
                            <div>
                                <select class="selectpicker mb-2 w-100" data-live-search="true" aria-label=""
                                    name="quiz_id">
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
                            <div class="multiple-choice-area">
                                <div id="multiple-choice-inner-area">
                                    <div class="mb-3">
                                        <label class="form-label" for="textarea">Question Line 1</label>
                                        <textarea class="summernote" name="question1" placeholder="Enter Description">{{ old('question1') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="textarea">Question Line 2</label>
                                        <textarea class="summernote" name="question2" placeholder="Enter Description">{{ old('question2') }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="textarea">Question Line 3</label>
                                        <textarea class="summernote" name="question3" placeholder="Enter Description">{{ old('question3') }}</textarea>
                                        <div id="input-fields" class="mb-3"></div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mb-3">
                                    <button onclick="addInputField()" type="button"
                                        class="btn btn-warning custom-btn custom-btn-outline w-100">Add Option</button>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Enter Incorrect Word.<small>(Press comma to create
                                            Tags)</small></label>
                                    <div class="bs-example">
                                        <input type="text" id="#inputTag" data-role="tagsinput" name="incorrect_words"
                                            value="{{ old('incorrect_words') }}">
                                    </div>
                                </div>

                            </div>
                        @else
                            <div class="multiptle-content-area">
                                <input type="hidden" value="{{ $question_id }}" name="statement_id">
                                <div class="note-section">
                                    <h2><i class="fontello icon-file-pdf"></i> Note</h2>
                                    <p>Wrap the word or words you wish to make a blank with ##(DOUBLE HASH). E.g.
                                        ##BLANK_ITEM##. The system will automatically convert them to empty blanks, and
                                        users will be provided with text boxes to enter their responses.</p>
                                </div>
                                <label for="basic-url" class="form-label">Quiz</label>
                                <div>
                                    <select class="selectpicker mb-2 w-100" data-live-search="true" name="quiz_id">
                                        <option selected disabled>Select Quiz</option>
                                        @foreach ($getQuiz as $quiz)
                                            <option value="{{ $quiz->id }}"
                                                {{ $getQuestion->quiz_id == $quiz->id ? 'selected' : '' }}>
                                                {{ $quiz->quiz_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label for="basic-url" class="form-label">Question Points</label>
                                <div class="input-group mb-2">
                                    <input type="number" value="{{ $getQuestion->points }}" name="points"
                                        class="form-control modal-input" id="" placeholder="Enter Question Points"
                                        required>
                                </div>
                                <div class="multiple-choice-area">
                                    <div id="multiple-choice-inner-area">
                                        @foreach ($getStatements as $key => $statement)
                                            <div class="mb-3">
                                                <label class="form-label" for="textarea">Question Line
                                                    {{ $key + 1 }}</label>
                                                <textarea class="summernote" name="question{{ $key + 1 }}" placeholder="Enter Description"
                                                    id="question{{ $key + 1 }}"></textarea>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mb-3">
                                    <button onclick="addInputField()" type="button"
                                        class="btn btn-warning custom-btn custom-btn-outline w-100">Add Option</button>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Enter Incorrect Word.<small>(Press comma to create
                                            Tags)</small></label>
                                    <div class="bs-example">
                                        <input type="text" id="#inputTag" value="{{ $getIncorrectOptions }}"
                                            data-role="tagsinput" name="incorrect_words">
                                    </div>
                                </div>
                            </div>
                    @endif
                </form>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
        <script src="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>

        <script>
            $("#inputTag").tagsinput('items');
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
                var countOptions = {!! isset($getStatements) ? count($getStatements) : 'null' !!}
                var divLoop = countOptions
            }

            function addInputField() {
                divLoop += 1;
                // var newInput = document.createElement("textarea");
                // newInput.setAttribute("name", "content_abstract");
                // newInput.classList.add("summernote");

                var textarea = document.createElement("textarea");
                textarea.classList.add("summernote");
                textarea.id = "myTextarea";
                textarea.name = "question" + divLoop;
                textarea.rows = 5;
                textarea.cols = 30;


                // var div = document.getElementById("myDiv");
                // div.appendChild(textarea);


                //first label section

                var newLabel = document.createElement("label");
                newLabel.classList.add("form-label", "m-0");
                newLabel.innerHTML = "Question Line " + divLoop;

                var firstLabelDiv = document.createElement("div");
                firstLabelDiv.classList.add("mb-3");
                firstLabelDiv.appendChild(newLabel);
                firstLabelDiv.appendChild(textarea);

                var getDiv = document.getElementById("multiple-choice-inner-area");
                getDiv.appendChild(firstLabelDiv);
                $('.summernote').summernote({
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
                        ['view', ['codeview']]
                    ]
                });
            }
        </script>
        <script>
            
    window.onload = function() {
            $('.summernote').summernote({
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
                    ['view', ['codeview']]
                ]
            });
      };

        </script>
        <script type="text/javascript">
            $('#selectDrag').select2({
                theme: 'bootstrap-5'
            });
            var getStatements = {!! isset($getStatements) ? $getStatements : 'null' !!}
            console.log(getStatements);
            if (getStatements != null) {
                for (var i = 0; i < getStatements.length; i++) {
                    console.log(getStatements[i]);
                    var summernoteIteration = i + 1;
                    var summernoteInstance = $('#question' + summernoteIteration).summernote();
                    summernoteInstance.summernote('code', getStatements[i].updatedStatement);

                    $('.summernote').summernote({
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
                            ['view', ['codeview']]
                        ]
                    });
                }
            }
        </script>
    @endsection
