<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Update Hod</h5>
            <button
                type="button"
                class="btn-close bg-light"
                data-bs-dismiss="modal"
                aria-label="Close"
            ></button>
        </div>
        <div class="modal-body">
            <form id="editHodForm" novalidate>
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="mb-3">
                    <label for="name" class="form-label">Hod Name <span class="text-danger">*</span>:</label>
                    <input
                        class="form-control"
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Hod"
                        required
                    />
                </div> 
                
            </form>
        </div>
        <div class="modal-footer">
            <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
            >
                Close
            </button>
            <button
                type="button"
                class="btn btn-primary"
                onclick="UpdateHod(selectedHodId)"
            >
                Update Hod 
            </button>
        </div>
    </div>
</div>
