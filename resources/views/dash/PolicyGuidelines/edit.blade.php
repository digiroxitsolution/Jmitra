<div class="modal" tabindex="-1" id="editModal{{ $guidelines->id }}">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Guidelines</h5>
                        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="p-4" action="{{ route('policy_guidelines.update', $guidelines->id) }}" method="POST" id="guidelineForm" enctype="multipart/form-data">
                       @csrf
                        @method('PUT')
                    <div class="modal-body">
                        
                            <div class="row mb-4">
                                <div class="col-lg-4 col-12 mb-lg-0 mb-3">
                                    <label for="policy_setting_id" class="form-label">Policy Name<span class="text-danger">*</span>:</label>
                                    <select class="form-select" aria-label="Default select example" id="policy_setting_id" name="policy_setting_id" required>
                                        @foreach ($policySettings as $Settings)
                                            <option value="{{ $Settings->id }}" 
                                                @if (isset($guidelines) && $guidelines->policy_setting_id == $Settings->id) 
                                                    selected 
                                                @endif>
                                                {{ $Settings->policy_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="policy_setting_idError" style="color: red; display: none;">Policy Name is required!</div>
                                </div>
                                <div class="col-lg-4 col-12 mb-lg-0 mb-3">
                                     <label for="file_name" class="form-label">File Name<span class="text-danger">*</span>:</label>
                                     <input type="text" class="form-control" id="file_name" name="file_name" placeholder="File Name" value="{{ $guidelines->file_name }}">
                                     <div id="file_nameError" style="color: red; display: none;">File Name is required!</div>
                                </div>
                                <div class="col-lg-4 col-12 mb-lg-0 mb-3">
                                    
                                    <div class="form-group">
                                        <label for="uploaded_file" class="form-label">Upload File <span class="text-danger">*</span>:</label>
                                        
                                        <!-- Display the current file if available -->
                                        @if($guidelines->uploaded_file)
                                            <p>Current File: <a href="{{ asset('public/' . $guidelines->uploaded_file) }}" target="_blank">{{ basename($guidelines->uploaded_file) }}</a></p>
                                        @endif

                                        <!-- File input for uploading a new file -->
                                        <input type="file" class="form-control" id="uploaded_file" name="uploaded_file">
                                    </div>
                                    <div id="uploaded_fileError" style="color: red; display: none;">Guideline Document is required!</div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12 mb-lg-0 mb-3">
                                    <label for="policy_description" class="form-label">Policy Description<span class="text-danger">*</span>:</label>
                                    <textarea class="form-control" rows="7" id="policy_description" name="policy_description">{{ $guidelines->policy_description }}</textarea>


                                    <div id="policy_descriptionError" style="color: red; display: none;">Policy Description is required!</div>
                                </div>
                            </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Guidelines</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>