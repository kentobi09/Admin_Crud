<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<!-- Edit Modal -->
<div class="modal fade" id="editApplicantModal" tabindex="-1" role="dialog" aria-labelledby="editApplicantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editApplicantModalLabel">Edit Applicant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editApplicantForm" method="post" action="update.php" enctype="multipart/form-data">
                    <!-- Hidden input for applicant ID -->
                    <input type="hidden" id="edit-applicantID" name="edit-applicantID">

                    <div class="row">
                        <!-- Left column for Personal and Examination Details -->
                        <div class="col-md-6 section">
                            <h4 class="section-title"><b>Personal Details</b></h4>
                            <div class="form-group">
                                <input type="text" class="form-control" id="edit-fname" name="edit-fname" placeholder="Firstname" required>
                                <input type="text" class="form-control my-3" id="edit-mname" name="edit-mname" placeholder="Middlename" required>
                                <input type="text" class="form-control" id="edit-lname" name="edit-lname" placeholder="Lastname" required>
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="edit-sex" name="edit-sex" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="edit-province" name="edit-province" required>
                                    <option value="">Select Province</option>
                                    <option value="Batanes">Batanes</option>
                                    <option value="Cagayan">Cagayan</option>
                                    <option value="Isabela">Isabela</option>
                                    <option value="Nueva Vizcaya">Nueva Vizcaya</option>
                                    <option value="Quirino">Quirino</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="edit-contactNumber" name="edit-contactNumber" placeholder="Contact Number">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" id="edit-emailAddress" name="edit-emailAddress" placeholder="Email Address">
                            </div>
                        </div>
                        <div class="col-md-6 section">
                            <h4 class="section-title"><b>Examination Details</b></h4>
                            <div class="form-group">
                                <input type="date" class="form-control" id="edit-notificationDate" name="edit-notificationDate" placeholder="mm/dd/yyyy" required>
                            </div>
                            <div class="form-group">
                                <input type="date" class="form-control" id="edit-examDate" name="edit-examDate" placeholder="mm/dd/yyyy"  required>
                            </div>
                            <div class="form-group">
                                <select  class="form-control" id="edit-examVenue" name="edit-examVenue"required>
                                <option value="">Select Exam Venue</option>
                                            <option value="hall-a">Hall A</option>
                                            <option value="hall-b">Hall B</option>
                                            <option value="hall-c">Hall C</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="edit-proctor" name="edit-proctor" placeholder="Proctor" required>
                            </div>
                            <div class="form-group">
                                <select class="form-control" id="edit-status" name="edit-status" required>
                                    <option value="">Select Status</option>
                                    <option value="Failed">Failed</option>
                                    <option value="Passed">Passed</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Score Details -->
                        <div class="col-md-6 section">
                            <h4 class="section-title"><b>Score Details</b></h4>
                            <div class="form-group">
                                <input type="text" class="form-control" id="edit-exam1Score" name="edit-exam1Score" placeholder="Exam 1" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="edit-exam2Score" name="edit-exam2Score" placeholder="Exam 2" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="edit-exam3Score" name="edit-exam3Score" placeholder="Exam 3" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="edit-totalScore" name="edit-totalScore" placeholder="Total Score" readonly required>
                            </div>
                        </div>
                        
                        <!-- Attached File -->
                        <div class="col-md-6 section">
                            <h4 class="section-title"><b>Attached File</b></h4>
                            <div class="form-group">
    <p><strong>Application Form:</strong> 
        <a id="current-applicantForm" href="#" onclick="viewApplicationForm(event)">View Attachment</a>
    </p>
    <input type="file" class="form-control-file" id="edit-applicantForm" name="edit-applicantForm" accept=".pdf">
