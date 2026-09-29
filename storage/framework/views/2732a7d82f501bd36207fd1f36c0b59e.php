<?php $__env->startSection('title', 'Survey List'); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/jquery.dataTables.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/autoFill.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/keyTable.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/buttons.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/fixedHeader.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/responsive.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/rowReorder.bootstrap5.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/flatpickr/flatpickr.min.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/vendors/select/bootstrap-select.min.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main_content'); ?>
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3><?php echo e(__('Surveys')); ?></h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.survey.index')); ?>"> <svg class="stroke-icon">
                                    <use href="<?php echo e(asset('assets/svg/icon-sprite.svg#stroke-home')); ?>"></use>
                                </svg></a></li>
                        
                        <li class="breadcrumb-item active"><?php echo e(__('Surveys')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid starts-->
    <div class="container-fluid product-report-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body px-0 pt-0">
                        <div class="top-body">
                            <div class="row common-f-start g-sm-3 g-2">
                                <div class="col-auto">
                                    <select id="treatment-filter" class="form-select w-auto">
                                        <option value=""><?php echo e(__("All")); ?> </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="product-report">
                            <div class="recent-table table-responsive custom-scrollbar">
                                <table class="table" id="survey-table">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th><?php echo e(__("Title")); ?></th>
                                            <th><?php echo e(__("Frequency")); ?></th>
                                            <th><?php echo e(__("Patients")); ?></th>
                                            <th><?php echo e(__("Questions")); ?></th>
                                            <th><?php echo e(__("Next Delivery")); ?></th>
                                            
                                            <th><?php echo e(__("Actions")); ?></th>
                                        </tr>
                                    </thead>
                                    
                            <tbody>
                                
                            </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Container-fluid Ends-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/dataTables.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/dataTables.select.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/select.bootstrap5.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatables/datatable.custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.autoFill.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/autoFill.bootstrap5.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.keyTable.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/keyTable.bootstrap5.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.buttons.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.bootstrap5.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.colVis.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.fixedHeader.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/fixedHeader.bootstrap5.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/pdfmake.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/vfs_fonts.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/buttons.print.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.responsive.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/responsive.bootstrap5.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/dataTables.rowReorder.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/rowReorder.bootstrap5.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatable/datatable-extension/custom.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/flat-pickr/flatpickr.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/flat-pickr/custom-flatpickr.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/flat-pickr/moment.js')); ?>"></script>
    
    <script src="<?php echo e(asset('assets/js/modalpage/validation-modal.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/select/bootstrap-select.min.js')); ?>"></script>


        <script>
    $(document).ready(function() {
        let surveyTable;

        // Function to load survey data
        function loadSurveyData() {
            $.ajax({
                url: "<?php echo e(route('admin.survey.getData')); ?>",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        initializeDataTable(response.data);
                    } else {
                        console.error('Failed to load surveys:', response.message);
                    }
                },
                initComplete: function() {
                    $('.buttons-html5').removeClass('btn-secondary');
                },
                error: function(xhr, status, error) {
                    console.error('Error loading surveys:', error);
                }
            });
        }

       function initializeDataTable(data) {
    // Destroy existing table if it exists
    if ($.fn.DataTable.isDataTable('#survey-table')) {
        $('#survey-table').DataTable().destroy();
    }

    surveyTable = $("#survey-table").DataTable({
        data: data,
        columns: [
            {
                data: null, // S/N column
                width: '5%',
                render: function(data, type, row, meta) {
                    return meta.row + 1 + meta.settings._iDisplayStart;
                }
            },
            {
                data: 'title',
                width: '25%',
                render: function(data, type, row) {
                    let html = `<div class="fw-bold">${data}</div>`;
                    if (row.description) {
                        const desc = row.description.length > 50 
                            ? row.description.substring(0, 50) + '...' 
                            : row.description;
                        html += `<small class="text-muted d-block mt-1">${desc}</small>`;
                    }
                    return html;
                }
            },
           {
                data: 'frequency',
                width: '5%',
                render: function(data, type, row) {
                    const badges = {
                        'once': 'secondary',
                        'daily': 'primary',
                        'weekly': 'success',
                        'monthly': 'info',
                        'custom': 'warning'
                    };

                    // Make sure data is a string
                    const value = data || '';  

                    const badge = badges[value] || 'secondary';
                    const text = value ? value.charAt(0).toUpperCase() + value.slice(1) : '-';
                    
                    return `<span class="">${text}</span>`;
                }
            },

            {
                data: 'batch_patient_count',
                width: '5%',
                orderable: false,
                render: function(data, type, row) {
                    return `<span class="badge badge-light-dark">${data} patient${data !== 1 ? 's' : ''}</span>`;
                }
            },
            {
                data: 'questions_count',
                width: '5%',
                orderable: false,
                render: function(data, type, row) {
                    return `${data} question${data !== 1 ? 's' : ''}`;
                }
            },
            {
                data: 'survey_delivery_date',
                width: '10%',
                render: function(data, type, row) {
                    if (!data) return '<span class="text-muted">N/A</span>';
                    const date = new Date(data);
                    return date.toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'short', 
                        day: 'numeric' 
                    });
                }
            },
            // {
            //     data: 'cron_status',
            //     width: '10%',
            //     render: function(data, type, row) {
            //         const statusClass = data === 'active' ? 'success' : 'danger';
            //         const text = data.charAt(0).toUpperCase() + data.slice(1);
            //         return `<span class="badge badge-${statusClass}">${text}</span>`;
            //     }
            // },
            {
                data: 'id',
                width: '15%',
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="/surveys/show/${data}" class="btn btn-outline-primary btn-sm" title="View">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="/surveys/${data}/edit" class="btn btn-outline-primary btn-sm" title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button class="btn btn-outline-danger btn-sm delete-survey" data-id="${data}" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[0, "asc"]], // Sort by Title (second column)
        pageLength: 10,
        responsive: false,
        autoWidth: false,
        scrollX: false,
        // Add this to align all content left
        createdRow: function(row, data, dataIndex) {
            // Align all cells to left
            $('td', row).css('text-align', 'left');
        },
        // Remove checkbox related columnDefs
        // columnDefs: [
        //     {
        //         orderable: false,
        //         render: $.fn.dataTable.render.select(),
        //         targets: 0,
        //     },
        // ],
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: "copy",
                        text: "<?php echo e(__('Copy')); ?>",
                        className: "btn btn-outline-primary",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6] // Adjust columns since checkbox removed
                        },
                        attr: {
                            class: "btn btn-outline-primary"
                        }
                    },
                    {
                        extend: "csv",
                        className: "btn btn-outline-primary",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        },
                        attr: {
                            class: "btn btn-outline-primary"
                        }
                    },
                    {
                        extend: "excel",
                        className: "btn btn-outline-primary",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        },
                        attr: {
                            class: "btn btn-outline-primary"
                        }
                    },
                    {
                        extend: "pdf",
                        className: "btn btn-outline-primary",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        },
                        attr: {
                            class: "btn btn-outline-primary"
                        }
                    },
                    {
                        extend: "print",
                        text: "<?php echo e(__('Print')); ?>",
                        className: "btn btn-outline-primary",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        },
                        attr: {
                            class: "btn btn-outline-primary"
                        }
                    },
                    {
                        text: '<i class="fa fa-plus"></i> <?php echo e(__("Add Survey")); ?>',
                        className: 'btn btn-primary ms-2',
                        attr: {
                            class: "btn btn-primary ms-2"
                        },
                        action: function () {
                            window.location.href = "<?php echo e(route('admin.survey.create')); ?>";
                        }
                    }
                ],
            },
            topEnd: {
                search: {
                    placeholder: "Search here...",
                },
            },
        },
        // Add column alignment
        columnDefs: [
            {
                targets: '_all',
                className: 'text-left'
            },
            {
                targets: 0, // S/N column
                className: 'text-center' // Center align S/N
            }
        ]
    });

    // Remove any existing checkbox event handlers
    $(document).off('click', '#select-all');
    $(document).off('click', '.delete-survey');
    
    // Rebind delete event
    $(document).on('click', '.delete-survey', function(e) {
        e.preventDefault();
        const surveyId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this survey? This will delete all surveys in this batch.')) {
            $.ajax({
                url: `/surveys/${surveyId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        notify('success', response.message || 'Survey deleted successfully');
                        //alert(response.message || 'Survey deleted successfully');
                        loadSurveyData(); // Reload data
                    } else {
                        notify('danger', response.message || 'Failed to delete survey');
                        //alert(response.message || 'Failed to delete survey');
                    }
                },
                error: function(xhr) {
                    notify('danger', 'An error occurred while deleting the survey');
                    //alert('An error occurred while deleting the survey');
                    console.error('Delete error:', xhr);
                }
            });
        }
    });
}

        // Delete survey
        // $(document).on('click', '.delete-survey', function(e) {
        //     e.preventDefault();
        //     const surveyId = $(this).data('id');
            
        //     if (confirm('Are you sure you want to delete this survey? This will delete all surveys in this batch.')) {
        //         $.ajax({
        //             url: `/surveys/${surveyId}`,
        //             type: 'DELETE',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             success: function(response) {
        //                 if (response.success) {
        //                     notify('success', response.message || 'Survey deleted successfully');
        //                    // alert(response.message || 'Survey deleted successfully');
        //                     loadSurveyData(); // Reload data
        //                 } else {
        //                     notify('danger', response.message || 'Failed to delete survey');
        //                    //
        //                     //alert(response.message || 'Failed to delete survey');
        //                 }
        //             },
        //             error: function(xhr) {
        //                 notify('danger', 'An error occurred while deleting the survey');
        //                 //alert('An error occurred while deleting the survey');
        //                 console.error('Delete error:', xhr);
        //             }
        //         });
        //     }
        // });

        // Bulk delete (optional)
        $('#bulk-delete-btn').on('click', function() {
            const selectedIds = $('.row-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                alert('Please select at least one survey to delete');
                return;
            }

            if (confirm(`Are you sure you want to delete ${selectedIds.length} survey(s)?`)) {
                $.ajax({
                    url: "<?php echo e(route('admin.survey.bulk-delete')); ?>",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        ids: selectedIds
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message || 'Surveys deleted successfully');
                            loadSurveyData(); // Reload data
                            $('#select-all').prop('checked', false);
                        } else {
                            alert(response.message || 'Failed to delete surveys');
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while deleting surveys');
                    }
                });
            }
        });

        // Initial load
        loadSurveyData();
    });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ecc-pain-new\resources\views/survey/index.blade.php ENDPATH**/ ?>