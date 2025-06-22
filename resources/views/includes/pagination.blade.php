<!--*******************
					 Pagination Start
		 		*****************-->
				<div class="d-flex flex-lg-row flex-column justify-content-lg-between justify-content-center align-items-lg-start align-items-center mt-5">
					<span class="mb-lg-0 mb-3">
						<div class="d-flex flex-lg-row flex-column  align-items-center">
							<div class="me-2">
								<label for="custom-entries">Show Entries:</label>
							</div>
							<div class="me-2" id="entries-container">
								<select id="custom-entries" class="form-select" aria-label="Default select example">
								  <option selected>10</option>
								  <option value="20">20</option>
								  <option value="30">30</option>
								  <option value="40">40</option>
								  <option value="50">50</option>
								  <option value="75">75</option>
								  <option value="100">100</option>
								  <option value="150">150</option>
								  <option value="200">200</option>
								  <option value="500">500</option>
								  <option value="1000">1000</option>
								  <option value="2000">2000</option>

								</select>
							</div>
							<!-- Display Info Text After the Show Entries -->
							<div id="info-container">
								<span id="entries-info">Loading...</span>
							</div>							
						</div>

					</span>
					<nav aria-label="Page navigation example">
					  <ul class="pagination" id="pagination">
					  	<!-- Pagination links will be dynamically added here -->					    
					  </ul>
					</nav>
				</div>
				<!--*******************
					 Pagination End
		 		*****************-->