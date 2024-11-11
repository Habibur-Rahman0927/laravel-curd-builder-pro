$(function () {
    let url = $('#routeData').data('url');

    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: url,
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action', 
            render: function(data, type, row) {
                let editButton = '<a href="/admin/role/' + row.id + '/edit" class="edit btn btn-success btn-sm">Edit</a>';
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
                    url: "/admin/role/" + id,
                    data: {
                        "_method": "DELETE",
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function (response) {
                        if (response.status_code === 200) {
                            Swal.fire(
                                'Deleted!',
                                'The role has been deleted.',
                                'success'
                            );
                            table.row(row).remove().draw();
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message || 'role was not deleted.',
                                'error'
                            );
                        }
                    },
                    error: function (xhr) {
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the role.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});