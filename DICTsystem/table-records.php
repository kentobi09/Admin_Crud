<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validate session token against database
try {
    $conn = new PDO("mysql:host=localhost;dbname=applicant-records", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve session token from database for current user
    $stmt = $conn->prepare("SELECT session_token FROM adminaccount WHERE adminID = :adminID");
    $stmt->bindParam(':adminID', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if session token in database matches session token in $_SESSION
    if (!$result || $result['session_token'] !== $_SESSION['session_token']) {
        // Invalid session, destroy session and redirect to login page
        session_destroy();
        header("Location: login.php?alert=session_conflict");
        exit();
    }

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>





<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Application Records</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <script src="main-script.js"></script>
    <style>
      .dropdown-item.selected {
        background-color: #e9ecef;
        font-weight: bold;
        }

        .delete-column,
        .sticky-id,
        .sticky-name {
          position: sticky;
          z-index: 2;
        }

        @media (min-width: 768px) {
          .delete-column {
            left: 0;

          }

          .sticky-id {
            left: 6.7rem;
          }

          .sticky-name {
            left: 8.1rem;
            font-weight: 700;
          }
        }

        @media (max-width: 767px) {
          .delete-column,
          .sticky-id,
          .sticky-name {
            left: unset; 
          }

          .delete-column {
            left:0;
          }

          .sticky-id {
            left: 3.55rem; 
          }

          .sticky-name {
            left: 5rem; 
            font-weight: 700;
          }
        }

        #header {
          margin-top: 4.5rem;
        }
        .score {
          text-align: right;
        }

        .invisible {
          opacity: 0;
          pointer-events:none;
        }

        .hide{
    display: none;
      }
      .applicant-sex {
        text-transform: capitalize;
      }
      table {
        border: 2px solid ;
      }
    </style>

  </head>
  <body>
<?php


?>
    <?php 
          
          include_once('first-connection.php'); ?>
    <?php include('fetch-scores.php');?>
    <?php include('filter-search.php');?>
    <?php include('addmodal.php'); ?>
    <?php include('dispaymodal.php'); ?>
    <?php include('editmodal.php'); ?>
    <?php include('deletemodal.php'); ?>
    <?php include('insert.php');?>

   
    <!-- All the Filters are applied here in the nav bar-->
    <nav class="navbar bg-body-tertiary fixed-top">
        <div class="container-fluid">
          <a class="navbar-brand" href="#"></a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div
            class="offcanvas offcanvas-end"
            tabindex="-1"
            id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel"
          >
            <div class="offcanvas-header">
              <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Filters</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close"
              ></button>
            </div>
            <div class="offcanvas-body">
            <form id="nameFilterForm" action="" method="GET">
              <div class="mb-3">
                <span class="fw-bold">Name</span>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDesc" name="sortName" value="desc" <?php echo isset($_GET['sortName']) && $_GET['sortName'] === 'desc' ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="flexSwitchCheckDesc">Sort Name (Z to A) <i class="bi bi-arrow-down"></i></label>
                </div>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckAsc" name="sortName" value="asc" <?php echo isset($_GET['sortName']) && $_GET['sortName'] === 'asc' ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="flexSwitchCheckAsc">Sort Name (A to Z) <i class="bi bi-arrow-up"></i></label>
                </div>
              </div>
            </form>

              <form id="idFilterForm" action="" method="GET">
                  <div class="mb-3">
                    <span class="fw-bold">ID</span>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckOldest" name="sortID" value="oldest" <?php echo isset($_GET['sortID']) && $_GET['sortID'] === 'oldest' ? 'checked' : ''; ?>>
                      <label class="form-check-label" for="flexSwitchCheckOldest">Oldest ID <i class="bi bi-arrow-down"></i></label>
                    </div>
                  </div>
              </form>

            <span class = "d-block fw-bold">Date of Examination</span>
            <input type="text" name="datefilter_examination" value="" />

            <span class = "d-block fw-bold">Date of Notification</span>
            <input type="text" name="datefilter_notification" value="" />

            <span class= "d-block fw-bold" >Status</span>
              <form id="statusForm">
                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="Passed" id="flexCheckPassed" name="status[]"
                            <?php echo (isset($_GET['status']) && in_array('Passed', $_GET['status'])) ? 'checked' : ''; ?>>
                      <label class="form-check-label" for="flexCheckPassed">
                          Passed
                      </label>
                  </div>

                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="Failed" id="flexCheckFailed" name="status[]"
                            <?php echo (isset($_GET['status']) && in_array('Failed', $_GET['status'])) ? 'checked' : ''; ?>>
                      <label class="form-check-label" for="flexCheckFailed">
                          Failed
                      </label>
                  </div>

                  <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="Pending" id="flexCheckPending" name="status[]"
                            <?php echo (isset($_GET['status']) && in_array('Pending', $_GET['status'])) ? 'checked' : ''; ?>>
                      <label class="form-check-label" for="flexCheckPending">
                          Pending
                      </label>
                  </div>
              </form>

              <span style="display:block;"><b>Exam Venue </b></span>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Select an Exam Venue
                    </button>
                    <button type="button" class="btn btn-success mx-3">Clear</button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" >hall-a</a></li>
                        <li><a class="dropdown-item" >hall-b</a></li>
                        <li><a class="dropdown-item" >hall-c</a></li>
                        <!-- <li><a class="dropdown-item" >Ilagan</a></li>
                        <li><a class="dropdown-item" >Echague</a></li>
                        <li><a class="dropdown-item" >Quirino</a></li> -->
                    </ul>
                </div>


            </div>
          </div>
        </div>
      </nav>

    <div class="container" id="header">
      <h1>Applicant Records</h1>
    </div>
    <div class="container my-3">
      <div class="d-flex justify-content-between my-4">
        <form class="d-flex" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
              <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        <div >
          <button  data-toggle="modal" data-target="#addApplicantModal" type="button" class="btn btn-primary mx-1" ><i class="bi bi-plus-lg"></i></button>
        </div>
      </div>
    </div>
    <div class="container px-0">
      <div class="table-responsive">
        <table class="table table-hover"> 
                <thead>
                <tr class="table-primary">
                  <th scope="col" class="delete-column"></th>
                  <th scope="col" class="sticky-id">ID</th>
                  <th scope="col" class="sticky-name">Name</th>
                  <th scope="col">Sex</th>
                  <th scope="col">Province</th>
                  <th scope="col">Date of Examination</th>
                  <th scope="col">Exam Venue</th>
                  <th scope="col">Date of Notification</th>
                  <th class= "hide" scope="col" colspan="4" class="text-center">Score</th>
                  <th scope="col">Proctor</th>
                  <th scope="col">Status</th>
                  <th class= "hide" scope="col">Email</th>
                  <th class= "hide" scope="col">Contact Number</th>
                  <th class= "hide" scope="col">Attached Form</th>
                </tr>
                <tr class= "hide" class="text-center table-primary">
                  <th class= "hide" scope="col" class="delete-column"></th>
                  <th class= "hide" scope="col" class="sticky-id"></th>
                  <th class= "hide" scope="col" class="sticky-name"></th>
                  <th class= "hide" scope="col"></th>
                  <th class= "hide" scope="col"></th>
                  <th class= "hide" scope="col"></th>
                  <th class= "hide" scope="col"></th>
                  <th class= "hide" scope="col"></th>
                  <th class= "hide" scope="col" class="align-top">Part 1</th>
                  <th class= "hide" scope="col" class="align-top">Part 2</th>
                  <th class= "hide" scope="col" class="align-top">Part 3</th>
                  <th class= "hide" scope="col" class="align-top">Total Score</th>
                  <th class= "hide" scope="col"></th>
                  <th class= "hide" scope="col"></th>
                  <th class= "hide" scope="col"></th>
                  <th  class= "hide"scope="col"></th>
                  <th class= "hide" scope="col"></th>
                </tr>
              </thead>

        <tbody id = "applicant-table-body">
            <?php if (!empty($applicant_record)): ?>
            <?php foreach ($applicant_record as $applicant): ?>
                <?php $hasApplicationForm = !empty($applicant['application_form']); ?>
                <tr data-applicant-id="<?= $applicant['applicantID'] ?>">
                    <td class="delete-column">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button onclick="deletexid(<?php echo $applicant['applicantID'] ?>)" type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteApplicantModal" data-applicantid="<?php echo $applicant['applicantID']; ?>">
                                <i class="bi bi-trash"></i>
                            </button>

                            <button class="btn btn-secondary edit-applicant-btn" type="button"
                                    data-toggle="modal" data-target="#editApplicantModal"
                                    data-id="<?php echo $applicant['applicantID']; ?>"
                                    data-fname="<?php echo htmlspecialchars($applicant['firstname']); ?>"
                                    data-mname="<?php echo htmlspecialchars($applicant['middlename']); ?>"
                                    data-lname="<?php echo htmlspecialchars($applicant['lastname']); ?>"
                                    data-sex="<?php echo htmlspecialchars($applicant['sex']); ?>"
                                    data-province="<?php echo htmlspecialchars($applicant['province']); ?>"
                                    data-contact="<?php echo htmlspecialchars($applicant['contact_number']); ?>"
                                    data-email="<?php echo htmlspecialchars($applicant['email_address']); ?>"
                                    data-notification="<?php echo htmlspecialchars($applicant['date_of_notification']); ?>"
                                    data-exam-date="<?php echo htmlspecialchars($applicant['date_of_examination']); ?>"
                                    data-exam-venue="<?php echo htmlspecialchars($applicant['exam_venue']); ?>"
                                    data-proctor="<?php echo htmlspecialchars($applicant['proctor']); ?>"
                                    data-status="<?php echo htmlspecialchars($applicant['status']); ?>"
                                    <?php if (isset($scores[$applicant['applicantID']])): ?>
                                        <?php $score = $scores[$applicant['applicantID']]; ?>
                                        data-score1="<?php echo htmlspecialchars($score['score1']); ?>"
                                        data-score2="<?php echo htmlspecialchars($score['score2']); ?>"
                                        data-score3="<?php echo htmlspecialchars($score['score3']); ?>"
                                        data-total="<?php echo htmlspecialchars($score['total']); ?>"
                                    <?php else: ?>
                                        data-score1=""
                                        data-score2=""
                                        data-score3=""
                                        data-total=""
                                    <?php endif; ?>
                                    data-application-form="<?php echo !empty($applicant['application_form']); ?>">
                                <i class="bi bi-pencil-square text-white"></i>
                            </button>
                        </div>
                    </td>
                    <td class="sticky-id applicant-id" ><?= htmlspecialchars($applicant['applicantID']) ?></td>
                    <td class="sticky-name applicant-name">
                      <?php ?>
                        <?= htmlspecialchars($applicant['firstname'].' '.$applicant['middlename'][0].'. '.$applicant['lastname']) ?>
                    </td>
                    <td class= "applicant-sex"              ><?= htmlspecialchars($applicant['sex']) ?></td>
                    <td class= "applicant-province"         ><?= htmlspecialchars($applicant['province']) ?></td>
                    <td class= "applicant-exam-date"        ><?= htmlspecialchars((new DateTime($applicant['date_of_examination']))->format('M d Y')) ?></td>
                    <td class= "applicant-exam-venue"       ><?= htmlspecialchars($applicant['exam_venue']) ?></td>
                    <td class= "applicant-notification-date"><?= htmlspecialchars((new DateTime($applicant['date_of_notification']))->format('M d Y')) ?></td>

                    <?php if (isset($scores[$applicant['applicantID']])): ?>
                        <?php $score = $scores[$applicant['applicantID']]; ?>
                        <td class="score applicant-score1 hide"><?= htmlspecialchars($score['score1']) ?></td>
                        <td class="score applicant-score2 hide"><?= htmlspecialchars($score['score2']) ?></td>
                        <td class="score applicant-score3 hide"><?= htmlspecialchars($score['score3']) ?></td>
                        <td class="score total-score hide" ><?= htmlspecialchars($score['total']) ?></td>
                    <?php else: ?>
                        <td colspan="4">No scores found</td>
                    <?php endif; ?>

                    <td class="proctor"       ><?= htmlspecialchars($applicant['proctor']) ?></td>
                    <td class="status"        ><?= htmlspecialchars($applicant['status']) ?></td>
                    <td class="email-address hide"><a href="mailto:<?= htmlspecialchars($applicant['email_address']) ?>"><?= htmlspecialchars($applicant['email_address']) ?></a></td>
                    <td class="contact-number hide"> <?= htmlspecialchars($applicant['contact_number']) ?></td>
                    <td class= "attachment hide" ><a target="_blank" href="applicant-form.php?id=<?= $applicant['applicantID'] ?>">attachment</a></td>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="delete-column">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-danger invisible" type="button" disabled><i class="bi bi-trash"></i></button>
                            <button class="btn btn-secondary invisible" type="button" disabled><i class="bi bi-pencil-square text-white"></i></button>
                        </div>
                    </td>
                    <td colspan="16" class="text-center">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
       </table>
              
       <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-md-center">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= $page <= 1 ? '#' : '?' . http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
              <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= $page >= $total_pages ? '#' : '?' . http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Next</a>
            </li>
          </ul>
        </nav>

        <a href="logout.php">Logout</a>
        <a href="history.php">History</a>

  </body>
</html>
