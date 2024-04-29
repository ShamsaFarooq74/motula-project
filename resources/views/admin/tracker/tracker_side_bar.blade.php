<div class="col-xl-3">
    <div class="page_left_bar">
        <a href="{{route('tracker.list')}}" class="{{((Request::is('tracker-list') || (Request::is('tracker-profile'))) ? 'active' : '')}}" >
             Tracker Listing</a>
        <a href="{{route('create.tracker')}}" class="{{(Request::is('create-tracker') ? 'active' : '')}}"><img src="assets/images/Assets/create_tracker.svg" alt=""> Create Tracker</a>
        {{--{{\Route::getCurrentName()}}--}}
    </div>
</div>