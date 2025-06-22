<div class="modal-dialog modal-dialog-centered modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Edit</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						  <form class="p-4">
						  	<meta name="csrf-token" content="{{ csrf_token() }}">
							<div class="row mb-4">
								<div class="col-12 mb-lg-0 mb-3">
									<label for="state_id" class="form-label">State<span class="text-danger">*</span>:</label>
									<select id="state_id" name="state_id" class="form-control">
									    <option value="">Select</option>
									    @foreach ($states as $state)
									        <option value="{{ $state->id }}" {{ $state->id == old('state_id', $city->state_id) ? 'selected' : '' }}>
									            {{ $state->name }}
									        </option>
									    @endforeach
									</select>
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-6 mb-lg-0 mb-3">
								   <label for="name" class="form-label">City<span class="text-danger">*</span>:</label>
								   <input type="text"  class="form-control" id="name" name="name" placeholder="Delhi">
								</div>
								
							</div>
						  </form>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						  <button type="button" class="btn btn-primary" onclick="updateCity(selectedCityId)">Update City</button>
						</div>
					  </div>
					</div>