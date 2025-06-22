				<div class="modal" tabindex="-1" id="editNextModal">
					<div class="modal-dialog modal-dialog-centered modal-xl">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title">Monthly Expenses</h5>
						  <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
						  							<div class="row mb-4">
							 <div class="col-lg-6 col-12 mb-lg-0 mb-3">
								  <label for="exampleInputDAExLocation" class="form-label">DA(Ex-Location):</label>
							   <input type="text" class="form-control" id="exampleInputDAExLocation" placeholder="DA(Ex-Location)">
							 </div>
							 <div class="col-lg-6 col-12">
								  <label for="exampleInputDAOutstation" class="form-label">DA(Outstation)<span class="text-danger">*</span>:</label>
							   <input type="text" class="form-control" id="exampleInputDAOutstation" placeholder="DA(Outstation)">
							 </div>
							</div>
							<div class="row mb-4">
								<div class="col-lg-6 col-12 mb-lg-0 mb-3">
									<label for="exampleInputDATotal" class="form-label">DA(Total)<span class="text-danger">*</span>:</label>
								 <input type="text" class="form-control" id="exampleInputDATotal" placeholder="DA(Total)">
							  	 </div>
								<div class="col-lg-6 col-12">
										<label for="exampleInputPostage" class="form-label">Postage<span class="text-danger">*</span>:</label>
									<input type="text" class="form-control" id="exampleInputPostage" placeholder="Postage">
								</div>
							 </div>
							 <div class="row mb-4">
								<div class="col-lg-6 col-12 mb-lg-0 mb-3">
									<label for="exampleInputMobileInternet" class="form-label">Mobile/Internet<span class="text-danger">*</span>:</label>
								 <input type="text" class="form-control" id="exampleInputMobileInternet" placeholder="Mobile/Internet">
							  	 </div>
								<div class="col-lg-6 col-12">
										<label for="exampleInputPrintStationery" class="form-label">Print_Stationery<span class="text-danger">*</span>:</label>
									<input type="text" class="form-control" id="exampleInputPrintStationery" placeholder="Print_Stationery">
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-lg-6 col-12 mb-lg-0 mb-3">
									<label for="exampleInputOtherExpensesPurpose" class="form-label">Other Expenses Purpose<span class="text-danger">*</span>:</label>
									<select class="form-select" aria-label="Default select example" id="exampleInputOtherExpensesPurpose">
										<option selected>Select Other Expenses Purpose</option>
										<option value="1">Other Expenses Purpose 1</option>
										<option value="2">Other Expenses Purpose 2</option>
										<option value="3">Other Expenses Purpose 3</option>
										</select>
							  	 </div>
								<div class="col-lg-6 col-12">
										<label for="exampleInputOtherExpensesAmount" class="form-label">Other Expenses Amount<span class="text-danger">*</span>:</label>
									<input type="text" class="form-control" id="exampleInputOtherExpensesAmount" placeholder="Other Expenses Amount">
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-6">
									<div class="row">
										<label class="mb-1">Pre-Approved<span class="text-danger">*</span>:</label>
										<div class="col-lg-2 col-12 mb-lg-0 mb-3">
											<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
											<label class="form-check-label" for="inlineRadio1">Yes</label>
										</div>
										<div class="col-lg-2 col-12 mb-lg-0 mb-3">
										  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
										  <label class="form-check-label" for="inlineRadio2">No</label>
										</div>
										<div class="col-lg-2 col-12 mb-lg-0 mb-3">
										  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3">
										  <label class="form-check-label" for="inlineRadio3">N.A.</label>
										</div>
									</div>
								</div>
								<div class="col-6">
									<div class="row">
										<div class="d-flex justify-content-between">
											<div>
												<label class="mb-1">Approved Date<span class="text-danger">*</span>:</label>
											</div>
											<div>
												<label for="ApprovedDate">Date<span class="text-danger">*</span>:</label>
											</div>
										</div>
										<div class="col-lg-4 col-12 mb-lg-0 mb-3">
											<label class="form-check-label" for="inlineRadioOptionsApprovedDate1">Date</label>
											<input class="form-check-input" type="radio" name="inlineRadioOptionsApprovedDate" id="inlineRadioOptionsApprovedDate1" value="option1">
										</div>
										<div class="col-lg-4 col-12 mb-lg-0 mb-3">
											<label class="form-check-label" for="inlineRadioOptionsApprovedDate2">N.A.</label>
										  <input class="form-check-input" type="radio" name="inlineRadioOptionsApprovedDate" id="inlineRadioOptionsApprovedDate2" value="option2">
										</div>
										<div class="col-lg-4 col-12 mb-lg-0 mb-3">
											<input type="date" id="ApprovedDate" class="form-control"> 
										</div>
									</div>
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-lg-6 col-12 mb-lg-0 mb-3">
									<label for="exampleInputFromAppovedBy" class="form-label">Approved By<span class="text-danger">*</span>:</label>
									<select class="form-select" aria-label="Default select example" id="exampleInputFromAppovedBy">
									<option selected>Select Aproved By</option>
									<option value="1">Aproved By 1</option>
									<option value="2">Aproved By 2</option>
									<option value="3">Aproved By 3</option>
									</select>
								</div>
								<div class="col-6">
									<div class="row">
										<label class="mb-1">Upload of Approvals Documnets :</label>
										<div class="col-lg-3 col-12 mb-lg-0 mb-3">
											<label class="form-check-label" for="inlineRadioOptionsUploadOfApprovalsDocumentsUpload">Upload</label>
											<input class="form-check-input" type="radio" name="inlineRadioOptionsUploadOfApprovalsDocuments" id="inlineRadioOptionsUploadOfApprovalsDocuments" value="option1">
										</div>
										<div class="col-lg-2 col-12 mb-lg-0 mb-3">
											<label class="form-check-label" for="inlineRadioOptionsUploadOfApprovalsDocumentsNA">N.A.</label>
											<input class="form-check-input" type="radio" name="inlineRadioOptionsUploadOfApprovalsDocuments" id="inlineRadioOptionsUploadOfApprovalsDocumentsNA" value="option2">
										</div>
										<div class="col-lg-7 col-12 mb-lg-0 mb-3">
											<input class="form-control" type="file" id="inlineRadioOptionsUploadOfApprovalsDocumentsUploadFile">
										</div>
									</div>
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-6">
									<label class="mb-1"  >View of Attendace <span class="text-danger">*</span></label><br>
									<button data-bs-toggle="modal" data-bs-target="#viewOfAttendenceModal" type="button" class="btn btn-success">View</button>
								</div>
								<div class="col-6">
									<label for="MontlyExpensesRemarks">Remarks <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="MontlyExpensesRemarks">
								</div>
							</div>
							<div class="row mb-4">
								<div class="col-12">
									<input class="form-check-input me-2" type="checkbox" value="" id="Agreement">
									<label class="form-check-label" for="Agreement">I hereby confirmed that I have verify the Expenses and found OK as per Travel/ Daily Allowance Policy.</label>
								</div>
							</div>
						  
						</div>
						<div class="modal-footer">
						  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa-solid fa-arrow-left me-1"></i> Previous</button>
						  <button type="submit" class="btn btn-success"> Submit</button>
						</div>
					  </div>
					</div>
				</div>