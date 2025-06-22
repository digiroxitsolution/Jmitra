<div class="modal-dialog modal-dialog-centered modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Add Way of Location</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						  <form class="p-4">
						  	<meta name="csrf-token" content="{{ csrf_token() }}">
							<div class="row mb-4">
								<div class="col-12 mb-3">
									<label for="way_of_location" class="form-label">Way of Location</label>
									<textarea class="form-control" rows="5" id="way_of_location" name="way_of_location"></textarea>
								</div>
							</div>
						  </form>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						  <button type="button" class="btn btn-primary" onclick="CreateWayOfLocation()">Add Way Of Location</button>
						</div>
					  </div>
					</div>