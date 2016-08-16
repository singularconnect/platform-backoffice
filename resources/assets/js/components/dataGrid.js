Vue.component('app-grid', {
	template: `
		<div class="bootstrap-table">
			<div class="fixed-table-toolbar">
				<div class="bars pull-left">
					<div class="btn-group hidden-xs" id="exampleToolbar" role="group">
						<button type="button" class="btn btn-sm btn-success">
							<i class="icon wb-plus" aria-hidden="true"></i> Adicionar
						</button>
					</div>
				</div>

				<div class="columns columns-right btn-group pull-right">
					<button class="btn btn-default btn-outline" type="button" name="refresh" title="Refresh" @click.prevent="refreshData()">
						<i class="glyphicon wb-refresh"></i>
					</button>

					<input type="checkbox" v-model="check">
				</div>

				<div class="pull-right search">
					<input class="form-control input-outline" type="text" placeholder="Search" name="serchQuery" v-model="searchQuery">
				</div>
			</div>

			<div class="">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th v-for="(index, key) in getColumns('title')" @click="sortBy(index)" :class="[getColumns('align')[index], {'sortable': hasSort(index)}, {'active': sortKeyTitle === getColumns('title')[index]}]">
								{{ key | uppercase }} <span class="arrow" v-if="hasSort(index)" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'"></span>
							</th>
						</tr>
					</thead>

					<tbody>
						<tr v-for="entry in data | filterBy searchQuery | orderBy sortKey sortOrders[sortKeyTitle]">
							<td v-for="(index, key) in getColumns('data')" :class="getColumns('align')[index]">
								{{{ renderDefaultData(entry[key], index) }}}
							</td>
						</tr>
					</tbody>

					<tbody class="gridLoading" v-show="isLoading">
						<tr>
							<td colspan="100%">Loading data...</td>
						</tr>
					</tbody>

					<tbody class="gridLoadingError" v-show="isLoadingError">
						<tr>
							<td colspan="100%">Error data!</td>
						</tr>
					</tbody>
				</table>

				<div class="clearfix">
					<div class="entries pull-left">
						Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} entries
					</div>
					
					<nav>
						<ul class="pagination pagination-sm pull-right">
							<li :class="{ 'disabled' : pagination.current_page === 1 }">
								<a @click.prevent="prevPage()">
									<span aria-hidden="true">«</span>
								</a>
							</li>
							
							<li v-for="page_num in pagination.last_page" :class="{ 'active': (page_num+1) === pagination.current_page }">
								<a @click.prevent="changePage((page_num+1))">{{ (page_num+1) }}</a>
							</li>
							
							<li :class="{ 'disabled' : pagination.current_page === pagination.last_page }">
								<a @click.prevent="nextPage()">
									<span aria-hidden="true">»</span>
								</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	`,
	props: {
		data: Array,
		config: Object,
		pagination: Object,
		dataURL: String
	},
	data: function () {
		this.dataURL = this.config.dataURL + "?take="+this.config.rowsPerPage+"&orderby="+this.config.orderBy+"&direction="+this.config.orderDirection;

		var sortOrders = {};
		
		this.getColumns('title').forEach(function (key) {
			sortOrders[key] = 1;
		});
		
		return { check: false, isLoading: false, isLoadingError: false, searchQuery: '', sortKey: '', sortKeyTitle: '', sortOrders: sortOrders };
	},
	ready: function() {
		this.getData();
	},
	methods: {
		getData() {
			this.isLoadingError = false;
			this.isLoading = true;

			this.$http.get(this.dataURL).then((response) => {
				this.$set('data', response.json().data);
				
				this.setPaginationData(response.json());

				this.isLoading = false;
			}, (response) => {
				this.isLoading = false;
				this.isLoadingError = true;
			});
		},
		setPaginationData(data) {
			let pagination = {
				total: data.total,
				from: data.from,
				to: data.to,
				current_page: data.current_page,
				last_page: data.last_page,
				next_page_url: data.next_page_url,
				prev_page_url: data.prev_page_url,
			};

			this.$set('pagination', pagination);
		},
		refreshData() {
			this.$set('data', []);
			this.getData();
		},
		changePage(page_num) {
			this.dataURL = this.config.dataURL + "?take="+this.config.rowsPerPage+"&orderby="+this.config.orderBy+"&direction="+this.config.orderDirection + "&page=" + page_num;
			this.refreshData();
		},
		prevPage() {
			if (this.pagination.current_page === 1)
				return false;

			this.dataURL = this.pagination.prev_page_url;
			this.refreshData();
		},
		nextPage() {
			if (this.pagination.current_page === this.pagination.last_page)
				return false;

			this.dataURL = this.pagination.next_page_url;
			this.refreshData();
		},
		getColumns(column) {
			let array = [];

			this.config.columnsConfig.forEach(function (key) {
				array.push(key[column]);
			});

			return array;
		},
		sortBy(index) {
			if (!this.getColumns('sort')[index])
				return false;

			let key = this.getColumns('title')[index];
			
			this.sortOrders[key] = this.sortOrders[key] * -1;
			this.sortKeyTitle = this.getColumns('title')[index];
			
			if (this.check) {
				if (this.sortOrders[key] > 0) {
					this.dataURL = "/api/users?take="+this.config.rowsPerPage+"&orderby="+this.getColumns('data')[index]+"&direction=asc";
				} else {
					this.dataURL = "/api/users?take="+this.config.rowsPerPage+"&orderby="+this.getColumns('data')[index]+"&direction=desc";
				}
				
				this.refreshData();
			} else {
				this.sortKey = this.getColumns('data')[index];
			}
		},
		hasSort(index) {
			return this.getColumns('sort')[index];
		},
		renderDefaultData(data, index) {
			if (typeof this.getColumns('render')[index] === 'function')
				return this.getColumns('render')[index](data);
			
			return data;
		}
	}
});