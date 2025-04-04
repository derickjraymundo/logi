<?php 
    include "includes/session.php";
    include "includes/header.php";
?>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>
<style>
    .dataTables_filter {
    display: none;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Fee's and Balances</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Fee's and Balances</li>
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
        
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="search_id" id="search_id" placeholder="Student ID/ Name"> 
                        </div>
                        <div class="col-md-2">
                            <input type="button" class="btn btn-success" id="btn_search" value="Search">
                        </div>
                    </div>         
           
            </div>
            <div class="card-body">
           
            <div class="table-responsive">
                <table id="tbl_1" class="table table-hover table-bordered table-striped tbl_1" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="5" class="text-center">No data available</td></tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th></th>
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

<?php include 'includes/footer.php'; ?>
</body>
</html>

<script>
$(document).ready(function(){
    $("#btn_search").click(function(){
        var searchValue = $("#search_id").val().trim();

        if(searchValue === ""){
            response_return("error", "Name or Student ID is Required", "Please Enter Name or Student", "nomodal", "tbl_1", "noform");
            return;
        }

        $.ajax({
            url: "fees_and_balance_view_students.php", // ✅ Correct PHP file
            type: "POST",
            data: { search: searchValue },
            dataType: "json",
            success: function(response){
                console.log(response); // ✅ Debugging output

                var table = $("#tbl_1 tbody");
                table.empty(); // Clear previous data

                if(Array.isArray(response) && response.length > 0){
                    $.each(response, function(index, student){
                        var imgTag = student.photo ? 
                            "<img src='" + student.photo + "' width='50' height='50' style='border-radius: 50%;'>" : 
                            "No Photo";

                        var row = "<tr>" +
                            "<td>" + (index + 1) + "</td>" +
                            "<td>" + student.student_id + "</td>" + // ✅ Corrected key
                            "<td>" + imgTag + "</td>" +
                            "<td>" + student.fullname  + "</td>" +
                            "<td><a href='fees_and_balance_view_students_id?ids="+student.id+"' class='btn btn-primary'>View</button></td>" +
                            "</tr>";

                        table.append(row);
                    });
                } else {
                    table.append("<tr><td colspan='5' class='text-center'>No records found</td></tr>");
                }
            },
            error: function(xhr, status, error){
                console.error("AJAX Error: " + status + " - " + error);
            }
        });
    });
});

</script>