</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="exid" id="edid" value="1">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editButtons = document.querySelectorAll('.edit-applicant-btn');

        editButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                console.log('Clicked edit button:', btn);

                var id = btn.getAttribute('data-id');
                console.log('Applicant ID:', id);

                var name = btn.getAttribute('data-name');
                console.log('Name:', name);

                // Output other data attributes for debugging

                // Populate modal fields as before
                // Ensure all fields are properly set based on the data retrieved
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
    // Find all edit buttons
    var editButtons = document.querySelectorAll('.edit-applicant-btn');

    // Attach click event listener to each edit button
    editButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Get data attributes from the button
            var id = btn.getAttribute('data-id');
            var fname = btn.getAttribute('data-fname');
            var mname = btn.getAttribute('data-mname');
            var lname = btn.getAttribute('data-lname');
            var sex = btn.getAttribute('data-sex');
            var province = btn.getAttribute('data-province');
            var contactNumber = btn.getAttribute('data-contact');
            var emailAddress = btn.getAttribute('data-email');
            var notificationDate = btn.getAttribute('data-notification');
            var examDate = btn.getAttribute('data-exam-date');
            var examVenue = btn.getAttribute('data-exam-venue');
            var proctor = btn.getAttribute('data-proctor');
            var status = btn.getAttribute('data-status');
            var score1 = btn.getAttribute('data-score1');
            var score2 = btn.getAttribute('data-score2');
            var score3 = btn.getAttribute('data-score3');
            var totalScore = btn.getAttribute('data-total');
            var hasApplicationForm = btn.getAttribute('data-application-form') === 'true';

            // Populate modal fields with data
            //------------------Modify here
            document.getElementById('edit-applicantID').value = id;
            document.getElementById('edit-fname').value = fname;
            document.getElementById('edit-mname').value = mname;
            document.getElementById('edit-lname').value = lname;
            document.getElementById('edit-sex').value = sex;
            document.getElementById('edit-province').value = province;
            document.getElementById('edit-contactNumber').value = contactNumber;
            document.getElementById('edit-emailAddress').value = emailAddress;
            document.getElementById('edit-notificationDate').value = notificationDate;
            document.getElementById('edit-examDate').value = examDate;
            document.getElementById('edit-examVenue').value = examVenue;
            document.getElementById('edit-proctor').value = proctor;
            document.getElementById('edit-status').value = status;
            document.getElementById('edit-exam1Score').value = score1;
            document.getElementById('edit-exam2Score').value = score2;
            document.getElementById('edit-exam3Score').value = score3;
            document.getElementById('edit-totalScore').value = totalScore;

            // Handle application form display or processing (e.g., displaying a link)
            var applicationFormIframe = document.getElementById('edit-applicationFormIframe');
            if (hasApplicationForm) {
                applicationFormIframe.src = 'applicant-form.php?id=' + id; // Assuming you have a link structure like this
                applicationFormIframe.style.display = 'block';
                
                
            } else {
                applicationFormIframe.src = ''; // Clear the iframe src
                applicationFormIframe.style.display = 'none';
            }
        });
    });
});

function viewApplicationForm(event) {
    event.preventDefault();
    var applicantId = document.getElementById('edit-applicantID').value;
    if (applicantId) {
        var url = 'applicant-form.php?id=' + encodeURIComponent(applicantId);
        window.open(url, '_blank');
    } else {
        alert('No application form available.');
    }
}

var id = btn.getAttribute('data-id');
            var hasApplicationForm = btn.getAttribute('data-application-form') === 'true';

            // ... existing code to populate other fields ...

            // Update the application form link visibility
            var currentApplicantFormLink = document.getElementById('current-applicantForm');
            if (hasApplicationForm) {
                currentApplicantFormLink.style.display = 'inline';
                currentApplicantFormLink.textContent = 'View Attachment';
            } else {
                currentApplicantFormLink.style.display = 'none';
                currentApplicantFormLink.textContent = 'No Attachment';
            }

            // Set the applicant ID in the hidden input
            document.getElementById('edit-applicantID').value = id;


</script>
