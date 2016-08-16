@extends('standard::layouts.signin')

@section('content')
	<div class="page vertical-align text-center">
		<div class="page-content vertical-align-middle">
			<div class="panel">
				<div class="panel-body">
		
					<div class="brand">
						<img class="brand-img" src="{{ elixir('images/logo-blue.png') }}">
						<h2 class="brand-text font-size-18">BackOffice SB</h2>
					</div>

					{{ Form::open(array('route' => 'signin.post')) }}
						
						@if(Session::has('error'))
							<p class='error'>{{ Session::get('error') }}</p>
						@endif

						<div class="form-group">
						@if (count($errors) > 0)
							@foreach ($errors->all() as $error)
							<div class="alert dark alert-alt alert-danger alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
								{{ $error }}
							</div>
							@endforeach
						@endif
						</div>

						<div class="form-group form-material floating">
							{{ Form::email('email', '', array('class' => 'form-control', 'autocomplete' => 'off')) }}
							<label class="floating-label">Email</label>
						</div>

						<div class="form-group form-material floating">
							{{ Form::password('password', array('class' => 'form-control')) }}
							<label class="floating-label">Password</label>
						</div>

						<div class="form-group clearfix">
							<div class="checkbox-custom checkbox-inline checkbox-primary checkbox-lg pull-left">
								{{ Form::checkbox('remember', true, false, array('id' => 'checkboxRemember')) }}
								<label for="checkboxRemember">Remember me</label>
							</div>
							<a class="pull-right" href="/">Forgot password?</a>
						</div>
						
						{{ Form::submit('Sign-in', array('class' => 'btn btn-primary btn-block btn-lg margin-top-40')) }}
					{{ Form::close() }}
				</div>
			</div>
			
			<footer class="page-copyright page-copyright-inverse">
				<p>© {{ date("Y") }}. SingularBet BackOffice.</p>
			</footer>
		</div>
	</div>
@endsection

@push('css')
@endpush

@push('script')
@endpush