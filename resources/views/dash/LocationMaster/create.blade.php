<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add Location</h5>
            <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="createLocationForm" class="p-4">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="row mb-4">
                    <div class="col-12 mb-3">
                        <label for="state_id" class="form-label">State<span class="text-danger">*</span>:</label>
                        <select name="state_id" id="state" class="form-control" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="city_id" class="form-label">City<span class="text-danger">*</span>:</label>
                        <select name="city_ids" id="city" class="form-control" required>
                            <option value="">Select City</option>
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="working_location" class="form-label">Working Location<span class="text-danger">*</span>:</label>
                        <input type="text" class="form-control" id="working_location" name="working_location" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="submitLocation">Add Location</button>
        </div>
    </div>
</div>
