<div class="col-xl-3 col-lg-6 col-md-8">
	<div class="dashboard-sidebar">
		<div class="dashboard-sidebar__thumb">
			<img src="{{getImage(imagePath()['employerLogo']['path'].'/'.auth()->guard('employer')->user()->image)}}" alt="@lang('image')" class="w-100">
		</div>
		<div class="text-center">
			<h5 class="mt-3">{{auth()->guard('employer')->user()->email}}</h5>
			<span class="text--base">{{auth()->guard('employer')->user()->username}}</span>
		</div>
		<ul class="dashboard-sidebar__menu mt-3">
			<li class="{{request()->routeIs('employer.home')?'active':''}}">
				<a href="{{route('employer.home')}}"><i class="las la-layer-group"></i> @lang('Dashboard')</a>
			</li>
			<li class="{{request()->routeIs('employer.profile')?'active':''}}">
				<a href="{{route('employer.profile')}}"><i class="las la-edit"></i> @lang('Update Profile')</a>
			</li>
			<li class="{{request()->routeIs('employer.job.create')?'active':''}}">
				<a href="#" onclick="create_info();"><i class="las la-folder-plus"></i> @lang('Create Job')</a>
			</li>
			<li class="{{request()->routeIs('employer.job.index')?'active':''}}">
				<a href="{{route('employer.job.index')}}"><i class="las la-user-md"></i> @lang('All Job')</a>
			</li>
			<li class="{{request()->routeIs('employer.deposit.history')?'active':''}}">
				<a href="{{route('employer.deposit.history')}}"><i class="las la-wallet"></i> @lang('Deposit History')</a>
			</li>
			<li class="{{request()->routeIs('employer.transaction.history')?'active':''}}">
				<a href="{{route('employer.transaction.history')}}"><i class="las la-credit-card"></i> @lang('Transaction')</a>
			</li>
			<li class="{{request()->routeIs('employer.change.password')?'active':''}}">
				<a href="{{route('employer.change.password')}}"><i class="las la-lock-open"></i> @lang('Change Password')</a>
			</li>

			<li class="{{request()->routeIs('ticket')?'active':''}}">
				<a href="{{route('ticket')}}"><i class="las la-envelope"></i> @lang('Get Support')</a>
			</li>

			<li class="{{request()->routeIs('employer.twofactor')?'active':''}}">
				<a href="{{route('employer.twofactor')}}"><i class="las la-key"></i> @lang('2FA Security')</a>
			</li>
			<li>
				<a href="{{route('employer.logout')}}"><i class="las la-sign-out-alt"></i> @lang('Logout')</a>
			</li>
		</ul>
	</div>
</div>

