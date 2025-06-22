<!--*******************
     Upload File Start
*****************-->
<div class="modal" tabindex="-1" id="uploadFileModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form  enctype="multipart/form-data" action="{{ route('sales_upload') }}"method="POST">
                    @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload File</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                    <div class="row mb-4">
                        <div class="col-lg-4 col-12 mb-lg-0 mb-3">
                            <input type="text" class="form-control" name="file_name" id="file_name" required placeholder="File Name">
                        </div>
                        <div class="col-lg-6 col-12 mb-lg-0 mb-3">
                            <input type="file" class="form-control" id="fileUpload" name="fileUpload" placeholder="Upload File">
                        </div>
                        <div class="col-lg-2 col-12">
                            <button type="button" class="btn btn-success" id="downloadFileButton">Download Sample</button>
                        </div>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="uploadBtn">Submit</button>
            </div>
        </div>

            </form>
        </div>
    </div>
</div>
<!--*******************
     Upload File End
*****************-->

<!-- Include Axios -->
<!-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> -->
<script>
	$(document).ready(function() {
	    $('#downloadFileButton').click(function() {
	        // Trigger the download request
	        window.location.href = '{{ route("sales_sample.download") }}';
	    });
	});
	
    
</script>