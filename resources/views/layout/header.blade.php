<nav class="navbar">
  <a href="#" class="sidebar-toggler">
    <i data-feather="menu"></i>
  </a>
  <div class="navbar-content">
    <form class="search-form">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i data-feather="search"></i>
          </div>
        </div>
        <input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
      </div>
    </form>
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="flag-icon flag-icon-us mt-1" title="us"></i> <span class="font-weight-medium ml-1 mr-1">English</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="languageDropdown">
          <a href="javascript:;" class="dropdown-item py-2"><i class="flag-icon flag-icon-us" title="us" id="us"></i> <span class="ml-1"> English </span></a>
          <a href="javascript:;" class="dropdown-item py-2"><i class="flag-icon flag-icon-fr" title="fr" id="fr"></i> <span class="ml-1"> French </span></a>
          <a href="javascript:;" class="dropdown-item py-2"><i class="flag-icon flag-icon-de" title="de" id="de"></i> <span class="ml-1"> German </span></a>
          <a href="javascript:;" class="dropdown-item py-2"><i class="flag-icon flag-icon-pt" title="pt" id="pt"></i> <span class="ml-1"> Portuguese </span></a>
          <a href="javascript:;" class="dropdown-item py-2"><i class="flag-icon flag-icon-es" title="es" id="es"></i> <span class="ml-1"> Spanish </span></a>
        </div>
      </li>
      <li class="nav-item dropdown nav-apps">
        <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i data-feather="grid"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="appsDropdown">
          <div class="dropdown-header d-flex align-items-center justify-content-between">
            <p class="mb-0 font-weight-medium">Web Apps</p>
            <a href="javascript:;" class="text-muted">Edit</a>
          </div>
          <div class="dropdown-body">
            <div class="d-flex align-items-center apps">
              <a href="{{ url('/apps/chat') }}"><i data-feather="message-square" class="icon-lg"></i><p>Chat</p></a>
              <a href="{{ url('/apps/calendar') }}"><i data-feather="calendar" class="icon-lg"></i><p>Calendar</p></a>
              <a href="{{ url('/email/inbox') }}"><i data-feather="mail" class="icon-lg"></i><p>Email</p></a>
              <a href="{{ url('/general/profile') }}"><i data-feather="instagram" class="icon-lg"></i><p>Profile</p></a>
            </div>
          </div>
          <div class="dropdown-footer d-flex align-items-center justify-content-center">
            <a href="javascript:;">View all</a>
          </div>
        </div>
      </li>
      <li class="nav-item dropdown nav-messages">
        <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i data-feather="mail"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="messageDropdown">
          <div class="dropdown-header d-flex align-items-center justify-content-between">
            <p class="mb-0 font-weight-medium">9 New Messages</p>
            <a href="javascript:;" class="text-muted">Clear all</a>
          </div>
          <div class="dropdown-body">
            <a href="javascript:;" class="dropdown-item">
              <div class="figure">
                <img src="{{ url('/image/cccccccccccc.png') }}" alt="user">
              </div>
              <div class="content">
                <div class="d-flex justify-content-between align-items-center">
                  <p>Leonardo Payne</p>
                  <p class="sub-text text-muted">2 min ago</p>
                </div>
                <p class="sub-text text-muted">Project status</p>
              </div>
            </a>
            <a href="javascript:;" class="dropdown-item">
              <div class="figure">
                <img src="{{ url('https://via.placeholder.com/30x30') }}" alt="userr">
              </div>
              <div class="content">
                <div class="d-flex justify-content-between align-items-center">
                  <p>Carl Henson</p>
                  <p class="sub-text text-muted">30 min ago</p>
                </div>
                <p class="sub-text text-muted">Client meeting</p>
              </div>
            </a>
            <a href="javascript:;" class="dropdown-item">
              <div class="figure">
                <img src="{{ url('https://via.placeholder.com/30x30') }}" alt="userr">
              </div>
              <div class="content">
                <div class="d-flex justify-content-between align-items-center">
                  <p>Jensen Combs</p>
                  <p class="sub-text text-muted">1 hrs ago</p>
                </div>
                <p class="sub-text text-muted">Project updates</p>
              </div>
            </a>
            <a href="javascript:;" class="dropdown-item">
              <div class="figure">
                <img src="{{ url('https://via.placeholder.com/30x30') }}" alt="userr">
              </div>
              <div class="content">
                <div class="d-flex justify-content-between align-items-center">
                  <p>Amiah Burton</p>
                  <p class="sub-text text-muted">2 hrs ago</p>
                </div>
                <p class="sub-text text-muted">Project deadline</p>
              </div>
            </a>
            <a href="javascript:;" class="dropdown-item">
              <div class="figure">
                <img src="{{ url('https://via.placeholder.com/30x30') }}" alt="userr">
              </div>
              <div class="content">
                <div class="d-flex justify-content-between align-items-center">
                  <p>Yaretzi Mayo</p>
                  <p class="sub-text text-muted">5 hr ago</p>
                </div>
                <p class="sub-text text-muted">New record</p>
              </div>
            </a>
          </div>
          <div class="dropdown-footer d-flex align-items-center justify-content-center">
            <a href="javascript:;">View all</a>
          </div>
        </div>
      </li>
      <li class="nav-item dropdown nav-notifications">
        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i data-feather="bell"></i>
          <div class="indicator">
            <div class="circle"></div>
          </div>
        </a>
        <div class="dropdown-menu" aria-labelledby="notificationDropdown">
          <div class="dropdown-header d-flex align-items-center justify-content-between">
            <p class="mb-0 font-weight-medium">6 New Notifications</p>
            <a href="javascript:;" class="text-muted">Clear all</a>
          </div>
          <div class="dropdown-body">
            <a href="javascript:;" class="dropdown-item">
              <div class="icon">
                <i data-feather="user-plus"></i>
              </div>
              <div class="content">
                <p>New customer registered</p>
                <p class="sub-text text-muted">2 sec ago</p>
              </div>
            </a>
            <a href="javascript:;" class="dropdown-item">
              <div class="icon">
                <i data-feather="gift"></i>
              </div>
              <div class="content">
                <p>New Order Recieved</p>
                <p class="sub-text text-muted">30 min ago</p>
              </div>
            </a>
            <a href="javascript:;" class="dropdown-item">
              <div class="icon">
                <i data-feather="alert-circle"></i>
              </div>
              <div class="content">
                <p>Server Limit Reached!</p>
                <p class="sub-text text-muted">1 hrs ago</p>
              </div>
            </a>
            <a href="javascript:;" class="dropdown-item">
              <div class="icon">
                <i data-feather="layers"></i>
              </div>
              <div class="content">
                <p>Apps are ready for update</p>
                <p class="sub-text text-muted">5 hrs ago</p>
              </div>
            </a>
            <a href="javascript:;" class="dropdown-item">
              <div class="icon">
                <i data-feather="download"></i>
              </div>
              <div class="content">
                <p>Download completed</p>
                <p class="sub-text text-muted">6 hrs ago</p>
              </div>
            </a>
          </div>
          <div class="dropdown-footer d-flex align-items-center justify-content-center">
            <a href="javascript:;">View all</a>
          </div>
        </div>
      </li>
      <li class="nav-item dropdown nav-profile">
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="{{ url('/assets/images/users/register.png') }}" width="10" height="30" alt="profile">
        </a>
        <div class="dropdown-menu" aria-labelledby="profileDropdown">
          <div class="dropdown-header d-flex flex-column align-items-center">
            <div class="figure mb-3">
              <img src="{{ url('/assets/images/users/register.png') }}" width="30" height="50" alt="">
            </div>
            <div class="info text-center">
              <p class="name font-weight-bold mb-0">{{auth()->user()->name}}</p>
              <p class="email text-muted mb-3">{{auth()->user()->email}}</p>
            </div>
          </div>
          <div class="dropdown-body">
            <ul class="profile-nav p-0 pt-3">
              <li class="nav-item">
                <a href="{{ url('/general/profile') }}" class="nav-link">
                  <i data-feather="user"></i>
                  <span>Profile</span>
                </a>
              </li>
              <li class="nav-item">
              <a class="nav-link" data-toggle="modal" id="mediumButton2" data-target="#mediumModal" data-attr="{{auth()->user()->id}}/attendexam" alt="{{auth()->user()->id}}" title="Click to edit profile">
                <i data-feather="edit"></i>
                  <span>Edit Profile</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:;" class="nav-link">
                  <i data-feather="repeat"></i>
                  <span>Switch User</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('logout') }}" method="get" class="nav-link">
                  <i data-feather="log-out"></i>
                  <span>Log Out</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </li>
    </ul>
  </div>
