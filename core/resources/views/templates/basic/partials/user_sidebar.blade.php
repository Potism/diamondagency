<div class="col-xl-3 col-lg-6 col-md-8">
	<div class="dashboard-sidebar">
		<div class="dashboard-sidebar__thumb">
			<img src="{{getImage(imagePath()['profile']['user']['path'].'/'. auth()->user()->image)}}" alt="@lang('image')" class="w-100">
		</div>
		<div class="text-center">
			<h5 class="mt-3">{{auth()->user()->email}}</h5>
			<span class="text--base">{{auth()->user()->username}}</span>
		</div>
		<ul class="dashboard-sidebar__menu mt-3">
			<li class="{{request()->routeIs('user.home')?'active':''}}">
				<a href="{{route('user.home')}}"><i class="las la-layer-group"></i> @lang('Dashboard')</a>
			</li>
			<li class="{{request()->routeIs('user.profile.setting')?'active':''}}">
				<a href="{{route('user.profile.setting')}}"><i class="las la-edit"></i> @lang('Profile Update')</a>
			</li>
			<li class="{{request()->routeIs('user.education.index')?'active':''}}">
				<a href="{{route('user.education.index')}}"><i class="las la-school"></i> @lang('Educational Qualification')</a>
			</li>
			<li class="{{request()->routeIs('user.employment.index')?'active':''}}">
				<a href="{{route('user.employment.index')}}"><i class="las la-landmark"></i> @lang('Employment History')</a>
			</li>
			<li class="{{request()->routeIs('user.pdf.view')?'active':''}}">
				<a href="{{route('user.pdf.view')}}"><i class="lar la-file"></i> @lang('View Credentials')</a>
			</li>
			<li class="{{request()->routeIs('user.job.application.list')?'active':''}}">
				<a href="{{route('user.job.application.list')}}"><i class="las la-tablet-alt"></i> @lang('Job Applications')</a>
			</li>
			<li class="{{request()->routeIs('user.job.currentjob')?'active':''}}">
				<a href="{{route('user.job.currentjob')}}"><i class="las la-cog-icon"></i> @lang('Current Job')</a>
			</li>
			<li class="{{request()->routeIs('user.favorite.job.list')?'active':''}}">
				<a href="{{route('user.favorite.job.list')}}"><i class="lar la-bookmark"></i> @lang('Favourite Job')</a>
			</li>
			<li class="{{request()->routeIs('user.statistics')?'active':''}}">
				<a href="{{route('user.statistics')}}"><i class="lar la-bookmark"></i> @lang('Your Statistics')</a>
			</li>
			<li class="{{request()->routeIs('user.change.password')?'active':''}}">
				<a href="{{route('user.change.password')}}"><i class="las la-lock-open"></i> @lang('Change Password')</a>
			</li>
			<li class="{{request()->routeIs('user.twofactor')?'active':''}}">
				<a href="{{route('user.twofactor')}}"><i class="las la-key"></i> @lang('2FA Security')</a>
			</li>
			<li class="{{request()->routeIs('ticket')?'active':''}}">
				<a href="{{route('ticket')}}"><i class="las la-envelope"></i> @lang('Get Support')</a>
			</li>
			<li>
				<a href="{{route('user.logout')}}"><i class="las la-sign-out-alt"></i> @lang('Logout')</a>
			</li>
		</ul>
	</div>
</div>
