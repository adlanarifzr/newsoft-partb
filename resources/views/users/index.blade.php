@extends('layouts.app')
@include('plugins.datatables')

@section('content')
<div class="container">
	<div class="card">
		<div class="card-header">
			<div class="pull-left">
				List of Users
			</div>
			<div class="pull-right">
				<button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add"><i class="fa fa-plus mr-1"></i> New User</button>
			</div>
		</div>
		<div class="card-body table-responsive">
			<table id="table" class="table table-striped w-100">
				<thead>
					<tr>
						<th scope="col" class="fit">#</th>
						<th scope="col">Name</th>
						<th scope="col">Email</th>
						<th scope="col" class="fit">Type</th>
						<th scope="col" class="fit">Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
@endsection

@push('modal')
<!-- The Modal -->
<div class="modal fade" id="modal-add">
	<div class="modal-dialog">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">New User</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<form id="form-add" action="{{ request()->fullUrl() }}" method="POST">
					<div class="form-group">
						<label for="name">Full Name:</label>
						<input type="text" class="form-control" id="name" name="name" required>
					</div>
					<div class="form-group">
						<label for="email">Email address:</label>
						<input type="email" class="form-control" id="email" name="email" required>
					</div>
					<div class="form-group">
						<label for="password">Password:</label>
						<input type="password" class="form-control" id="password" name="password" required minlength="8">
					</div>
					<div class="form-group">
						<label for="password_confirmation">Confirm Password:</label>
						<input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="8">
					</div>
					<div class="form-group">
						<label for="type">Type:</label>
						<select class="form-control" id="type" name="type" required>
							<option selected hidden disabled>-- Choose one --</option>
							<option value="a">Administrator</option>
							<option value="u">User</option>
						</select>
					</div>
				</form>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="submit" data-submit="form-add" class="btn btn-success"><i class="fa fa-check mr-1"></i> Submit</button>
			</div>

		</div>
	</div>
</div>
@endpush

@push('js')
<script type="text/javascript">
	$('form').validate();

	var table = $('#table');
	var settings = {
		"processing": true,
		"serverSide": true,
		"deferRender": true,
		"ajax":"{{ request()->fullUrl() }}",
		"columns": [
			{ data: 'index', defaultContent: '', orderable: false, searchable: false, render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			}},
			{ data: "name", name: "name", render: function(data, type, row){
				return $("<div/>").html(data).text();
			}},
			{ data: "email", name: "email", render: function(data, type, row){
				return $("<div/>").html(data).text();
			}},
			{ data: "type", name: "type", render: function(data, type, row){
				return $("<div/>").html(data).text();
			}},
			{ data: "action", name: "action", searchable: false, orderable: false },
		],
		"columnDefs": [
			{ className: "nowrap", "targets": [4] },
		],
		"order": [
			[ 1, 'asc' ],
		],
		"sDom": "<'row'<l f>><t><'row'<i p>>",
		"destroy": true,
		"scrollCollapse": true,
		"iDisplayLength": 10
	};

	table.dataTable(settings);

	$("#form-add").on('submit', function(e) {
		e.preventDefault();
		var form = $(this);

		if(!form.valid()) {
			return;
		}

		$.ajax({
			url: form.attr('action'),
			method: form.attr('method'),
			data: new FormData(form[0]),
			dataType: 'json',
			async: true,
			contentType: false,
			processData: false,
			success: function(data) {
				Swal.fire(data.title, data.message, data.status);
				if(data.status == 'success') {
					table.api().ajax.reload(null, false);
					$('#modal-add').modal('hide');
					form.trigger('reset');
				}
			},
			error: function() {
				Swal.fire('Unexpected Error', 'The data cannot be sent. Please check your input.', 'error');
			}
		});
	});

	function edit(id) {
		$("#div-modal").load('{{ request()->fullUrl() }}/'+id);
	}

    function remove(id) {
		Swal.fire({
			title: "Confirm Remove?",
			text: "Any deleted data would not be recoverable. Proceed?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Remove',
			showLoaderOnConfirm: true,
			allowOutsideClick: () => !Swal.isLoading(),
		}).then((confirm) => {
			if(confirm.value) {
				$.ajax({
					url: '{{ request()->fullUrl() }}/'+id,
					method: 'delete',
					dataType: 'json',
					async: true,
					contentType: false,
					processData: false,
					success: function(data) {
						Swal.fire(data.title, data.message, data.status);
						table.api().ajax.reload(null, false);
					},
					error: function() {
						Swal.fire('Unexpected Error', 'The data cannot be sent. Please check your input.', 'error');
					}
				});
			}
		});
    }
</script>
@endpush