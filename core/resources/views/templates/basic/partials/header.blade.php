<style>
p,span
{
    word-wrap: break-word !important;
}
@media screen and (max-width:992px)
{
  .inner-hero, footer,.nav-right
  {
    display: none;
  }
}
@media screen and (min-width:1095px)
{
  .navbar-collapse
  {
    width: 15%;
  }
}
@media screen and (min-width:573px) and (max-width:1094px)
{
  .navbar-collapse
  {
    width: 30%;
  }
}
@media screen and (max-width:572px)
{
  .navbar-collapse
  {
    width: 65%;
  }
}
@media screen and (min-width:1200px)
{
  .mob_drop
  {
    display: none;
  }
}
@media screen and (max-width:1199px)
{
    .header .main-menu li
    {
        border-bottom:none !important;
    }
    .logo_img
    {
        max-width: 8.938rem !important;
    }
  .navbar-toggler
  {
    display:flex;
  }
  .navbar-collapse {
  position: absolute;
  z-index: 11111 !important;
  top: 73%;
  right: 0;
  padding-left: 15px !important;
  padding-right: 15px !important;
  padding-bottom: 15px !important;
  height: 400px;
  width: auto;
  overflow-y: scroll;
}
  .navbar-collapse.collapsing {
    righr: -75%;
    transition: height 0s ease;
  }
  html
  {
    background: #071e3e;
  }
  .inner-hero, footer,.web_drop
  {
    display: none !important;
  }
  .mob_drop
  {
    display: block !important;
  }
}
.navbar-toggler.collapsed ~ .navbar-collapse {
  transition: right 500ms ease-in-out;
}
.dropbtn {
  background-color: transparent;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #14233c;
  min-width: 260px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 99999;
}

