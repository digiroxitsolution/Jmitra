<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Update Sales Master</h5>
            <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form class="p-4" id="editSalesMasterForm">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="row mb-4">
                    <div class="col-12 mb-lg-0 mb-3">
                        <label for="file_names" class="form-label">File Name<span class="text-danger">*</span>:</label>
                        <input type="text" class="form-control" id="file_names" name="file_names" placeholder="File Name"   required>
                    </div>
                </div>
                <div class="col-6 mb-lg-0 mb-3">
                    <label for="date_of_upload" class="form-label">Date of Upload:</label>
                    <input type="date" class="form-control" id="date_of_upload" name="date_of_upload" placeholder="Date of Upload"   required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="UpdateSalesMAster(selectedSalesMAsterId)">Update Sales</button>
        </div>
    </div>
</div>
