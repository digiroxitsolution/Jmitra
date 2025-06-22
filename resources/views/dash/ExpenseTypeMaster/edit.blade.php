<div class="modal-dialog modal-dialog-centered modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Update Expense Type</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						  <form class="p-4">
						  	<meta name="csrf-token" content="{{ csrf_token() }}">
							<div class="row mb-4">
								<div class="col-12 mb-3">
									<label for="expense_type" class="form-label">Expense Type<span class="text-danger">*</span>:</label>
									<textarea class="form-control" rows="5" id="expense_type" name="expense_type"  required></textarea>
								</div>
							</div>
						  </form>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						  <button type="button" class="btn btn-primary" onclick="updateExpenseType(selectedExpenseTypeId)">Update Expense Type</button>
						</div>
					  </div>
					</div>