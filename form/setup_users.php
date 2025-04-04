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
                    <h1>Setup Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Maintenance</li>
                        <li class="breadcrumb-item active">Setup Users</li>
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
                    <button class="btn btn-primary" id="btn_add">Create</button>
                </h3>
            </div>
            <div class="card-body">
            <table id="tbl_1" class="table table-hover table-bordered table-striped tbl_1" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Image</th>
                            <th>Usertype</th>
                            <th>Email Address</th>
                            <th>Gender</th>
                            <th>Facility</th>
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
<div class="modal fade mdl_1" id="mdl_1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm_1" class="frm_1" action="setup_users_act.php" method="POST" enctype="multipart/form-data">
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
                        <label for="text_2">Suffix</label>
                        <input type="text" name="text_2" id="text_2" class="form-control capitalizeAllLetters">
                    </div>

                    <div class="form-group">
                        <label for="text_3">Lastname <span class="text-danger">*</span> </label>
                        <input type="text" name="text_3" id="text_3" class="form-control capitalizeAllLetters" required>
                    </div>
                    <div class="form-group">
                        <label for="text_5">Firstname <span class="text-danger">*</span></label>
                        <input type="text" name="text_5" id="text_5" class="form-control capitalizeAllLetters" required>
                    </div>
                    <div class="form-group">
                        <label for="text_6">Middlename</label>
                        <input type="text" name="text_6" id="text_6" class="form-control capitalizeAllLetters">
                    </div>
                    <div class="form-group">
                        <label for="text_7">Usertype <span class="text-danger">*</span></label>
                        <select id="text_7" class="form-control select2" name="text_7" required>
                            <option value="">Select an option</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="text_8">Email Address <span class="text-danger">*</span> </label>
                        <input type="text" name="text_8" id="text_8" class="form-control lowercaseAllLetters" required>
                    </div>
                    <div class="form-group">
                        <label for="text_9">Gender <span class="text-danger">*</span> </label>
                        <select id="text_9" class="form-control select2" name="text_9" required>
                            <option value="">Select an option</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="text_10">Facility <span class="text-danger">*</span> </label>
                        <select id="text_10" class="form-control select2" name="text_10">
                            <option value="">Select an option</option>
                        </select>
                    </div>
                    <div class="security">
                        <label for="" class="text-danger">Security</label>
                        <div class="form-group">
                            <label for="text_11">Password</label>
                            <input type="text" name="text_11" id="text_11" class="form-control" required>
                        </div>
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

<?php include 'includes/footer.php'; ?>
</body>
</html>

<script>
  $(function(){ 


    var tbl_1 = "";
    
    $('#text_10').select2({
        ajax: {
            url: 'select2_branch.php',  // Your PHP file that fetches the data
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


    $('#text_7').select2({
        ajax: {
            url: 'select2_usertype.php',  // Your PHP file that fetches the data
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

    $('#text_9').select2({
        ajax: {
            url: 'select2_gender.php',  // Your PHP file that fetches the data
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


    var table = $('#tbl_1').DataTable({
    'ajax': {
        'method': 'POST',
        'url': 'setup_users_act.php',
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
                        <img src="${data}" alt="Item Image" 
                             class="clickable-image" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"
                             data-full="${data}">
                    `;
                } else {
                    return `<span class="text-muted">No Image</span>`;
                }
            }
        },
        { data: 'row10' },
        { data: 'row11' },
        { data: 'row16' },
        // {
        //     data: 'row4',
        //     render: function (data, type, row) {
        //         if (data) {
        //             return `
        //                 <div class="text-truncate" style="max-width: 200px;" data-toggle="tooltip" title="${data}">
        //                     ${data}
        //                 </div>`;
        //         } else {
        //             return `<span class="text-muted">No Description</span>`;
        //         }
        //     }
        // },
        { data: 'row15' },
        {
            data: 'row13',
            render: function (data, type, row) {
                if (row.row13 == 1) {
                    return `<span class="badge badge-danger">Deleted</span>`;
                } else {
                    return `<span class="badge badge-success">Active</span>`;
                }
            }
        },
        {
            data: 'row8',
            render: function (data, type, row) {
                if (row.row8 == 1) {
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
        const itemId = rowData.row1;
        const itemSuffix = rowData.row7;
        const itemLastname = rowData.row4;
        const itemFirstname = rowData.row5; 
        const itemMiddlename = rowData.row6; 
        const itemUsertype = rowData.row9; 
        const itemUsertype_name = rowData.row10; 
        const itemEmail = rowData.row11; 
        const itemGender =  rowData.row12; 
        const itemGender_name =  rowData.row16; 
        const itemBranch =  rowData.row14; 
        const itemBranch_name =  rowData.row15; 
        const itemImage = rowData.row3; // Get item image URL


        
        // Populate the modal fields
        $('#text_1').val(itemId); 
        $('#text_2').val(itemSuffix); 
        $('#text_3').val(itemLastname); 
        $('#text_5').val(itemFirstname); 
        $('#text_6').val(itemMiddlename); 
        if (itemUsertype && itemUsertype_name) {
            const newOption = new Option(itemUsertype_name, itemUsertype, true, true);
            $('#text_7').append(newOption).trigger("change");
        }
        if (itemBranch && itemBranch_name) {
            const newOption = new Option(itemBranch_name, itemBranch, true, true);
            $('#text_10').append(newOption).trigger("change");
        }
        $(".security").addClass("d-none");
        $('#text_8').val(itemEmail); 
        if (itemGender && itemGender_name) {
            const newOption = new Option(itemGender_name, itemGender, true, true);
            $('#text_9').append(newOption).trigger("change");
        }

        
        $('#current_photo').val(itemImage);
        // Handle image preview - if image exists, show it
        if (itemImage) {
            $('#preview_image').attr('src', `${itemImage}`).show(); // Display the existing image
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
                    url : "setup_users_act.php",
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
                   url : "setup_users_act.php",
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
        $(".security").removeClass("d-none");
        $("#frm_1").trigger("reset");
        $(".select2").val(null).trigger("change"); // Reset to placeholder or default value
        $("#mdl_1").modal("show");
        $("#operation").val("add");
        $("#text_2").val(''); // Clear item name input
        $("#text_3").val(''); // Clear item description input
        $('#preview_image').hide(); // Hide image preview for new item
        $("#btn1").text("Save"); // Set button to "Save"
        $(".modal-title").text("Create Item"); // Set title to "Create Item"
    });



    $('form#frm_1').validate({
        rules: {
            text_3: {
                required: true,
            },
            text_5 : {
                required: true,
            },
            text_7 :{
                required: true,
            },
            text_8 :{
                required: true,
            },
            text_9 :{
                required: true,
            },
            text_10 : {
                 required: true,
            },
            text_11 : {
                required: true,
            }


        },
        messages: {
            text_3: {
                required: "Lastname is Required.",
            },
            text_5: {
                required: "Firstname is Required.",
            },
            text_7: {
                required: "Usertype is Required.",
            },
            text_8: {
                required: "Email Address is Required.",
            },
            text_9: {
                required: "Gender is Required.",
            },
            text_10: {
                required: "Facility is Required.",
            },
            text_11: {
                required: "Password is Required.",
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
     
  });

</script>