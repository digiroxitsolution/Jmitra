<div class="modal-dialog modal-dialog-centered modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Create Hod</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						  <form class="p-4">
						  	<meta name="csrf-token" content="{{ csrf_token() }}">
						  	<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
							<div class="row mb-4">
								<div class="col-12 mb-3">
									<label for="names" class="form-label">Hod Name<span class="text-danger">*</span>:</label>
									<input class="form-control" type="text" id="names" name="names" placeholder="Hod Name" required>
								</div>
								
							</div>
						  </form>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						  <button type="button" class="btn btn-primary" onclick="CreateHod()">Add Hod</button>
						</div>
					  </div>
					</div>