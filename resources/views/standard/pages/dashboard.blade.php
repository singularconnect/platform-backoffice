@extends('standard::layouts.app')

@section('content')
	@include('standard::components.header')
	@include('standard::components.menu')
	
	<!-- Page -->
		<div class="page">
			<div class="page-header">
				<h1 class="page-title"> &nbsp {{ $pageTitle }}</h1>
			</div>

			<div class="page-content container-fluid">
				<div class="row">
					<div class="col-lg-3 col-sm-6 col-xs-12 info-panel">
						<div class="widget widget-shadow">
							<div class="widget-content bg-white padding-20">
								<button type="button" class="btn btn-floating btn-sm btn-warning">
									<i class="icon wb-shopping-cart"></i>
								</button>
								
								<span class="margin-left-15 font-weight-400">ORDERS</span>
								
								<div class="content-text text-center margin-bottom-0">
									<i class="text-danger icon wb-triangle-up font-size-20"></i>
									<span class="font-size-40 font-weight-100">399</span>
									<p class="blue-grey-400 font-weight-100 margin-0">+45% From previous month</p>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6 col-xs-12 info-panel">
						<div class="widget widget-shadow">
							<div class="widget-content bg-white padding-20">
								<button type="button" class="btn btn-floating btn-sm btn-danger">
									<i class="icon fa-dollar"></i>
								</button>

								<span class="margin-left-15 font-weight-400">INCOM</span>
								
								<div class="content-text text-center margin-bottom-0">
									<i class="text-success icon wb-triangle-down font-size-20"></i>
									<span class="font-size-40 font-weight-100">$18,628</span>
									<p class="blue-grey-400 font-weight-100 margin-0">+45% From previous month</p>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6 col-xs-12 info-panel">
						<div class="widget widget-shadow">
							<div class="widget-content bg-white padding-20">
								<button type="button" class="btn btn-floating btn-sm btn-success">
									<i class="icon wb-eye"></i>
								</button>

								<span class="margin-left-15 font-weight-400">VISITORS</span>
								
								<div class="content-text text-center margin-bottom-0">
									<i class="text-danger icon wb-triangle-up font-size-20"></i>
									<span class="font-size-40 font-weight-100">23,456</span>
									<p class="blue-grey-400 font-weight-100 margin-0">+25% From previous month</p>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6 col-xs-12 info-panel">
						<div class="widget widget-shadow">
							<div class="widget-content bg-white padding-20">
								<button type="button" class="btn btn-floating btn-sm btn-primary">
									<i class="icon wb-user"></i>
								</button>

								<span class="margin-left-15 font-weight-400">CUSTOMERS</span>
								
								<div class="content-text text-center margin-bottom-0">
									<i class="text-danger icon wb-triangle-up font-size-20"></i>
									<span class="font-size-40 font-weight-100">4,367</span>
									<p class="blue-grey-400 font-weight-100 margin-0">+25% From previous month</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- End Page -->

	@include('standard::components.footer')
@endsection

@push('css')
@endpush

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.9.3/vue-resource.min.js"></script>
<script>	
	Vue.config.devtools = true;

</script>
@endpush