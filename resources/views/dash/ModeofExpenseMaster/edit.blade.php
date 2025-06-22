<div class="modal-dialog modal-dialog-centered modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Update Expense</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						  <form class="p-4">
						  	<meta name="csrf-token" content="{{ csrf_token() }}">
							<div class="row mb-4">
								<div class="col-12 mb-3">
									<label for="mode_expenses" class="form-label">Mode Expense<span class="text-danger">*</span>:</label>
									<textarea class="form-control" rows="5" name="mode_expenses" id="mode_expenses"></textarea>
								</div>
							</div>
						  </form>
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						  <button type="button" class="btn btn-primary" onclick="UpdateModeOfExpenses(selectedModeOfExpensesId)">Update Expense</button>
						</div>
					  </div>
					</div>