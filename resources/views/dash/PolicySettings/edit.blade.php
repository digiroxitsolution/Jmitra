<div class="modal fade" id="editPolicyModal{{ $policy->id }}" tabindex="-1" aria-labelledby="editPolicyModalLabel{{ $policy->id }}" aria-hidden="true">
				  <div class="modal-dialog modal-dialog-centered modal-xl">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title" id="editPolicyModalLabel{{ $policy->id }}">Edit Policy Setting</h5>
				        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
				      </div>
				      <form class="p-4" action="{{ route('policy_settings.update', $policy->id) }}" method="POST" id="policyForm">
					    @csrf
                		@method('PUT')
					    
					    <div class="modal-body">
					        
					        <div class="row mb-4">
					            <div class="col-lg-4 col-12 mb-lg-0 mb-3">
					                <label for="policy_name" class="form-label">Policy Name<span class="text-danger">*</span>:</label>
					                <input type="text" class="form-control" id="policy_name" name="policy_name" placeholder="Policy Name" value="{{ $policy->policy_name }}" required>
					                <div id="policyNameError" style="color: red; display: none;">Policy name is required!</div>
					            </div>
					            <div class="col-lg-4 col-12 mb-lg-0 mb-3">
					                <label for="designation_id" class="form-label">Designation<span class="text-danger">*</span>:</label>
					                <select class="form-select" aria-label="Default select example" id="designation_id" name="designation_id" required>
					                    @foreach ($allDesignations as $designation)
			                                <option value="{{ $designation->id }}" 
											    {{ $designation->id == $policy->designation_id ? 'selected' : '' }}>
											    {{ $designation->name }}
											</option>
			                            @endforeach
					                </select>
					                <div id="designationError" style="color: red; display: none;">Designation is required!</div>
					            </div>
					            <div class="col-lg-4 col-12 mb-lg-0 mb-3">
					                <label for="effective_date" class="form-label">Effective Date<span class="text-danger">*</span>:</label>
					                <input type="date" class="form-control" id="effective_date" name="effective_date" value="{{ $policy->effective_date }}" required>
					                <div id="effective_dateError" style="color: red; display: none;">Effective Date is required!</div>
					            </div>
					        </div>

					        <div class="row mb-4">
					            <div class="col-lg-4 col-12 mb-lg-0 mb-3">
					                <label for="location_da" class="form-label">Location DA<span class="text-danger">*</span>:</label>
					                <input type="number" class="form-control no-negative" id="location_da" name="location_da" placeholder="Location DA" value="{{ $policy->location_da }}" required>
					                <div id="locationDAError" style="color: red; display: none;">Location DA is required!</div>
					            </div>
					            <div class="col-lg-4 col-12 mb-lg-0 mb-3">
					                <label for="ex_location_da" class="form-label">Ex-Location DA<span class="text-danger">*</span>:</label>
					                <input type="number" class="form-control no-negative" id="ex_location_da" name="ex_location_da" placeholder="Ex-Location DA" value="{{ $policy->ex_location_da }}" required>
					                <div id="exLocationDAError" style="color: red; display: none;">Ex-Location DA is required!</div>
					            </div>
					            <div class="col-lg-4 col-12 mb-lg-0 mb-3">
					                <label for="outstation_da" class="form-label">Outstation DA<span class="text-danger">*</span>:</label>
					                <input type="number" class="form-control no-negative" id="outstation_da" name="outstation_da" placeholder="Outstation DA" value="{{ $policy->outstation_da }}" required>
					                <div id="outstationDAError" style="color: red; display: none;">Outstation DA is required!</div>
					            </div>
					        </div>

					        <div class="row mb-4">
					            <div class="col-lg-4 col-12 mb-lg-0 mb-3">
					                <label for="intercity_travel_ex_location" class="form-label">Intercity Travel (Ex Location Only)<span class="text-danger">*</span>:</label>
					                <input type="text" class="form-control" id="intercity_travel_ex_location" name="intercity_travel_ex_location" placeholder="Intercity Travel (Ex Location Only)" value="{{ $policy->intercity_travel_ex_location }}" required>
					                <div id="intercityExLocationError" style="color: red; display: none;">Intercity Travel (Ex Location Only) is required!</div>
					            </div>
					            <div class="col-lg-4 col-12 mb-lg-0 mb-3">
					                <label for="intercity_travel_outstation" class="form-label">Intercity Travel (Outstation)<span class="text-danger">*</span>:</label>
					                <input type="text" class="form-control" id="intercity_travel_outstation" name="intercity_travel_outstation" placeholder="Intercity Travel (Outstation)" value="{{ $policy->intercity_travel_outstation }}" required>
					                <div id="intercityOutstationError" style="color: red; display: none;">Intercity Travel (Outstation) is required!</div>
					            </div>
					            <div class="col-lg-4 col-12 mb-lg-0 mb-3">
					                <label for="other" class="form-label">Other:</label>
					                <input type="text" class="form-control" id="other" name="other" placeholder="Other"  value="{{ $policy->other }}">
					            </div>
					        </div>

					        <div class="row mb-4">
					            <div class="col-lg-6 col-12 mb-lg-0 mb-3">
					                <label for="charges" class="form-label">Two Wheeler Per KM Charges<span class="text-danger">*</span>:</label>
					                <input type="number" class="form-control" id="two_wheelers_charges" name="two_wheelers_charges"   value="{{ $policy->two_wheelers_charges }}" placeholder="Per KM Charges">
					            </div>
					            <div class="col-lg-6 col-12 mb-lg-0 mb-3">
					                <label for="charges" class="form-label">Four Wheeler Per KM Charges<span class="text-danger">*</span>:</label>
					                <input type="number" class="form-control" id="four_wheelers_charges" name="four_wheelers_charges"   value="{{ $policy->four_wheelers_charges }}" placeholder="Per KM Charges">
					            </div>
					            
					            
					        </div>

					    </div>

					    <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					        <button type="submit" class="btn btn-primary">Update Policy Setting</button>
					    </div>
					</form>
				    </div>
				  </div>
				</div>