</nav>




<!-- Before start Exam Student Fill necessary info  -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelHeading">Edit Users Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                  <form id="exam-register-form">
                  @csrf

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Change&nbsp;Password</label>
                            <div class="col-sm-12">
                                <label for="name" class="col-sm-2 control-label">Enter&nbsp;new&nbsp;Password</label>
                                <input type="text" class="form-control" id="password1" name="password1" placeholder="Enter New Password ...... " value="" maxlength="50" required>
                                <label for="name" class="col-sm-2 control-label">ReType&nbsp;new&nbsp;Password</label>
                                <input type="text" class="form-control" id="password2" name="password2" placeholder="Retype New Password ...... " value="" maxlength="50" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Edit&nbsp;Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter New Name .... " value="{{auth()->user()->name}}" maxlength="50" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Edit&nbsp;Email</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="emailid" name="emailid" placeholder="Enter New Email Id ...." value="{{auth()->user()->email}}" maxlength="50" required="">
                            </div>
                        </div>

                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="submitbtn" value="create">Confirm Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Ends Before start Exam Student Fill necessary info  -->

@push('custom-scripts')
<script>

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Submit button
$(document).on('click', '#submitbtn', function(e) {
    e.preventDefault();

    var form6 = $('#exam-register-form').serializeArray();
    // alert(form6[5].value);
    console.log(form6);
    // return false;

    if(form6[1].value == form6[2].value) {
        alert("Password Matched");
        // return false;

        $.ajax({
            url: "{{ url('/updateprofile') }}", // Replace with your API endpoint URL
            type: "POST",
            data: form6,
            // contentType: "application/json", // Type of data you're sending (e.g., JSON)
            success: function(response) {
                // Handle the successful response from the server
                console.log("Success:", response);
                alert("Profile Updated Successfully .....");
            },
            error: function(error) {
                // Handle errors during the request
                console.error("Error:", error);
            }

        });

    } else {
        alert("Password Not Matched");
        return false;
    }

});

</script>
@endpush
