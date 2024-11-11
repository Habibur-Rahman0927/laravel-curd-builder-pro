
$(function () {
    let url = $('#routeData').data('url');
            
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: url,
        columns: [
            {data: 'id', name: 'id'},
			{data: 'name', name: 'name'},
            {data: 'permissions', name: 'action',
            render: function(data, type, row) {
                return '<div><a href="#" class="view-permissions" data-toggle="modal" data-permissions=\'' + JSON.stringify(data) + '\'>Show Permissions</a></div>';
            },
            orderable: false, searchable: false},
            {data: 'action', name: 'action', 
            render: function(data, type, row) {
                console.log(row)
                let editButton = '<a href="/admin/rolehaspermission/' + row.id + '/edit" class="edit btn btn-success btn-sm">Edit</a>';
                let deleteButton = '<button class="delete btn btn-danger btn-sm" data-id="' + row.id + '">Delete</button>';
                return editButton + ' ' + deleteButton;
            },
            orderable: false, searchable: false},
        ],
        dom: '<"row"<"col-md-6"l><"col-md-6"f>>t<"row"<"col-md-5"i><"col-md-7"p>>',
        buttons: [
            { extend: 'csv', text: 'CSV', className: 'btn btn-secondary' },
            { extend: 'excel', text: 'Excel', className: 'btn btn-secondary' },
            { extend: 'print', text: 'Print', className: 'btn btn-secondary' }
        ],
        initComplete: function () {
            var exportButton = $('.export-btn');
            var buttons = $('.dt-buttons').detach();
            exportButton.after(buttons);
        }
    });
            
    $('.column-search').on('click', function(e) {
        e.stopPropagation();
    });
            
    $('.column-search').on('keyup change', function() {
        let columnIndex = $(this).parent().index();
        table.column(columnIndex).search(this.value).draw();
    });
            
    $('.dropdown-menu').on('click', 'button', function() {
        var action = $(this).attr('id');
        switch (action) {
            case 'csvExport':
                table.button('.buttons-csv').trigger();
                break;
            case 'excelExport':
                table.button('.buttons-excel').trigger();
                break;
            case 'printExport':
                table.button('.buttons-print').trigger();
                break;
        }
    });
            
    $(document).on('click', '.delete', function () {
        var id = $(this).data('id');
        var row = $(this).closest('tr');
            
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "/admin/rolehaspermission/" + id,
                    data: {
                        "_method": "DELETE",
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        if (response.status_code === 200) {
                            Swal.fire(
                                'Deleted!',
                                'The rolehaspermission has been deleted.',
                                'success'
                            );
                            table.row(row).remove().draw();
                        } else {
                             Swal.fire(
                                'Error!',
                                response.message || 'RoleHasPermission was not deleted.',
                                'error'
                            );
                        }
                    },
                    error: function (xhr) {
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the rolehaspermission.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    $(document).on('click', '.view-permissions', function() {
        let permissions = $(this).data('permissions');

        let memberTableHtml = '';
        if (permissions.length > 0) {
            // Create table with 5 columns
            const columns = (permissions.length >= 3) ? 3 : 
                            (permissions.length >= 2) ? 2 : 
                            (permissions.length >= 1) ? 1 : 3;
            const rows = Math.ceil(permissions.length / columns);

            memberTableHtml = `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            ${Array(columns).fill('').map((_, i) => `<th>Permissions</th>`).join('')}
                        </tr>
                    </thead>
                    <tbody>
                        ${Array(rows).fill('').map((_, rowIndex) => {
                            const cells = Array(columns).fill('').map((_, colIndex) => {
                                const index = rowIndex * columns + colIndex;
                                const perm = permissions[index];
                                const formattedValue = convertToNormalText(perm?.name);
                                return `<td>${perm ? `<i class="fas fa-check" style="color: green; margin-right:5px;"></i>${formattedValue}` : ''}</td>`;
                            }).join('');
                            return `<tr>${cells}</tr>`;
                        }).join('')}
                    </tbody>
                </table>
            `;
        } else {
            memberTableHtml = 'No Permissions Assigned';
        }

        $('#permissionTableContainer').html(memberTableHtml);
        $('#permissionModal').modal('show');
    });

    /**
     * Convert a string with hyphens, dots, and camel case to normal text format.
     *
     * @param {string} input - The input string to convert.
     * @return {string} - The converted string.
     */
    function convertToNormalText(input) {
        // Replace hyphens and dots with spaces
        let output = input?.replace(/[-.]/g, ' ');

        // Convert camel case to spaced words
        output = output?.replace(/([a-z])([A-Z])/g, '$1 $2');

        // Capitalize the first letter of each word
        output = output?.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());

        return output;
    }
});
            