<div class="sidebar sidebar-style-2">
	<div class="sidebar-wrapper scrollbar scrollbar-inner">
		<div class="sidebar-content">
			<div class="user">
				<div class="avatar-sm float-left mr-2">
					<img src="{{ asset('storage/'.auth()->user()->avator) ?? asset('assets/img/default/avator.png') }}" alt="..." class="avatar-img rounded-circle">
				</div>
				<div class="info">
					<a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
						<span>
							{{ Auth()->user()->fname}}
							<span class="user-level"> {{ auth()->user()->roles->pluck('name')[0] ?? '' === 'User' }} </span>
							<span class="caret"></span>
						</span>
					</a>
					<div class="clearfix"></div>

					<div class="collapse in" id="collapseExample">
						<ul class="nav">
							<li>
								<a href="{{ route('users.show', auth()->id()) }}">
									<span class="link-collapse">My Profile</span>
								</a>
							</li>
							<li>
								<a href="{{ route('users.edit', auth()->id()) }}">
									<span class="link-collapse">Edit Profile</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<ul class="nav nav-primary">
				<li class="nav-item @if(Route::currentRouteName() == 'cms') active @endif">
					<a href="#dashboard">
						<i class="fas fa-home"></i>
						<p>Dashboard</p>
					</a>
				</li>
				<li class="nav-section">
					<span class="sidebar-mini-icon">
						<i class="fa fa-ellipsis-h"></i>
					</span>
					<h4 class="text-section">Modules</h4>
					<hr>
				</li>

				@if(auth()->user()->hasAnyRole(['admin', 'superadmin']))
				<li class="nav-item @if(Route::is('users.*')) active @endif">
					<a href="{{ route('users.index') }}">
						<i class="icon-user-follow"></i>
						<p>Users</p>
					</a>
				</li>
				@endif


				<li class="nav-item @if(Route::is('groups.*')) active @endif">
					<a href="{{ route('groups.index') }}">
						<i class="far icon-layers"></i>
						<p> Groups </p>
					</a>
				</li>


				<li class="nav-item @if(Route::is('members.*')) active @endif">
					<a href="{{ route('members.index') }}">
						<i class="icon-people "></i>
						<p> Members </p>
					</a>
				</li>


				<li class="nav-item @if(Route::is('events.*')) active @endif">
					<a href="{{ route('events.index') }}">
						<i class="icon-calendar"></i>
						<p> Events </p>
					</a>
				</li>

				<li class="nav-item @if(Route::is('eventAttendance.*')) active @endif">
					<a href="{{ route('eventAttendance.index') }}">
						<i class="icon-arrow-down-circle"></i>
						<p> Attendance </p>
					</a>
				</li>

				<li class="nav-item @if(Route::is('donations.*')) active @endif">
					<a href="{{ route('donations.index') }}">
						<i class="icon-present"></i>
						<p> Donations & Givings </p>
					</a>
				</li>


				<li class="nav-item @if(Route::is('announcements.*')) active @endif">
					<a href="{{ route('announcements.index') }}">
						<i class="icon-feed"></i>
						<p> Announcements </p>
					</a>
				</li>


				<!-- have posts routes, categories and posts -->
				 <li class="nav-item @if(Route::is('posts.*') || 
									Route::is('postCategories.*')  ) active 
									@endif">
					<a data-toggle="collapse" href="#submenu_post">
						<i class="icon-docs"></i>
						<p>Posts</p>
						<span class="caret"></span>
					</a>
					<div class="@if( Route::is('posts.*') || 
								     Route::is('postCategories.*') ) collapsed @else collapse @endif"
						id="submenu_post">
						<ul class="nav nav-collapse">
							<li class=" @if(Route::is('postCategories.*')) active @endif">
								<a href="{{ route('postCategories.index') }}">
									<span class="sub-item"> Categories </span>
								</a>
							</li>

							<li class=" @if(Route::is('posts.*')) active @endif">
								<a href="{{ route('posts.index') }}">
									<span class="sub-item"> Posts </span>
								</a>
							</li>
						</ul>
					</div>
				</li>

				

				<li class="nav-item @if(Route::is('reports.*')) active @endif">
					<a href="{{ route('reports.index') }}">
						<i class="icon-pie-chart"></i>
						<p> Reports</p>
					</a>
				</li>

				@if(auth()->user()->hasAnyRole(['admin', 'superadmin']))
				<li class="nav-item @if(Route::is('roles.*') || 
									Route::is('permissions.*') ||
									 Route::is('notifications.*') ) active 
									@endif">
					<a data-toggle="collapse" href="#submenu">
						<i class="fas fa-cog"></i>
						<p>System Parameters</p>
						<span class="caret"></span>
					</a>
					<div class="@if( Route::is('roles.*') || 
								     Route::is('permissions.*') ||
									 Route::is('notifications.*') ) collapsed @else collapse @endif"
						id="submenu">
						<ul class="nav nav-collapse">
							<li>
								<a data-toggle="collapse" href="#subnav2">
									<span class="sub-item">Authorization</span>
									<span class="caret"></span>
								</a>
								<div class="@if(Route::is('roles.*') || Route::is('permissions.*')) collapsed @else collapse @endif" id="subnav2">
									<ul class="nav nav-collapse subnav">
										<li class=" @if(Route::is('roles.*')) active @endif">
											<a href="{{ route('roles.index') }}">
												<span class="sub-item">Roles</span>
											</a>
										</li>

										<li class=" @if(Route::is('permissions.*')) active @endif">
											<a href="{{ route('permissions.index') }}">
												<span class="sub-item">Permissions</span>
											</a>
										</li>
									</ul>
								</div>
							</li>

							<li class=" @if(Route::is('notifications.*')) active @endif">
								<a href="{{ route('notifications.index') }}">
									<span class="sub-item">Notifications </span>
								</a>
							</li>

							<li>
								<a href="#">
									<span class="sub-item">Params</span>
								</a>
							</li>
						</ul>
					</div>
				</li>
				@endif

				<hr>
				<li class="nav-item">
					<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
						<i class="icon-logout"></i>
						<p>Logout</p>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>