<div class="site-menubar">
	<div class="site-menubar-body">
		<div>
			<div>
				<ul class="site-menu">
					<li class="site-menu-item active">
						<a href="/dashboard">
							<i class="site-menu-icon wb-dashboard" aria-hidden="true"></i>
							<span class="site-menu-title">Dashboard</span>
						</a>
					</li>

					@role(['admin', 'super_admin'])
					<li class="site-menu-item has-sub <?php if ($page === trans('users')) { echo "open active"; } ?>">
						<a href="javascript:void(0)">
							<i class="site-menu-icon wb-users" aria-hidden="true"></i>
							<span class="site-menu-title">{{ ucfirst(trans('users')) }}</span>
							<span class="site-menu-arrow"></span>
						</a>

						<ul class="site-menu-sub">
							<li class="site-menu-item <?php if ($subPage === trans('list')) { echo "active"; } ?>">
								<a class="animsition-link" href="/users">
									<span class="site-menu-title">{{ ucfirst(trans('list')) }}</span>
								</a>
							</li>
						</ul>
					</li>
					@endrole
					<li class="site-menu-item has-sub <?php if ($page === trans('translations')) { echo "open active"; } ?>">
						<a href="javascript:void(0)">
							<i class="site-menu-icon wb-globe" aria-hidden="true"></i>
							<span class="site-menu-title">{{ ucfirst(trans('translations')) }}</span>
							<span class="site-menu-arrow"></span>
						</a>

						<ul class="site-menu-sub">
							<li class="site-menu-item <?php if ($subPage === trans('list')) { echo "active"; } ?>">
								<a class="animsition-link" href="/translations">
									<span class="site-menu-title">{{ ucfirst(trans('list')) }}</span>
								</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="site-menubar-footer">
		<a href="javascript: void(0);" class="fold-show" data-placement="top" data-toggle="tooltip" data-original-title="Settings">
			<span class="icon wb-settings" aria-hidden="true"></span>
		</a>

		<a href="javascript: void(0);" data-placement="top" data-toggle="tooltip" data-original-title="Lock">
			<span class="icon wb-eye-close" aria-hidden="true"></span>
		</a>

		<a href="javascript: void(0);" data-placement="top" data-toggle="tooltip" data-original-title="Logout">
			<span class="icon wb-power" aria-hidden="true"></span>
		</a>
	</div>
</div>