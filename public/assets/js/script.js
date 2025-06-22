var el = document.getElementById('wrapper');
var toggleButton = document.getElementById('menu-toggle');

toggleButton.onclick = function(){
	el.classList.toggle('toggled');
}

document.addEventListener('DOMContentLoaded', function () {
const sidebarItems = document.querySelectorAll('.list-group-item');

sidebarItems.forEach(item => {
    item.addEventListener('click', function () {
        sidebarItems.forEach(i => i.classList.remove('active'));
        this.classList.add('active');
    	});
    });
});



// manipulating data tables start 
$(document).ready(function() {
            // Initialize DataTable with pagination and entries functionality
            var table = $('#example').DataTable({
                paging: true,             // Enable pagination
                searching: true,          // Enable searching
                info: false,              // Disable default info (Showing x of y entries)
                ordering: true,           // Enable ordering
                pageLength: 10,           // Set initial page length
                lengthMenu: [10, 25, 50, 75, 100, 150, 200, 500, 1000], // Options for entries per page
                scrollX: false,           // Disable horizontal scrolling
                autoWidth: true,
                responsive: true,         // Allow columns to adjust width automatically

            });

            // Optional: Adjust table container's overflow settings
            // $('#example_wrapper').css('overflow-x', 'hidden');

            // Hide the default "Show entries" dropdown from DataTables
            $('#example_length').hide();

            // Bind the custom "Show entries" dropdown to DataTable page length
            $('#custom-entries').on('change', function() {
                var pageLength = $(this).val();
                table.page.len(pageLength).draw(); // Update page length based on custom selection
            });

            // Hide the default DataTables search bar
            $('#example_filter').hide();

            // Sync custom search input with DataTable search
            $('#custom-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Update the info text when DataTable is redrawn (after filtering, pagination, etc.)
            table.on('draw', function() {
                var pageInfo = table.page.info();
                // Showing 'X' to 'Y' of 'Z' entries
                $('#entries-info').html('Showing ' + (pageInfo.start + 1) + ' to ' + pageInfo.end + ' of ' + pageInfo.recordsTotal + ' entries');
                updatePagination();  // Update pagination manually
            });

            // Manually update the entries info on initial load
            var initialPageInfo = table.page.info();
            $('#entries-info').html('Showing ' + (initialPageInfo.start + 1) + ' to ' + initialPageInfo.end + ' of ' + initialPageInfo.recordsTotal + ' entries');
            
            // Update pagination links manually
            // Update pagination links manually
            function updatePagination() {
                var pageInfo = table.page.info();
                var pages = pageInfo.pages;
                var currentPage = pageInfo.page;
                var maxVisiblePages = 4; // Maximum number of visible pages

                // Show the pagination controls if they were hidden
                $('#example_paginate').hide();

                // Clear existing pagination links
                $('#pagination').empty();

                // Add previous button
                if (currentPage > 0) {
                    $('#pagination').append('<li class="page-item"><a class="page-link" href="#" data-page="' + (currentPage - 1) + '">&laquo;</a></li>');
                }

                // Add page number buttons
                var startPage = Math.max(0, currentPage - Math.floor(maxVisiblePages / 2));
                var endPage = Math.min(pages - 1, startPage + maxVisiblePages - 1);

                if (endPage - startPage < maxVisiblePages - 1) {
                    startPage = Math.max(0, endPage - maxVisiblePages + 1);
                }

                for (var i = startPage; i <= endPage; i++) {
                    var activeClass = (i === currentPage) ? 'active' : '';
                    $('#pagination').append('<li class="page-item ' + activeClass + '"><a class="page-link" href="#" data-page="' + i + '">' + (i + 1) + '</a></li>');
                }

                // Add last page button if not visible
                if (endPage < pages - 1) {
                    $('#pagination').append('<li class="page-item disabled"><span class="page-link">...</span></li>');
                    $('#pagination').append('<li class="page-item"><a class="page-link" href="#" data-page="' + (pages - 1) + '">' + pages + '</a></li>');
                }

                // Add next button
                if (currentPage < pages - 1) {
                    $('#pagination').append('<li class="page-item"><a class="page-link" href="#" data-page="' + (currentPage + 1) + '">&raquo;</a></li>');
                }

                // Bind click event to new pagination links
                $('#pagination .page-link').on('click', function (e) {
                    e.preventDefault();
                    var page = $(this).data('page');
                    table.page(page).draw('page');
                });
            }

            // Ensure pagination is shown on initial load
            updatePagination();
        });
// manipulating data tables end

    document.querySelectorAll('.no-negative').forEach(input => {
        input.addEventListener('input', function () {
          if (this.value < 0) {
            this.value = 0;
          }
        });
      });

