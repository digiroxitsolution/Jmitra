					<div class="modal-dialog modal-dialog-centered modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Add Company</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						  <form class="p-4">
							<div class="row mb-4">
								<div class="col-12 mb-3">
									<label for="company_name" class="form-label">Company Name<span class="text-danger">*</span>:</label>
									<input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" required>
								</div>
								<div class="col-12 mb-3">
									<label for="location" class="form-label">Location<span class="text-danger">*</span>:</label>
									<input type="text" class="form-control" id="location" name="location" placeholder="Location">
								</div>
								<div class="col-12 mb-3">
									<label for="address" class="form-label">Company Address<span class="text-danger">*</span>:</label>
									<input type="text" class="form-control" id="address" name="address" placeholder="Company Address">
								</div>
							</div>
						  </form>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						  <button type="button" class="btn btn-primary" onclick="CreateCompany()">Create Company</button>
						</div>
					  </div>
					</div>