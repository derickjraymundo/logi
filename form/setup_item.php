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
                    <h1>Setup Item</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Maintenance</li>
                        <li class="breadcrumb-item active">Setup Item</li>
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
                            <th>Item Name</th>
                            <th>Image</th>
                            <th>Description</th>
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
            <form id="frm_1" class="frm_1" action="setup_item_act.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="operation" id="operation">
                    <input type="hidden" name="text_1" id="text_1">
                    <input type="hidden" name="current_photo" id="current_photo">
                    <div class="form-group">
                        <label for="text_4">Photo</label>
                        <input type="file" name="text_4" id="text_4" class="form-control">
                        <img id="preview_image" src="" alt="Image Preview" style="display:none; max-width: 100%; margin-top: 10px;" />

                    </div>

                    <div class="form-group">
                        <label for="text_2">Item Name</label>
                        <input type="text" name="text_2" id="text_2" class="form-control capitalizeAllLetters" required>
                    </div>

                    <div class="form-group">
                        <label for="text_3">Description</label>
                        <textarea name="text_3" id="text_3"  class="form-control"></textarea>
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

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="" alt="Full Image" style="max-width: 100%; height: auto; border-radius: 5px;">
            </div>
        </div>
    </div>
</div>

<div class="modal fade mdl_4" id="mdl_4" role="dialog" aria-labelledby="exampleModalLabel4" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_4" class="frm_4" action="setup_item_act.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="upload_excel">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel4">Upload Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Download Template <a href="gen_template_item.php">Here</a></label>
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
    
    $('#text_4').on('change', function (event) {
        const file = this.files[0];
        if (file) {
            const fileType = file.type;
            const validImageTypes = ["image/jpeg", "image/png", "image/jpg"];
            
            if ($.inArray(fileType, validImageTypes) < 0) {
                Swal.fire({
                    icon: "error",
                    title: "Invalid File Type",
                    text: "Please select a valid image file (JPEG, PNG, JPG)."
                });
                $(this).val(''); // Clear the file input
                $('#preview_image').hide(); // Hide the preview image
            } else {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#preview_image').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(file); // Read file as DataURL
            }
        } else {
            $('#preview_image').hide(); // Hide preview if no file selected
        }
    });



    var table = $('#tbl_1').DataTable({
        'ajax': {
            'method': 'POST',
            'url': 'setup_item_act.php',
            'data': { tbl_1 },
        },
        'columns': [
        { data: 'row1', visible: false },
        { data: 'row2' },
        {
            data: 'row3',
            render: function (data, type, row) {
                if (data) {
                    return `
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
        {
            data: 'row4',
            render: function (data, type, row) {
                if (data) {
                    return `
                        <div class="text-truncate" style="max-width: 200px;" data-toggle="tooltip" title="${data}">
                            ${data}
                        </div>`;
                } else {
                    return `<span class="text-muted">No Description</span>`;
                }
            }
        },
        {
            data: 'row5',
            render: function (data, type, row) {
                if (row.row5 == 1) {
                    return `<span class="badge badge-danger">Deleted</span>`;
                } else {
                    return `<span class="badge badge-success">Active</span>`;
                }
            }
        },
        {
            data: 'row6',
            render: function (data, type, row) {
                if (row.row5 == 1) {
                    return `<div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu" style="">
                                    <a class="dropdown-item return_btn" href="#">Return as Active</a>
                                </div>
                            </div>`;
                } else {
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
    ],
    'order': [[0, 'desc']],
    });

// Initialize Bootstrap tooltips
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();

        // Handle image click to show modal
        $(document).on('click', '.clickable-image', function () {
            var fullImageUrl = $(this).data('full');
            $('#imageModal .modal-body img').attr('src', fullImageUrl);
            $('#imageModal').modal('show');
        });
    });


    $(document).on("click", ".edit_btn", function() {
        const rowData = table.row($(this).parents('tr')).data(); // Get row data
        const itemId = rowData.row1; // Get item ID
        const itemName = rowData.row2; // Get item name
        const itemDescription = rowData.row4; // Get item description
        const itemImage = rowData.row3; // Get item image URL

        // Populate the modal fields
        $('#text_1').val(itemId); // Set hidden ID field
        $('#text_2').val(itemName); // Set item name
        $('#text_3').val(itemDescription); // Set item description
        $('#current_photo').val(itemImage);
        // Handle image preview - if image exists, show it
        if (itemImage) {
            $('#preview_image').attr('src', `../images/items/${itemImage}`).show(); // Display the existing image
            $('#text_4').prop('required', false); // Image input is not required
        } else {
            $('#preview_image').hide(); // Hide the preview if no image exists
            $('#text_4').prop('required', true); // Mark image input as required
        }

        // Set modal for Edit
        $("#operation").val("edit"); // Set operation to "edit"
        $("#btn1").text("Update"); // Set button to "Update"
        $(".modal-title").text("Edit Item"); // Set title to "Edit Item"

        // Show modal
        $("#mdl_1").modal("show");
    });

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
                    url : "setup_item_act.php",
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
                   url : "setup_item_act.php",
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
        $("#frm_1").trigger("change");
        $("#operation").val("add");
        $('#preview_image').hide(); // Hide image preview for new item
        $("#btn1").text("Save"); // Set button to "Save"
        $(".modal-title").text("Create Item"); // Set title to "Create Item"
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
              required: "Item Name is Required.",
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
            var formData = new FormData(form); // Create a FormData object to include file inputs.

            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false, // Prevent jQuery from automatically transforming the data.
                contentType: false, // Let the browser set the `Content-Type` header.
                dataType: "json",
                beforeSend: function () {
                    $("#btn1").addClass("d-none");
                },
                success: function (response) {
    
                    $("#btn1").removeClass("d-none");
                    response_return(response[0], response[1], response[2], "mdl_1", "tbl_1", "frm_1");
                },
                error: function (xhr, status, error) {
                    // console.log(status);
                    console.error("AJAX Error:", status, error);
                    $("#btn1").removeClass("d-none");
                }
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