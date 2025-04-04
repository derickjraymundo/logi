<?php 
    include "includes/session.php";
    include "includes/header.php";
?>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Released Cargoes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Transactions</li>
                        <li class="breadcrumb-item active">Released Cargoes</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary" id="btn_filter"><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
                <button class="btn btn-primary" id="btn_download_template"><i class="fa fa-upload" aria-hidden="true"></i> Import Excel</button>

               
                <!-- <h3 class="card-title">
                    Trigger Button for Modal
                    <button class="btn btn-primary" id="btn_add">Create</button>
                </h3> -->
            </div>
            <div class="card-body">
                <!-- <label for="">Date of Arrival</label>
                <div class="row">
                
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_filter">Min</label>
                            <input type="text" id="min" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_filter">Max</label>
                            <input type="text" id="max" class="form-control">
                        </div>
                    </div>
                </div> -->
           
            <div class="table-responsive">
            <table id="tbl_1" class="table table-hover table-bordered table-striped tbl_1" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>#</th>
                            <th>Port</th>
                            <th>Consignee</th>
                            <th>Flight No.</th>
                            <th>Airway Bill No.</th>
                            <th>Item/Goods.</th>
                            <th>HS CODE.</th>
                            <th>Quantity.</th>
                            <th>Duties and Taxes.</th>
                            <th>Gross Weight (KGS.)</th>
                            <th>Actual Released Date</th>
                            <th>Remarks</th>
                            <th>Item Name</th>
                            <th>Branch Name</th>
                            <th>Chargeable Weight</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>#</th>
                            <th>Port</th>
                            <th>Consignee</th>
                            <th>Flight No.</th>
                            <th>Airway Bill No.</th>
                            <th>Item/Goods.</th>
                            <th>HS CODE.</th>
                            <th>Quantity.</th>
                            <th>Duties and Taxes.</th>
                            <th>Gross Weight (KGS.)</th>
                            <th>Actual Released Date</th>
                            <th>Remarks</th>
                            <th>Item Name</th>
                            <th>Branch Name</th>
                            <th>Chargeable Weight</th>
                        </tr>
                    </tfoot>
                </table>
                </div>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<div class="modal fade mdl_3" id="mdl_3" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_3" class="form-horizontal ">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2"><i class="fa fa-filter" aria-hidden="true"></i> Filter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country-filter">Filter by Country of Origin</label>
                                <input type="text" id="country-filter" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="consignee-filter">Filter by Consignee</label>
                                <input type="text" id="consignee-filter" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="flightno-filter">Filter by Flight No.</label>
                                <input type="text" id="flightno-filter" class="form-control">
                            </div>
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_filter">Actual Release Date From</label>
                                        <input type="text" id="min" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_filter">Actual Release Date To</label>
                                        <input type="text" id="max" class="form-control">
                                    </div>
                                </div>
                            </div>
                          
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="items-filter">Filter by Items/Goods</label>
                                <input type="text" id="items-filter" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="hscode-filter">Filter by HS CODE</label>
                                <input type="text" id="hscode-filter" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="branch-filter">Filter by Branch</label>
                                <input type="text" id="branch-filter" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="airwaybill-filter">Filter by Airway Bill No.</label>
                                <input type="text" id="airwaybill-filter" class="form-control">
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btn_clear_filter" class="btn btn-danger">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Create Port -->
<div class="modal fade mdl_1" id="mdl_1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_1" class="frm_1" action="cargoes_released_act.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Import Cargoes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="operation" id="operation">
                    <input type="hidden" name="text_1" id="text_1">
                    <div class="form-group">
                        <label for="text_2">Port of Discharge</label>
                        <select id="text_2" class="form-control" name="text_2">
                            <option value="">Select an option</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="text_3">Consignee</label>
                        <select id="text_3" class="form-control" name="text_3">
                            <option value="">Select an option</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="text_4">Flight No.</label>
                        <input type="text" class="form-control" name="text_4" id="text_4">
                    </div>
                    <div class="form-group">
                        <label for="text_5">Airway Bill No.</label>
                        <input type="number" class="form-control" name="text_5" id="text_5">
                    </div>
                    <div class="form-group">
                        <label for="text_6">Item/Goods</label>
                        <select id="text_6" class="form-control" name="text_6">
                            <option value="">Select an option</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="text_7">HS CODE.</label>
                        <input type="number" class="form-control" name="text_7" id="text_7">
                    </div>
                    <div class="form-group">
                        <label for="text_8">Quantity.</label>
                        <input type="number" class="form-control" name="text_8" id="text_8">
                    </div>
                    <div class="form-group">
                        <label for="text_9">Duties and Taxes.</label>
                        <input type="number" class="form-control" name="text_9" id="text_9">
                    </div>
                    <div class="form-group">
                        <label for="text_10">Gross Weight (KGS.)</label>
                        <input type="number" class="form-control" name="text_10" id="text_10">
                    </div>
                    <div class="form-group">
                        <label for="text_11">Actual Date of Arrival</label>
                        <input type="date" class="form-control" name="text_11" id="text_11">
                    </div>
                    <div class="form-group">
                        <label for="text_12">Remarks</label>
                        <textarea class="form-control" id="text_12" name="text_12" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn1">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade mdl_2" id="mdl_2" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_2" class="frm_2" action="cargoes_released_act.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Mark as Released</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="operation2" id="operation2">
                    <input type="text" name="text_2_1" id="text_2_1">
                    <div class="form-group">
                        <label for="text_2_2">Chargeable Weight <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" name="text_2_2" id="text_2_2">
                    </div>
                    <div class="form-group">
                        <label for="text_2_3">Actual Released Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="text_2_3" id="text_2_3">
                    </div>
                    <div class="form-group">
                        <label for="text_2_4">Actual Released Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="text_2_4" id="text_2_4">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn2">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade mdl_4" id="mdl_4" role="dialog" aria-labelledby="exampleModalLabel4" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_4" class="frm_4" action="cargoes_released_act.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="upload_excel">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel4">Upload Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Download Template <a href="gen_template_cargoe_released.php">Here</a></label>
                    </div>
                    <div class="form-group">
                        <label for="text_4_1">Upload File<span class="text-danger"> *</span> </label>
                        <input type="file" class="form-control" name="text_4_1" id="text_4_1" accept=".csv" title="Only .CSV file is allowed">
                        <small class="text-danger" id="fileError" style="display: none;">Only .CSV files are allowed.</small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>

