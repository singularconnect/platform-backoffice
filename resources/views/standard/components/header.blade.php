<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega">
	
	<div class="navbar-header">
		<div class="navbar-brand navbar-brand-center">
			<img class="navbar-brand-logo" src="{{ elixir('images/logo.png') }}" title="Platform Backoffice">
			<span class="navbar-brand-text"> Platform Backoffice</span>
		</div>
	</div>

	<div class="navbar-container container-fluid">
		<!-- Navbar Collapse -->
		<div class="collapse navbar-collapse navbar-collapse-toolbar">

			<!-- Navbar Toolbar Right -->
			<ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
				<li class="dropdown">
					<a class="navbar-avatar dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
						<span class="avatar avatar-online">
							<img src="{{ elixir('images/avatar.jpg') }}">
							<i></i>
						</span>
						<span class="padding-horizontal-10" style="position: relative; top: -3px;">{{ Auth::user()->name }}</span>
					</a>

					<ul class="dropdown-menu" style="margin-top: 0px;">
						<li>
							<a href="/alterar/senha"><i class="icon wb-settings" aria-hidden="true"></i> Alterar Senha</a>
						</li>

						<li class="divider"></li>
						
						<li>
							<a href="/logout"><i class="icon wb-power" aria-hidden="true"></i> Logout</a>
						</li>
					</ul>
				</li>
			</ul>
			<!-- End Navbar Toolbar Right -->

		</div>
		<!-- End Navbar Collapse -->
	</div>
</nav>