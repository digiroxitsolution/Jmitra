					<div class="modal-dialog modal-dialog-centered modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Update Company</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div id="cf-response-message"></div>
						  <form class="p-4" id="update-form">
						  	<meta name="csrf-token" content="{{ csrf_token() }}">
							<div class="row mb-4">
								<div class="col-12 mb-3">
									<label for="company_name" class="form-label">Company Name<span class="text-danger">*</span>:</label>
									<input type="text" class="form-control" id="company_names" name="company_names" placeholder="Company Name" required>
								</div>
								<div class="col-12 mb-3">
									<label for="location" class="form-label">Location<span class="text-danger">*</span>:</label>
									<input type="text" class="form-control" id="locations" name="locations" placeholder="Location">
								</div>
								<div class="col-12 mb-3">
									<label for="address" class="form-label">Company Address<span class="text-danger">*</span>:</label>
									<input type="text" class="form-control" id="addresss" name="addresss" placeholder="Company Address">
								</div>
							</div>
						  </form>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						  <button type="button" class="btn btn-primary" onclick="UpdateCompany(selectedCompanyId)">Update Company</button>
						</div>
					  </div>
					</div>