.dropdown-content a {
  color: white;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #10163A;}

.dropdown:hover .dropdown-content {display: block;}

.bar1, .bar2, .bar3 {
  width: 20px;
  height: 2px;
  background-color: #ffffff;
  margin: 6px 0;
  transition: 0.4s;
}

.change .bar1 {
  -webkit-transform: rotate(-45deg) translate(-9px, 6px);
  transform: rotate(-45deg) translate(-9px, 6px);
}

.change .bar2 {opacity: 0;}

.change .bar3 {
  -webkit-transform: rotate(45deg) translate(-8px, -8px);
  transform: rotate(45deg) translate(-8px, -8px);
}
.input_disabled
{
  pointer-events:none;
  color:#AAA;
  background:#F5F5F5;
}
.popover {
    z-index: 999999 !important;
}
.fc-content
{
  cursor: pointer !important;
}
a.hero__form-btn:hover{
  color: white !important;
}
a.hero__form-btn
{
  text-align: center !important;
}
.select2-container
{
  width: 100% !important;
}
</style>
<?php
$dhcemployer = auth()->guard('employer')->user();
$dhcuser = Auth::user();
?>
<header class="header">
  <div class="header__bottom">
    <div class="container">
      <nav id="navbar" class="navbar navbar-expand-xl p-0 align-items-center">
        <a class="site-logo site-title" href="{{route('home')}}"><img class="logo_img" src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('logo')"></a>
        <button class="navbar-toggler"  type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="menu-toggle" style="width:1.7rem;"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <span style="display:inline-flex;justify-content: center;">
            @if(auth()->user())
              <img src="{{getImage(imagePath()['profile']['user']['path'].'/'.@$dhcuser->image)}}" style="object-fit: cover;width: 35px;height: 35px;border-radius: 50%;position: relative;" alt="@lang('logo')">
              <label style="color: white;margin-top:10%;">&nbsp;{{ ucfirst(@$dhcuser->username) }}</label>
            @endif
            @if(auth()->guard('employer')->user())
              <img src="{{getImage(imagePath()['employerLogo']['path'].'/'.@$dhcemployer->image)}}" style="object-fit: cover;width: 35px;height: 35px;border-radius: 50%;position: relative;" alt="@lang('logo')">
              <label style="color: white;margin-top:10%;">&nbsp;{{ ucfirst(@$dhcemployer->username) }}</label>
            @endif
          </span>
        </button>
        <div class="collapse navbar-collapse mt-lg-0 mt-3" id="navbarSupportedContent"  style="padding-left:8%;">
          <!-- <ul class="navbar-nav main-menu m-auto"> -->

          <!-- </ul> -->
          <ul class="navbar-nav main-menu m-auto">
            <li><a href="{{route('home')}}">@lang('Home')</a></li>
            @foreach($pages as $k => $data)
                @if(!auth()->user() && !auth()->guard('employer')->user())
                    @if($data->slug == 'job' || $data->slug == 'clients')
                    @else
                      <li><a href="{{route('pages',[$data->slug])}}">{{__($data->name)}}</a></li>
                    @endif
                @else
                    <li><a href="{{route('pages',[$data->slug])}}">{{__($data->name)}}</a></li>
                @endif
            @endforeach
            @if(auth()->guard('employer')->user())
            <li class="mob_drop"><a href="{{route('employer.home')}}"><i class="las la-layer-group"></i> @lang('Dashboard')</a></li>
            <li class="mob_drop"><a href="{{route('employer.profile')}}"><i class="las la-edit"></i> @lang('Update Profile')</a></li>
            <li class="mob_drop"><a href="#" onclick="create_info();"><i class="las la-folder-plus"></i> @lang('Create Job')</a></li>
            <li class="mob_drop"><a href="{{route('employer.job.index')}}"><i class="las la-user-md"></i> @lang('All Jobs')</a></li>
            <li class="mob_drop"><a href="{{route('employer.deposit.history')}}"><i class="las la-wallet"></i> @lang('Deposit History')</a></li>
            <li class="mob_drop"><a href="{{route('employer.transaction.history')}}"><i class="las la-credit-card"></i> @lang('Transaction')</a></li>
            <li class="mob_drop"><a href="{{route('employer.change.password')}}"><i class="las la-lock-open"></i> @lang('Change Password')</a></li>
            <li class="mob_drop"><a href="{{route('ticket')}}"><i class="las la-envelope"></i> @lang('Get Support')</a></li>
            <li class="mob_drop"><a href="{{route('employer.twofactor')}}"><i class="las la-key"></i> @lang('2FA Security')</a></li>
            <li class="mob_drop"><a href="{{route('employer.logout')}}"><i class="las la-sign-out-alt"></i> @lang('Logout')</a></li>
            @endif
            @if(auth()->user())
            <li class="mob_drop"><a href="{{route('user.home')}}"><i class="las la-layer-group"></i> @lang('Dashboard')</a></li>
            <li class="mob_drop"><a href="{{route('user.profile.setting')}}"><i class="las la-edit"></i> @lang('Profile Update')</a></li>
            <li class="mob_drop"><a href="{{route('user.education.index')}}"><i class="las la-school"></i> @lang('Educational Qualification')</a></li>
            <li class="mob_drop"><a href="{{route('user.employment.index')}}"><i class="las la-landmark"></i> @lang('Employment History')</a></li>
            <li class="mob_drop"><a href="{{route('user.pdf.view')}}"><i class="lar la-file"></i> @lang('Credentials')</a></li>
            <li class="mob_drop"><a href="{{route('user.job.application.list')}}"><i class="las la-tablet-alt"></i> @lang('Job Applications')</a></li>
            <li class="mob_drop"><a href="{{route('user.job.currentjob')}}"><i class="las la-tablet-alt"></i> @lang('Current Jobs')</a></li>
            <li class="mob_drop"><a href="{{route('user.favorite.job.list')}}"><i class="lar la-bookmark"></i> @lang('Favourite Jobs')</a></li>
            <li class="mob_drop"><a href="{{route('user.change.password')}}"><i class="las la-lock-open"></i> @lang('Change Password')</a></li>
            <li class="mob_drop"><a href="{{route('user.twofactor')}}"><i class="las la-key"></i> @lang('2FA Security')</a></li>
            <li class="mob_drop"><a href="{{route('ticket')}}"><i class="las la-envelope"></i> @lang('Get Support')</a></li>
            <li class="mob_drop"><a href="{{route('user.logout')}}"><i class="las la-sign-out-alt"></i> @lang('Logout')</a></li>
            @endif
          </ul>
          <div class="nav-right">
            @if(!auth()->user() && !auth()->guard('employer')->user())
            <a href="{{route('login')}}" class="btn btn-md btn--base d-flex align-items-center"><i class="las la-user fs--18px me-2"></i> @lang('Login')</a>
            @else
            <div class="web_drop dropdown" style="display: flex;">
              <button class="dropbtn">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
              </button>
              <div style="margin-top: 10%;">
                @if(auth()->user())
                  <img src="{{getImage(imagePath()['profile']['user']['path'].'/'.@$dhcuser->image)}}" style="object-fit: cover;width: 35px;height: 35px;border-radius: 50%;position: relative;" alt="@lang('logo')">
                  <label style="color: white;">&nbsp;{{ ucfirst(@$dhcuser->username) }}</label>
                @endif
                @if(auth()->guard('employer')->user())
                  <img src="{{getImage(imagePath()['employerLogo']['path'].'/'.@$dhcemployer->image)}}" style="object-fit: cover;width: 35px;height: 35px;border-radius: 50%;position: relative;" alt="@lang('logo')">
                  <label style="color: white;">&nbsp;{{ ucfirst(@$dhcemployer->username) }}</label>
                @endif
              </div>
              <div class="dropdown-content" style="margin-top: 38%;">
                @if(auth()->guard('employer')->user())
                <a href="{{route('employer.home')}}"><i class="las la-layer-group"></i> @lang('Dashboard')</a>
                <a href="{{route('employer.profile')}}"><i class="las la-edit"></i> @lang('Update Profile')</a>
                <a href="#" onclick="create_info();"><i class="las la-folder-plus"></i> @lang('Create Job')</a>
                <a href="{{route('employer.job.index')}}"><i class="las la-user-md"></i> @lang('All Jobs')</a>
                <a href="{{route('employer.deposit.history')}}"><i class="las la-wallet"></i> @lang('Deposit History')</a>
                <a href="{{route('employer.transaction.history')}}"><i class="las la-credit-card"></i> @lang('Transaction')</a>
                <a href="{{route('employer.change.password')}}"><i class="las la-lock-open"></i> @lang('Change Password')</a>
                <a href="{{route('ticket')}}"><i class="las la-envelope"></i> @lang('Get Support')</a>
                <a href="{{route('employer.twofactor')}}"><i class="las la-key"></i> @lang('2FA Security')</a>
                <a href="{{route('employer.logout')}}"><i class="las la-sign-out-alt"></i> @lang('Logout')</a>
                @endif
                @if(auth()->user())
                <a href="{{route('user.home')}}"><i class="las la-layer-group"></i> @lang('Dashboard')</a>
                <a href="{{route('user.profile.setting')}}"><i class="las la-edit"></i> @lang('Profile Update')</a>
                <a href="{{route('user.education.index')}}"><i class="las la-school"></i> @lang('Educational Qualification')</a>
                <a href="{{route('user.employment.index')}}"><i class="las la-landmark"></i> @lang('Employment History')</a>
                <a href="{{route('user.pdf.view')}}"><i class="lar la-file"></i> @lang('Credentials')</a>
                <a href="{{route('user.job.application.list')}}"><i class="las la-tablet-alt"></i> @lang('Job Applications')</a>
                <a href="{{route('user.job.currentjob')}}"><i class="las la-tablet-alt"></i> @lang('Current Jobs')</a>
                <a href="{{route('user.favorite.job.list')}}"><i class="lar la-bookmark"></i> @lang('Favourite Jobs')</a>
                <a href="{{route('user.change.password')}}"><i class="las la-lock-open"></i> @lang('Change Password')</a>
                <a href="{{route('user.twofactor')}}"><i class="las la-key"></i> @lang('2FA Security')</a>
                <a href="{{route('ticket')}}"><i class="las la-envelope"></i> @lang('Get Support')</a>
                <a href="{{route('user.logout')}}"><i class="las la-sign-out-alt"></i> @lang('Logout')</a>
                @endif
              </div>
            </div>
            @endif
          </div>
        </div>
      </nav>
    </div>
  </div>
</header>
