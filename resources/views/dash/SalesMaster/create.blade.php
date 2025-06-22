<div class="modal-dialog modal-dialog-centered modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Add Sales Master</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						  <form class="p-4">
						  	<meta name="csrf-token" content="{{ csrf_token() }}">
							<div class="row mb-4">
								<div class="col-12 mb-lg-0 mb-3">
									<label for="state_ids" class="form-label">State<span class="text-danger">*</span>:</label>
									<select name="state_ids" id="state_ids" class="form-select">
										<option value="">Select State</option>
										@foreach ($states as $state)
											
											<option value="{{ $state->id }}">{{ $state->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-6 mb-lg-0 mb-3">
								   <label for="file_names" class="form-label">Sales Ammount<span class="text-danger">*</span>:</label>
								   <input type="number"  class="form-control" id="file_names" name="file_names" placeholder="0.0"  required>
								</div>
								<div class="col-6 mb-lg-0 mb-3">
									<label for="date_of_uploads" class="form-label">Date of Sales:</label>
									<input type="date" class="form-control" id="date_of_uploads" name="date_of_uploads" placeholder="Date of Sales"  required>
								 </div>
							</div>
						  </form>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						  <button type="button" class="btn btn-primary" onclick="CreateSalesMAster()">Add Sales</button>
						</div>
					  </div>
					</div>