@extends('standard::layouts.app')

@section('content')
	@include('standard::components.header')
	@include('standard::components.menu')
	
	<!-- Page -->
		<div id="users" class="page">
			<div class="page-header">

				<h1 class="page-title"> &nbsp {{ $pageTitle }}</h1>
				
				<div class="page-header-actions">
					<div class="row no-space width-250 hidden-xs">
						<div class="col-xs-4">
						</div>
						
						<div class="col-xs-4">
						</div>

						<div class="col-xs-4">
							<div class="counter">
								<span class="counter-number font-weight-medium">6</span>
								<div class="counter-label">{{ trans('translations') }}</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Page Content -->
			<div class="page-content container-fluid">
				<!-- Panel Api -->
				<div class="panel">
					<div class="panel-body">
						<app-grid :config="dataGrid"></app-grid>
					</div>
				</div>
			</div>
			<!-- End Page Content -->
		</div>
	<!-- End Page -->

	@include('standard::components.footer')
@endsection

@push('css')
@endpush

@push('script')

<script src="{{ elixir('js/components/dataGrid.js') }}"></script>
<script>
	Vue.config.devtools = true;

	let vmUsers = new Vue({
		el: '#users',
		data: {
			dataGrid: {
				dataURL: '/api/users',
				rowsPerPage: 3,
				orderBy: 'name', 
				orderDirection: 'asc', 
				columnsConfig: [
					{ 
						data:  'name', 
						title: 'Nome', 
						align: 'text-left', 
						sort:  true
					},
					{ 
						data:  'email', 
						title: 'Email', 
						align: 'text-left', 
						sort:  true
					},
					{ 
						data:  'role_ids', 
						title: 'Tipo', 
						align: 'text-center', 
						sort:  true
					},
					{ 
						data:  'created_at', 
						title: 'Criado em', 
						align: 'text-center', 
						sort:  true
					},
					{ 
						data:  'id', 
						title: 'Ações', 
						align: 'text-center', 
						sort:  false,
						render: function(data) {
							return `
								<a href="/edit/${data}" class="btn btn-pure btn-info btn-xs icon wb-order"></a>
								<a href="/edit/${data}" class="btn btn-pure btn-primary btn-xs icon wb-pencil"></a>
								<a href="/delete/${data}" class="btn btn-pure btn-danger btn-xs icon wb-close"></a>
							`;
						}
					}
				]
			}
		},
		ready() {
			//
		},
		methods: {
			//
		}
	});
</script>
@endpush