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
                    <h1>Setup Origin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Maintenance</li>
                        <li class="breadcrumb-item active">Setup Origin</li>
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
                <h3 class="card-title">
                    <!-- Trigger Button for Modal -->
                    <button class="btn btn-primary" id="btn_add"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>

                    <button class="btn btn-primary" id="btn_download_template"><i class="fa fa-upload" aria-hidden="true"></i> Import Excel</button>
                </h3>
            </div>
            <div class="card-body">
            <table id="tbl_1" class="table table-hover table-bordered table-striped tbl_1" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Country of Origin Name</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal for Create Port -->
<div class="modal fade mdl_1" id="mdl_1"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_1" class="frm_1" action="setup_port_act.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Origin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="operation" id="operation">
                    <input type="hidden" name="text_1" id="text_1">
                    <div class="form-group">
                        <label for="text_2">Origin Name</label>
                        <input type="text" name="text_2" id="text_2" class="form-control capitalizeAllLetters" required>
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


<div class="modal fade mdl_4" id="mdl_4" role="dialog" aria-labelledby="exampleModalLabel4" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_4" class="frm_4" action="setup_port_act.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="upload_excel">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel4">Upload Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Download Template <a href="gen_template_origin.php">Here</a></label>
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
    
    var table = $('#tbl_1').DataTable( {
        
        'ajax': {
            'method' : 'POST',
            'url'    :'setup_port_act.php',
            'data'   : {
                        tbl_1
                        },
        },
        // "initComplete":function( settings, json){
        //     $('#cover-spin').hide(0);
        // },
        'columns': [
            { data: 'row1', visible: false },
            { data: 'row2' },
            { data: 'row3', render: function(data, type, row) {
                    if(row.row3 == 1) {
                            
                        return `<span class="badge badge-danger">Deleted</span>`;
                        
                    }else{
    
                      return `<span class="badge badge-success">Active</span>`;
                       
                            
                    }
              }
            },
            { data: 'row4', render: function(data, type, row) {
                    if(row.row3 == 1) {
                            
               
                      return `<div class="btn-group">
                                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                  </button>
                                  <div class="dropdown-menu" role="menu" style="">
                                    <a class="dropdown-item return_btn" href="#">Return as Active</a>
                                  </div>
                                </div>`;
                        
                    }else{
    
                        return `<div class="btn-group">
                                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                  </button>
                                  <div class="dropdown-menu" role="menu" style="">
                                    <a class="dropdown-item edit_btn" href="#">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger delete_btn" href="#">Delete</a>
                                  </div>
                                </div>`;
                            
                    }
                }
            }
            // { data: 'row3' }
            
        ],
        'order'  :   [[ 0, 'desc']],
    });
    $('#tbl_1 tbody').on( 'click', '.edit_btn', function () {
        // $('#cover-spin').show(0);
        var data = table.row( $(this).parents('tr') ).data();
        $("#operation").val("edit");
        $("#text_1").val(data['row1']);

        $("#text_2").val(data['row2']);
        $('#mdl_1').modal('show');
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

    $(document).on("click", "#btn_download_template", function() {
        $("#mdl_4").modal("show");
    });


    $('form#frm_1').validate({
      rules: {
        text_2: {
              required: true,
          },

      },
      messages: {
        text_2: {
              required: "Port Name is Required.",
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