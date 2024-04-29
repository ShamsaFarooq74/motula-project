
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@300;500;700;800;900&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="fonts/fontello/css/fontello.css">
    <link rel="stylesheet" href="css/style.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>



    <title>Attempt-Quiz</title>
    <style>
        /* #regForm {
    background-color: #ffffff;
    margin: 0px auto;
    font-family: Raleway;
    padding: 40px;
    border-radius: 10px
} */

/* input {
    padding: 10px;
    width: 100%;
    font-size: 17px;
    font-family: Raleway;
    border: 1px solid #aaaaaa
}

input.invalid {
    background-color: #ffdddd
} */

.tab {
    display: none
}



#prevBtn {
    background-color: #bbbbbb
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

/* .all-steps {
    text-align: center;
    margin-top: 30px;
    margin-bottom: 30px
} */

.thanks-message {
    display: none
}


    </style>
</head>
<body>
    <div class="container-fluid px-0">        
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-white shadow-sm">
                <div class="container-fluid header-wrapper_vt">
                    <a class="navbar-brand" href="#"><img class="logo-img" src="images/seru-logo.png" alt="" srcset=""></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-data">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-data">Courses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-data">blogs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-data">Contact</a>
                            </li>
                        </ul>
                        <div class="header-btn-widgit">
                            <a><i class="fontello icon-menu-icon"></i></a>
                            <a href="#" class="btn btn-warning header-btn_vt">Get Started</a>
                        </div>
                    </div>
                    
                </div>
            </nav>
        </header>
        <div class="quiz-main-page-content">
            <div class="quiz-wrapper">
                <div class="quiz-left-section">
                    <div class="left-header">
                        <h1>Online SERU Training-4 Weeks Access</h1>
                    </div>
                    <div class="left-accordian">
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="content-circle">
                                                <i class="fontello icon-book-open "></i>
                                            </div>
                                            <div class="content-intro">
                                                <h4>Introduction</h4>
                                                <p>Online SERU Training</p>
                                                <div class="d-flex mt-2">
                                                    <div class="topics_vt d-flex topic-first-vt">
                                                        <i class="fontello icon-book-alt"></i>
                                                        <p>1 Topic</p>
                                                    </div>
                                                    <div class="topics_vt d-flex">
                                                        <i class="fontello icon-lightbulb"></i>
                                                        <p>1 Quiz</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <!-- <div class="preview_vt">
                                            <div class="d-flex expectation_vt align-items-center">
                                                <div class="accordian-circle"></div>
                                                <p>SERU Assessment – what to expect</p>
                                            </div>
                                            <div class="">
                                                <button type="button" class="btn btn-warning custom-btn preview-btn">Preview</button>
                                            </div>
                                        </div> -->
                                        <form id="regForm">
                                            <div class="all-steps" id="all-steps"> 
                                                <div class="d-flex steps-content">
                                                    <span class="step"></span>
                                                    <div class="pl-10">
                                                        <a href="#"><p>Topic 1. Licensing requirements</p></a>
                                                        <a href="#">
                                                            <div class="d-flex align-items-center quiz-topics-content">
                                                                <i class="fontello icon-quiz-check"></i>
                                                                <p>Questions for 1. Licensing Requirements</p>
                                                            </div>
                                                        </a>
                                                    </div> 
                                                </div>
                                                <div class="d-flex steps-content">
                                                    <span class="step"></span>
                                                    <div class="pl-10">
                                                        <p>Topic 1. Licensing requirements</p>
                                                        <div class="d-flex align-items-center quiz-topics-content">
                                                            <i class="fontello icon-quiz-check"></i>
                                                            <p>Questions for 1. Licensing Requirements</p>
                                                        </div>
                                                    </div> 
                                                </div>
                                                <div class="d-flex steps-content">
                                                    <span class="step"></span>
                                                    <div class="pl-10">
                                                        <p>Topic 1. Licensing requirements</p>
                                                        <div class="d-flex align-items-center quiz-topics-content">
                                                            <i class="fontello icon-quiz-check"></i>
                                                            <p>Questions for 1. Licensing Requirements</p>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="content-circle">
                                                <i class="fontello icon-book-open "></i>
                                            </div>
                                            <div class="content-intro">
                                                <h4>Section 1</h4>
                                                <p>London PHV Driver Licensing</p>
                                                <div class="d-flex mt-2">
                                                    <div class="topics_vt d-flex topic-first-vt">
                                                        <i class="fontello icon-book-alt"></i>
                                                        <p>1 Topic</p>
                                                    </div>
                                                    <div class="topics_vt d-flex">
                                                        <i class="fontello icon-lightbulb"></i>
                                                        <p>1 Quiz</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="preview_vt">
                                            <div class="d-flex expectation_vt align-items-center">
                                                <div class="accordian-circle"></div>
                                                <p>SERU Assessment – what to expect</p>
                                            </div>
                                            <div class="">
                                                <button type="button" class="btn btn-warning custom-btn preview-btn">Preview</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="content-circle">
                                                <i class="fontello icon-book-open "></i>
                                            </div>
                                            <div class="content-intro">
                                                <h4>Section 2</h4>
                                                <p>Licensing Requirements for PHVs</p>
                                                <div class="d-flex mt-2">
                                                    <div class="topics_vt d-flex topic-first-vt">
                                                        <i class="fontello icon-book-alt"></i>
                                                        <p>1 Topic</p>
                                                    </div>
                                                    <div class="topics_vt d-flex">
                                                        <i class="fontello icon-lightbulb"></i>
                                                        <p>1 Quiz</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="preview_vt">
                                            <div class="d-flex expectation_vt align-items-center">
                                                <div class="accordian-circle"></div>
                                                <p>SERU Assessment – what to expect</p>
                                            </div>
                                            <div class="">
                                                <button type="button" class="btn btn-warning custom-btn preview-btn">Preview</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="quiz-right-section">
                    <div class="mt-2" style="display: none;">
                        <div class="">
                            <div class="quiz-detail">
                                <h1>Online SERU Training-8 Weeks Access  >  London PHV Driver Licensing > Question for Licensing Requirements</h1>
                                <h2>Quiz 1 For Licencing Requirements</h2>
                                <div class="before-login-wrappe">
                                    <img src="images/lock.png" alt="">
                                    <p>You must sign in or sign up to start the quiz.</p>
                                    <div class="pt-4">
                                        <button type="button" class="btn btn-warning custom-btn">Login</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="tab">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="quiz-test-detail">
                                    <h1>Online SERU Training-8 Weeks Access  >  London PHV Driver Licensing > Question for Licensing Requirements</h1>
                                    <h2>Quiz 1 For Licencing Requirements</h2>
                                </div>
                                <div class="test-details">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="text-center">
                                                <span>1/17</span>
                                                <p>Question</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="text-center">
                                                <span>1/17</span>
                                                <p>Time Remaining</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               <!-- <div>
                                    <div class="mt-2 licensed_vt">
                                        <p>1. If you want to be a London PHV driver and want to carry out bookings from a licensed operator.<br> Then you must?</p>
                                    </div>
                                    <div class="test-section">
                                        <div class="choice-widgit">
                                            <label class="radio-container">Have a good command of Driving
                                                <input type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div class="choice-widgit">
                                            <label class="radio-container">Have a good command of Driving
                                                <input type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div class="choice-widgit">
                                            <label class="radio-container">Have a good command of Driving
                                                <input type="radio" name="radio">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                               </div> -->
                                <div>
                                    <div class="mt-2 licensed_vt">
                                        <p>Choose from the 6 words below and drag the correct words to complete the sentence. YOU MUST drag any unused words into the pink space provided </p>
                                    </div>
                                    <div class="mt-2 licensed_vt d-flex align-items-center">
                                        <p>TfL will use the SERU assessment to assess applicants’ </p>
                                        <div class="square-section mx-2"></div>
                                    </div>
                                    <div class="mt-2 licensed_vt d-flex align-items-center">
                                        <p>and writing skills. The SERU assessment is </p>
                                        <div class="square-section mx-2"></div>
                                        <p>on information found in the</p>
                                    </div>
                                    <div class="mt-2 licensed_vt d-flex align-items-center">
                                        <div class="square-section mx-2"></div>
                                        <p>Drivers Handbook.</p>
                                    </div>
                                    <div class="drop-section my-3">
                                        <p>Add Incorrect Words Here</p>
                                    </div>
                                    <div class="drag-content_vt">
                                        <div id="draggable" class="ui-widget-content draggable_vt">
                                            <p>Reading</p>
                                        </div>
                                        <div id="draggable1" class="ui-widget-content draggable_vt">
                                            <p>Learning</p>
                                        </div>
                                        <div id="draggable2" class="ui-widget-content draggable_vt">
                                            <p>Based</p>
                                        </div>
                                        <div id="draggable3" class="ui-widget-content draggable_vt">
                                            <p>PHV</p>
                                        </div>
                                        <div id="draggable4" class="ui-widget-content draggable_vt">
                                            <p>Made</p>
                                        </div>
                                        <div id="draggable5" class="ui-widget-content draggable_vt">
                                            <p>PCO</p>
                                        </div>
                                    </div>
                                </div>
                                 
                                <div class="d-flex justify-content-end mb-3">
                                    <button type="button" class="btn btn-warning custom-btn">Next</button>
                                </div>
                                <div class="quiz-progress">
                                    <div class="progress-head">
                                        <div class="progress-title">
                                            <h1>Quiz 1 Progress</h1>
                                        </div>
                                        <div class="progress-right">
                                            <div class="d-flex align-items-center pr-10">
                                                <i class="fontello icon-circle Attempt"></i>
                                                <p>Attempted</p>
                                            </div>
                                            <div class="d-flex align-items-center pr-10">
                                                <i class="fontello icon-circle pending"></i>
                                                <p>Attempted</p>
                                            </div>
                                            <div class="d-flex align-items-center pr-10">
                                                <i class="fontello icon-circle Not-attempt"></i>
                                                <p>Attempted</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress-link-holder">
                                        <div class="pr-10 mb-2">
                                            <a href="javascript:void(0)" class="btn-link_vt">Question 1</a>
                                        </div>
                                        <div class="pr-10 mb-2">
                                            <a href="javascript:void(0)" class="btn-link_vt btn-green">Question 2</a>
                                        </div>
                                        <div class="pr-10 mb-2">
                                            <a href="javascript:void(0)" class="btn-link_vt btn-gray">Question 3</a>
                                        </div>
                                        <div class="pr-10 mb-2">
                                            <a href="javascript:void(0)" class="btn-link_vt btn-orange">Question 4</a>
                                        </div>
                                        <div class="pr-10 mb-2">
                                            <a href="javascript:void(0)" class="btn-link_vt">Question 1</a>
                                        </div>
                                        <div class="pr-10 mb-2">
                                            <a href="javascript:void(0)" class="btn-link_vt btn-green">Question 2</a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="tab">
                            
        
                        </div>
                        <div class="tab">
                            
                        </div>
        
                        <div class="thanks-message text-center" id="text-message"> <img src="https://i.imgur.com/O18mJ1K.png" width="100" class="mb-4">
                            <h3>Thanks for your Donation!</h3> <span>Your donation has been entered! We will contact you shortly!</span>
                        </div>
                        <!-- <div style="overflow:auto;" id="nextprevious">
                            <div style="float:right;"> <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button> <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button> </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    var currentTab = 0;
document.addEventListener("DOMContentLoaded", function(event) {


    showTab(currentTab);

});

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

function validateForm() {
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByTagName("input");
    for (i = 0; i < y.length; i++) {
        if (y[i].value == "") {
            y[i].className += " invalid";
            valid = false;
        }
    }
    if (valid) { document.getElementsByClassName("step")[currentTab].className += " finish"; }
    return valid;
}

function fixStepIndicator(n) {
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) { x[i].className = x[i].className.replace(" active", ""); }
    x[n].className += " active";
}
</script>
<script>
    $( function() {
      $( "#draggable, #draggable1, #draggable2, #draggable3, #draggable4, #draggable5" ).draggable();
    } );
    </script>

</html>