<!-- The Modal -->
<div class="modal fade" id="modal-edit">
	<div class="modal-dialog">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">User Details</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<form id="form-edit" action="{{ route('users.item', $user->id) }}" method="POST">
					@method('PATCH')
					<div class="form-group">
						<label for="name">Full Name:</label>
						<input type="text" class="form-control" id="name" name="name" required value="{{ $user->name }}">
					</div>
					<div class="form-group">
						<label for="email">Email address:</label>
						<input type="email" class="form-control" id="email" name="email" required value="{{ $user->email }}">
					</div>
					<div class="form-group">
						<label for="password">New Password:</label>
						<input type="password" class="form-control" id="password" name="new_password" minlength="8">
					</div>
					<div class="form-group">
						<label for="password_confirmation">Confirm New Password:</label>
						<input type="password" class="form-control" id="password_confirmation" name="new_password_confirmation" minlength="8">
					</div>
					<div class="form-group">
						<label for="type_edit">Type:</label>
						<select class="form-control" id="type_edit" name="type" required>
							<option selected hidden disabled>-- Choose one --</option>
							<option value="a">Administrator</option>
							<option value="u">User</option>
						</select>
					</div>
				</form>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="submit" onclick="submitUpdate()" class="btn btn-success"><i class="fa fa-check mr-1"></i> Update</button>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	$("#type_edit").val("{{ $user->type }}").trigger("change");
	$("#modal-edit").modal("show");
	$("#form-edit").validate();

	function submitUpdate() {
		var form = $("#form-edit");

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
					$('#modal-edit').modal('hide');
				}
			},
			error: function() {
				Swal.fire('Unexpected Error', 'The data cannot be sent. Please check your input.', 'error');
			}
		});
	}
</script>