<script>
  $(function(){ 


    var tbl_1 = "";
    let minDate,  maxDate;
    let expandedRow = null;

    $('#text_2').select2({
        ajax: {
            url: 'select2_ports.php',  // Your PHP file that fetches the data
            dataType: 'json',
            delay: 250, // Wait 250ms before making the request
            data: function (params) {
                return {
                    q: params.term, // Search term
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items, // data from PHP (example format)
                    pagination: {
                        more: (params.page * 30) < data.total_count // Check if more results exist
                    }
                };
            },
            cache: true
        },
        placeholder: 'Search for an option',
        // minimumInputLength: 1,
        allowClear: true
    });

    $('#text_3').select2({
        ajax: {
            url: 'select2_consignee.php',  // Your PHP file that fetches the data
            dataType: 'json',
            delay: 250, // Wait 250ms before making the request
            data: function (params) {
                return {
                    q: params.term, // Search term
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items, // data from PHP (example format)
                    pagination: {
                        more: (params.page * 30) < data.total_count // Check if more results exist
                    }
                };
            },
            cache: true
        },
        placeholder: 'Search for an option',
        // minimumInputLength: 1, 
        allowClear: true
    });

    $('#text_6').select2({
        ajax: {
            url: 'select2_item.php',  // Your PHP file that fetches the data
            dataType: 'json',
            delay: 250, // Wait 250ms before making the request
            data: function (params) {
                return {
                    q: params.term, // Search term
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items, // data from PHP (example format)
                    pagination: {
                        more: (params.page * 30) < data.total_count // Check if more results exist
                    }
                };
            },
            cache: true
        },
        placeholder: 'Search for an option',
        // minimumInputLength: 1, 
        allowClear: true
    });


    $('#country-filter').on('keyup change', function () {
        var countryFilter = $(this).val().toLowerCase(); // Get input value
        table.columns(2).search(countryFilter).draw(); // Replace '3' with your country column index
    });
    $('#consignee-filter').on('keyup change', function () {
        var consigneeFilter = $(this).val().toLowerCase(); // Get input value
        table.columns(3).search(consigneeFilter).draw(); // Replace '3' with your country column index
    });
    $('#flightno-filter').on('keyup change', function () {
        var flightnoFilter = $(this).val().toLowerCase(); // Get input value
        table.columns(4).search(flightnoFilter).draw(); // Replace '3' with your country column index
    });
    $('#airwaybill-filter').on('keyup change', function () {
        var airwaybillFilter = $(this).val().toLowerCase(); // Get input value
        table.columns(5).search(airwaybillFilter).draw(); // Replace '3' with your country column index
    });
    $('#items-filter').on('keyup change', function () {
        var itemFilter = $(this).val().toLowerCase(); // Get input value
        table.columns(14).search(itemFilter).draw(); // Replace '3' with your country column index
    });    
    
    $('#hscode-filter').on('keyup change', function () {
        var hscodeFilter = $(this).val().toLowerCase(); // Get input value
        table.columns(7).search(hscodeFilter).draw(); // Replace '3' with your country column index
    });

    $('#branch-filter').on('keyup change', function () {
        var branchFilter = $(this).val().toLowerCase(); // Get input value
        table.columns(15).search(branchFilter).draw(); // Replace '3' with your country column index
    });


    function format(d) {
            // `d` is the original data object for the row
        return (
            '<dl>' +
            
            
                '<dt>QUANTITY: </dt>' +
                '<dd>' +
                d.row12 +
                '</dd>' +

                '<dt>HS CODE: </dt>' +
                '<dd>' +
                d.row11 +
                '</dd>' +

                '<dt>Gross Weight: </dt>' +
                '<dd>' +
                d.row14 +
                '</dd>' +

                '<dt>Date of Arrival: </dt>' +
                '<dd>' +
                d.row15 +
                '</dd>' +

                '<dt>Remarks: </dt>' +
                '<dd>' +
                d.row16 +
                '</dd>' +

                '<dt>Chargeable Weight: </dt>' +
                '<dd>' +
                d.row18 +
                '</dd>' +


                '<dt>Actual Released Date</dt>' +
                '<dd>' +
                d.row19 +
                '</dd>' +

                '<dt>Branch/ Facility: </dt>' +
                '<dd>' +
                d.row22 +
                '</dd>' +

                '<dt>Remarks: </dt>' +
                '<dd>' +
                d.row16 +
                '</dd>' +
            '</dl>'
            );
    }

    var table = $('#tbl_1').DataTable( {
        dom: 'Bfrtip', // Layout of the table with buttons
        // dom: 'lBfrtip',  
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        "buttons": ["copy", "csv", "excel"],
        'ajax': {
            'method' : 'POST',
            'url'    :'cargoes_released_act.php',
            'data'   : {
                        tbl_1
                        },
        },
    //     initComplete: function () {
    //     this.api()
    //         .columns()
    //         .every(function () {
    //             let column = this;
    //             let title = column.footer().textContent;
 
    //             // Create input element
    //             let input = document.createElement('input');
    //             input.placeholder = title;
    //             column.footer().replaceChildren(input);
 
    //             // Event listener for user input
    //             input.addEventListener('keyup', () => {
    //                 if (column.search() !== this.value) {
    //                     column.search(input.value).draw();
    //                 }
    //             });
    //         });
    // },
        'columns': [
            {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: ''
            },
            { data: 'row1' ,visible :false, "width" :'20px' },
            { data: 'row3' },
            { data: 'row5' },
            { data: 'row20' },
            { data: 'row7' },
            {
                data: 'row8',
                render: function (data, type, row) {
                    if (data) {
                        return `
                            ${row.row9} <hr>
                            <img src="../images/items/${data}" alt="Item Image" 
                                class="clickable-image" 
                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"
                                data-full="../images/items/${data}">
                        `;
                    } else {
                        return `<span class="text-muted">No Image</span>`;
                    }
                }
            },
            { data: 'row11', visible :false },
            { data: 'row12', visible :false },
            { data: 'row13' },
            { data: 'row14', visible :false },
            { data: 'row15', visible :false },
            { data: 'row16', visible :false },
            { data: 'row23', visible :false},
            { data: 'row22', visible :false},
            { data: 'row18', visible :false},
        ],
        'order'  :   [[ 1, 'desc']],
        
    });
    DataTable.ext.search.push(function (settings, data, dataIndex) {
        let min = minDate.val();
        let max = maxDate.val();
        let date = new Date(data[11]);
    
        if (
            (min === null && max === null) ||
            (min === null && date <= max) ||
            (min <= date && max === null) ||
            (min <= date && date <= max)
        ) {
            return true;
        }
        return false;
    });


    
    minDate = new DateTime('#min', {
        format: 'MMMM Do YYYY'
    });
    maxDate = new DateTime('#max', {
        format: 'MMMM Do YYYY'
    });
    
  
    document.querySelectorAll('#min, #max').forEach((el) => {

        el.addEventListener('change', () => table.draw());
    });


    table.on('click', 'td.dt-control', function (e) {
        let tr = e.target.closest('tr');
        let row = table.row(tr);

        // If a row is already expanded and it's not the clicked row, collapse it
        if (expandedRow && expandedRow !== row) {
            expandedRow.child.hide();
            $(expandedRow.node()).removeClass('shown'); // Optional: Update class for styling if needed
            expandedRow = null;
        }

        // Check if the clicked row is already expanded
        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            $(tr).removeClass('shown'); // Optional: Update class for styling if needed
        } else {
            // Open this row and set it as the expandedRow
            row.child(format(row.data())).show();
            $(tr).addClass('shown'); // Optional: Update class for styling if needed
            expandedRow = row; // Set this as the currently expanded row
        }
    });


    // table.on('click', 'td.dt-control', function (e) {
    //     let tr = e.target.closest('tr');
    //     let row = table.row(tr);
    
    //     if (row.child.isShown()) {
            
    //         row.child.hide();
    //     }
    //     else {

    //         row.child(format(row.data())).show();
    //     }
    // });

    $('#tbl_1 tbody').on( 'click', '.edit_btn', function () {
        // $('#cover-spin').show(0);
        var data = table.row( $(this).parents('tr') ).data();
        $("#operation2").val("edit");

        $("#text_2_1").val(data['row1']);

        $('#mdl_2').modal('show');
        // $('#cover-spin').hide(0);
    } );
    $('#tbl_1 tbody').on( 'click', '.delete_btn', function () {
       
        var data = table.row( $(this).parents('tr') ).data();
        Swal.fire({
            
            title:"Are you sure?",
            text:`Remove ${data['row2']} ?`,
            icon:"warning",
            showCancelButton:!0,
            confirmButtonColor:"#1c84ee",
            cancelButtonColor:"#fd625e",
            confirmButtonText:"Yes, delete it!"})
        .then(function(e){
            if(e.isConfirmed)  {
                let operation = "delete";
                let text_1 = data['row1'];
                $.ajax({
                    url : "setup_port_act.php",
                    method : "post",
                    dataType : "json",
                    data  :{
                      operation, text_1
                    },
                    // beforeSend : function() {
                    //     $('#cover-spin').show(0);
                    // },
                    success : function (response) {
                      response_return(response[0], response[1], response[2], "nomodal", "tbl_1", "noform");
                        // if(response[0] == "success") {
                        //     e.value&&Swal.fire("Deleted!",response[2],response[0]);
                        //     $(`.tbl_announcement`).DataTable().ajax.reload(null, false);
                        //     // $('#cover-spin').hide(0);
                        // }else {
                        //     // $('#cover-spin').hide(0);
                        // }
          
                    }
    
                });
    
            }
    
        });
        // $('#cover-spin').hide(0);
    } );
    $('#tbl_1 tbody').on( 'click', '.return_btn', function () {
       
       var data = table.row( $(this).parents('tr') ).data();
       Swal.fire({
           
           title:"Are you sure?",
           text:`Return as Active ${data['row2']} ?`,
           icon:"warning",
           showCancelButton:!0,
           confirmButtonColor:"#1c84ee",
           cancelButtonColor:"#fd625e",
           confirmButtonText:"Yes, Return as Active!"})
       .then(function(e){
           if(e.isConfirmed)  {
               let operation = "delete";
               let text_1 = data['row1'];
               $.ajax({
                   url : "setup_port_act.php",
                   method : "post",
                   dataType : "json",
                   data  :{
                     operation, text_1
                   },
                   // beforeSend : function() {
                   //     $('#cover-spin').show(0);
                   // },
                   success : function (response) {
                     response_return(response[0], response[1], response[2], "nomodal", "tbl_1", "noform");
                       // if(response[0] == "success") {
                       //     e.value&&Swal.fire("Deleted!",response[2],response[0]);
                       //     $(`.tbl_announcement`).DataTable().ajax.reload(null, false);
                       //     // $('#cover-spin').hide(0);
                       // }else {
                       //     // $('#cover-spin').hide(0);
                       // }
         
                   }
   
               });
   
           }
   
       });
       // $('#cover-spin').hide(0);
   } );


    $(document).on("click", "#btn_add", function() {
      $("#mdl_1").modal("show");
      $("#operation").val("add");
    });
    $(document).on("click", "#btn_filter", function() {
        $("#mdl_3").modal("show");
    });
    $(document).on("click", "#btn_clear_filter", function() {
  
        location.reload();
    });
    $(document).on("click", "#btn_download_template", function() {
        $("#mdl_4").modal("show");
    });


    $('form#frm_1').validate({
        rules: {
            text_2: {
                required: true,
            },
            text_3: {
                required: true,
            },
            text_4: {
                required: true,
            },
            text_5: {
                required: true,
                number:true
            },
            text_4: {
                required: true,
            },
            text_6: {
                required: true,
            },
            text_7: {
                required: true,
            },
            text_8: {
                required: true,
                number:true
            },
            text_9: {
                required: true,
            },
            text_10: {
                required: true,
            },
            text_11: {
                required: true,
            },
            
        },
        messages: {
            text_2: {
                required: "Port of Discharge is Required.",
            },
            text_3: {
                required: "Consignee is Required.",
            },
            text_4: {
                required: "Flight No. is Required.",
            },   
            text_5: {
                required: "Airway Bill No. is Required.",
                number: "Only Number is Allowed",
            },
            text_6: {
                required: "Item is Required.",
            },
            text_7: {
                required: "HS CODE is Required.",
            },
            text_8: {
                required: "Quantity is Required.",
                number: "Only Numbers is Allowed"
            },
            text_9: {
                required: "Duties and Taxes is Required.",
            },
            text_10: {
                required: "Gross Weight (KGS) is Required.",
            },
            text_11: {
                required: "Actual Date of Arrival is Required.",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                dataType: "json",
                beforeSend: function () {
                    $("#btn1").addClass("d-none");
                },
                success: function (response) {
                    $("#btn1").removeClass("d-none");
                    response_return(response[0], response[1], response[2], "mdl_1", "tbl_1", "frm_1");
                },
            });
        }
    });
    
    
    $('form#frm_2').validate({
        rules: {
            text_2_2: {
                required: true,
                number:true
            },
            text_2_3: {
                required: true,
            },
            text_2_4: {
                required: true,
            }
        },
        messages: {
            text_2_2: {
                required: "Chargeable Weight is Required.",
                number: "Only Number is Allowed",
            },
            text_2_3: {
                required: "Actual Released Date is Required.",
            },
            text_2_4: {
                required: "Actual Released Time is Required.",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                dataType: "json",
                beforeSend: function () {
                    $("#btn2").addClass("d-none");
                },
                success: function (response) {
                    // alert(response);
                    $("#btn2").removeClass("d-none");
                    response_return(response[0], response[1], response[2], "mdl_2", "tbl_1", "frm_2");
                },
            });
        }
    });
     
    $('form#frm_4').validate({
        rules: {
            text_4_1: {
                required: true,
            }
        },
        messages: {
            text_4_1: {
                required: "Please Upload the file Here",
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            let formData = new FormData(form); // Create FormData object

            $.ajax({
                url: form.action,
                type: form.method,
                data: formData, 
                contentType: false, // Important: Prevent jQuery from setting content type
                processData: false, // Important: Prevent jQuery from converting data
                dataType : "json",
                success: function (response) {
                    response_return(response[0], response[1], response[2], "mdl_4", "tbl_1", "frm_4");
                },
                error: function (xhr, status, error) {
                    response_return("danger", "Error", xhr.responseText, "no_modal", "no_table", "no_form");
                }
            });
        }
    });
  });

</script>