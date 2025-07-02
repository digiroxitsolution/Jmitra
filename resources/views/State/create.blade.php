<div class="modal-dialog modal-dialog-centered modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Add State</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						  <form class="p-4">
						  	<meta name="csrf-token" content="{{ csrf_token() }}">
							<div class="row mb-4">
								<div class="col-12 mb-3">
									<label for="state_name" class="form-label">State Name<span class="text-danger">*</span>:</label>
									<input class="form-control" id="state_name" name="state_name" rows="5">
								</div>

								<div class="col-12 mb-3">
									<label for="short_name" class="form-label">State Short Name<span class="text-danger">*</span>:</label>
									<input class="form-control" id="short_name" name="short_name" rows="5">
								</div>
								<div class="col-12 mb-3">
									<label for="zone_id" class="form-label">Select Zone <span class="text-danger">*</span>:</label>
									<select class="form-select" id="zone_id" name="zone_id" required>
									  <option value="">-- Select Zone --</option>
									  @foreach($zones as $zone)
										<option value="{{ $zone->id }}">{{ $zone->zone_name }}</option>
									  @endforeach
									</select>
								  </div>
							</div>
						  </form>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						  <button type="button" class="btn btn-primary" onclick="CreateState()">Add State</button>
						</div>
					  </div>
					</div>