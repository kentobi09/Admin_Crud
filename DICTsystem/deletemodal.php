<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->

</head>
<body>
<!-- Delete Modal -->
<div class="modal fade" id="deleteApplicantModal" tabindex="-1" role="dialog" aria-labelledby="deleteApplicantModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteApplicantModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this applicant?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteApplicantForm" method="post" action="delete.php">
                    <!-- Hidden input for applicant ID -->
                    <input type="hidden" id="delete-applicantID" name="applicantID">

                    <button type="submit" name="delete_applicant" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
       function deletexid(x){ 
    document.getElementById('delete-applicantID').value=x;
  }
</script>




    <!-- Bootstrap JS and jQuery (optional but recommended) -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
