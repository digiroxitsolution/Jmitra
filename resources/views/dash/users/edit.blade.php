<div class="modal" tabindex="-1" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">User Update</h5>
                        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form class="p-4" action="{{ route('users.update', $user->id) }}" method="POST" id="userForm">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row mb-4">
                                <div class="col-lg-6 col-12 mb-lg-0 mb-3">
                                    <label for="name" class="form-label">Employee Name<span class="text-danger">*</span>:</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Employee Name" value="{{ $user->name }}">
                                </div>
                                <div class="col-lg-6 col-12">
                                    <label for="email" class="form-label">E-Mail:</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 col-12 mb-lg-0 mb-3">
                                  <label for="password" class="form-label">Password <span class="text-danger">*</span>:</label>
                                  <div class="input-group">
                                      <input 
                                          type="password" 
                                          class="form-control" 
                                          name="password" 
                                          id="password" 
                                          placeholder="Enter password"
                                          value="{{ old('password') }}" 
                                          autocomplete="new-password">
                                      
                                  </div>
                              </div>
                                <div class="col-lg-4 col-12 mb-lg-0 mb-3">
                                    <label for="confirm-password" class="form-label">Confirm Password<span class="text-danger">*</span>:</label>
                                    <input type="password" class="form-control" name="confirm-password" id="confirm-password" placeholder="Confirm Password" value="{{ old('password') }}">
                                </div>
                                <div class="col-lg-4 col-12 mb-lg-0">
                                    <label for="designation_id" class="form-label">Designation<span class="text-danger">*</span>:</label>
                                    <select class="form-select" id="designation_id" name="designation_id">
                                        <option selected>Select Designation</option>
                                        @foreach($designations as $designation)
                                            <option value="{{ $designation->id }}" {{ $user->userDetail->designation_id == $designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 col-12 mb-lg-0 mb-3">
                                    <label for="status" class="form-label">Status<span class="text-danger">*</span>:</label>
                                    <select class="form-select" name="status" id="status">
                                        
                                         @foreach([1 => 'Active', 2 => 'Inactive', 3 => 'Banned'] as $value => $label)
                                          <option value="{{ $value }}" 
                                              {{ ($user->status == $value) ? 'selected' : '' }}>
                                              {{ $label }}
                                          </option>
                                      @endforeach                 </select>
                                </div>
                                <div class="col-lg-4 col-12 mb-lg-0">
                                    <label for="divison_master_id" class="form-label">Divison<span class="text-danger">*</span>:</label>
                                    <select class="form-select" id="divison_master_id" name="divison_master_id">
                                        <option selected>Select Divison</option>
                                        @foreach($divisons as $divison)
                                            <option value="{{ $divison->id }}" {{ $user->userDetail->company_master_id == $divison->id ? 'selected' : '' }}>{{ $divison->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-4">
                                <div class="col">
                                    <label for="company_master_id" class="form-label">Company Name<span class="text-danger">*</span>:</label>
                                    <select class="form-select" id="company_master_id" name="company_master_id">
                                        <option selected>Select Company Name</option>
                                         @foreach ($company_masters as $company)
                                            <option value="{{ $company->id }}" 
                                                {{ $user->userDetail->company_master_id == $company->id ? 'selected' : '' }}>
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 col-12 mb-lg-0 mb-3">
                                    <label for="state_id" class="form-label">State<span class="text-danger">*</span>:</label>
                                    <select class="form-select" id="state_id" name="state_id">
                                        <option selected>Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ $user->userDetail->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 col-12 mb-lg-0 mb-3">
                                    <label for="city_id" class="form-label">City:</label>
                                    <select class="form-select" id="city_id" name="city_id">
                                        <option selected>Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ $user->userDetail->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-12 mb-lg-0">
                                    <label for="location_master_id" class="form-label">Working Location<span class="text-danger">*</span>:</label>
                                    <select class="form-select" id="location_master_id" name="location_master_id">
                                        <option selected>Select Working Location</option>
                                        @foreach($location_masters as $location_master)
                                            <option value="{{ $location_master->id }}" {{ $user->userDetail->location_master_id == $location_master->id ? 'selected' : '' }}>{{ $location_master->working_location }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-12 mb-lg-0 mb-3">
                                    <label for="roles" class="form-label">Role<span class="text-danger">*</span>:</label>
                                    <select class="form-select" name="roles" id="roles">
                                        <option selected disabled>Select Role</option>
                                         @foreach($roles as $role)
                                              <option value="{{ $role->name }}" {{ old('roles', $user->roles->pluck('name')->first() ?? '') == $role->name ? 'selected' : '' }}>
                                                  {{ $role->name }}
                                              </option>
                                          @endforeach
                                                                          </select>
                                </div>
                                <div class="col-lg-4 col-12 mb-lg-0 mb-3">
                                    <label for="hod_id" class="form-label">HOD Name<span class="text-danger">*</span>:</label>
                                    <select class="form-select" id="hod_id" name="hod_id">
                                        <option selected>Select HOD Name</option>
                                        @foreach($hods as $hod)
                                            <option value="{{ $hod->id }}" {{ $user->userDetail->hod_id == $hod->id ? 'selected' : '' }}>{{ $hod->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-12 mb-lg-0 mb-3">
                                    <label for="policy_setting_id" class="form-label">Policy Applicable<span class="text-danger">*</span>:</label>
                                    <select class="form-select" id="policy_setting_id" name="policy_setting_id">
                                        <option selected>Select Policy Applicable</option>
                                        @foreach($policy_settings as $policy_setting)
                                            <option value="{{ $policy_setting->id }}" {{ $user->userDetail->policy_setting_id == $policy_setting->id ? 'selected' : '' }}>{{ $policy_setting->policy_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </div>
                    </form>
                    </div>
                  </div>
                </div>
