<!-- The Modal -->
<div class="modal fade" id="modal-edit">
	<div class="modal-dialog">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Listing Details</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<form id="form-edit" action="{{ route('listings.item', $listing->id) }}" method="POST">
					@method('PATCH')
					<div class="form-group">
						<label for="list_name">List Name:</label>
						<input type="text" class="form-control" id="list_name" name="list_name" required value="{{ $listing->list_name }}">
					</div>
					<div class="form-group">
						<label for="address">Address:</label>
						<input type="text" class="form-control" id="address" name="address" required value="{{ $listing->address }}">
					</div>
					<div class="form-group">
						<label for="latitude">Latitude:</label>
						<input type="text" class="form-control" id="latitude" name="latitude" required value="{{ $listing->latitude }}">
					</div>
					<div class="form-group">
						<label for="longitude">Longitude:</label>
						<input type="text" class="form-control" id="longitude" name="longitude" required value="{{ $listing->longitude }}">
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