//Apply the colors in respective status

document.addEventListener("DOMContentLoaded", function () {
  const statusCells = document.querySelectorAll(
    ".table.table-hover tbody tr td:nth-last-of-type(4)"
  );

  statusCells.forEach((statusCell) => {
    const status = statusCell.textContent.trim();

    if (status === "Passed") {
      statusCell.classList.add("table-success");
    } else if (status === "Failed") {
      statusCell.classList.add("table-danger");
    } else if (status === "Pending") {
      statusCell.classList.add("table-info");
    }
  });
});

//Listen to the toggle of name filter
document.addEventListener("DOMContentLoaded", function () {
  const descCheckbox = document.getElementById("flexSwitchCheckDesc");
  const ascCheckbox = document.getElementById("flexSwitchCheckAsc");

  descCheckbox.addEventListener("change", function (event) {
    event.preventDefault();
    if (this.checked) {
      ascCheckbox.checked = false;
      document.getElementById("nameFilterForm").sortName.value = "desc";
      document.getElementById("nameFilterForm").submit();
    } else {
      document.getElementById("nameFilterForm").submit();
    }
  });

  ascCheckbox.addEventListener("change", function (event) {
    event.preventDefault();
    if (this.checked) {
      descCheckbox.checked = false;
      document.getElementById("nameFilterForm").sortName.value = "asc";
      document.getElementById("nameFilterForm").submit();
    } else {
      document.getElementById("nameFilterForm").submit();
    }
  });
});

//Listen to the toggle of ID filter
document.addEventListener("DOMContentLoaded", function () {
  const oldestCheckbox = document.getElementById("flexSwitchCheckOldest");

  oldestCheckbox.addEventListener("change", function () {
    const form = document.getElementById("idFilterForm");
    const url = new URL(window.location.href);
    if (this.checked) {
      url.searchParams.set("sortID", "oldest");
    } else {
      url.searchParams.delete("sortID");
    }
    form.action = url.toString();
    form.submit();
  });
});

//Listen to the date filter exam inputs
$(function () {
  $('input[name="datefilter_examination"]').daterangepicker({
    autoUpdateInput: false,
    locale: {
      cancelLabel: "Clear",
    },
    ranges: {
      Today: [moment(), moment()],
      "Last 7 Days": [moment().subtract(6, "days"), moment()],
      "Last 30 Days": [moment().subtract(29, "days"), moment()],
      "Last Month": [
        moment().subtract(1, "month").startOf("month"),
        moment().subtract(1, "month").endOf("month"),
      ],
    },
  });

  $('input[name="datefilter_examination"]').on(
    "apply.daterangepicker",
    function (ev, picker) {
      $(this).val(
        picker.startDate.format("MM/DD/YYYY") +
          " - " +
          picker.endDate.format("MM/DD/YYYY")
      );
      var params = new URLSearchParams(window.location.search);
      params.set(
        "start_date_examination",
        picker.startDate.format("YYYY-MM-DD")
      );
      params.set("end_date_examination", picker.endDate.format("YYYY-MM-DD"));

      params.delete("date_preset_notification");
      params.delete("start_date_notification");
      params.delete("end_date_notification");

      window.location.search = params.toString();
    }
  );

  $('input[name="datefilter_examination"]').on(
    "cancel.daterangepicker",
    function (ev, picker) {
      $(this).val("");
      var params = new URLSearchParams(window.location.search);
      params.delete("start_date_examination");
      params.delete("end_date_examination");
      window.location.search = params.toString();
    }
  );

  var params = new URLSearchParams(window.location.search);
  if (
    params.has("start_date_examination") &&
    params.has("end_date_examination")
  ) {
    var start = moment(params.get("start_date_examination"));
    var end = moment(params.get("end_date_examination"));
    $('input[name="datefilter_examination"]').val(
      start.format("MM/DD/YYYY") + " - " + end.format("MM/DD/YYYY")
    );
  }
  //Listen to the date filter notification inputs
  $('input[name="datefilter_notification"]').daterangepicker({
    autoUpdateInput: false,
    locale: {
      cancelLabel: "Clear",
    },
    ranges: {
      Today: [moment(), moment()],
      "Last 7 Days": [moment().subtract(6, "days"), moment()],
      "Last 30 Days": [moment().subtract(29, "days"), moment()],
      "Last Month": [
        moment().subtract(1, "month").startOf("month"),
        moment().subtract(1, "month").endOf("month"),
      ],
    },
  });

  $('input[name="datefilter_notification"]').on(
    "apply.daterangepicker",
    function (ev, picker) {
      $(this).val(
        picker.startDate.format("MM/DD/YYYY") +
          " - " +
          picker.endDate.format("MM/DD/YYYY")
      );
      var params = new URLSearchParams(window.location.search);
      params.set(
        "start_date_notification",
        picker.startDate.format("YYYY-MM-DD")
      );
      params.set("end_date_notification", picker.endDate.format("YYYY-MM-DD"));

      params.delete("start_date_examination");
      params.delete("end_date_examination");

      window.location.search = params.toString();
    }
  );

  $('input[name="datefilter_notification"]').on(
    "cancel.daterangepicker",
    function (ev, picker) {
      $(this).val("");
      var params = new URLSearchParams(window.location.search);
      params.delete("start_date_notification");
      params.delete("end_date_notification");
      window.location.search = params.toString();
    }
  );

  if (
    params.has("start_date_notification") &&
    params.has("end_date_notification")
  ) {
    var startNotification = moment(params.get("start_date_notification"));
    var endNotification = moment(params.get("end_date_notification"));
    $('input[name="datefilter_notification"]').val(
      startNotification.format("MM/DD/YYYY") +
        " - " +
        endNotification.format("MM/DD/YYYY")
    );
  }

  $('input[name="datefilter_examination"]').on("keypress", function (e) {
    if (e.which === 13) {
      if ($(this).val().trim() === "") {
        $(this).trigger("cancel.daterangepicker");
      } else {
        $(this).trigger("apply.daterangepicker");
      }
    }
  });

  $('input[name="datefilter_notification"]').on("keypress", function (e) {
    if (e.which === 13) {
      if ($(this).val().trim() === "") {
        $(this).trigger("cancel.daterangepicker");
      } else {
        $(this).trigger("apply.daterangepicker");
      }
    }
  });
});

//Listen to the submit of date filters
document.addEventListener("DOMContentLoaded", function () {
  let formChanged = false;
  const statusForm = document.getElementById("statusForm");
  const nameFilterForm = document.getElementById("nameFilterForm");
  const idFilterForm = document.getElementById("idFilterForm");
  const offcanvas = document.getElementById("offcanvasNavbar");

  function handleFormChange() {
    formChanged = true;
  }

  function submitForms() {
    const dateExamination = document.querySelector(
      'input[name="datefilter_examination"]'
    ).value;
    const dateNotification = document.querySelector(
      'input[name="datefilter_notification"]'
    ).value;
    const statusChecked =
      Array.from(document.querySelectorAll('input[name="status[]"]:checked'))
        .length > 0;

    if (formChanged) {
      const formData = new FormData();

      [statusForm, nameFilterForm, idFilterForm].forEach((form) => {
        for (let pair of new FormData(form).entries()) {
          if (pair[1] !== "") {
            formData.append(pair[0], pair[1]);
          }
        }
      });

      if (dateExamination)
        formData.append(
          "start_date_examination",
          moment(dateExamination.split(" - ")[0], "MM/DD/YYYY").format(
            "YYYY-MM-DD"
          )
        );
      formData.append(
        "end_date_examination",
        moment(dateExamination.split(" - ")[1], "MM/DD/YYYY").format(
          "YYYY-MM-DD"
        )
      );
      if (dateNotification)
        formData.append(
          "start_date_notification",
          moment(dateNotification.split(" - ")[0], "MM/DD/YYYY").format(
            "YYYY-MM-DD"
          )
        );
      formData.append(
        "end_date_notification",
        moment(dateNotification.split(" - ")[1], "MM/DD/YYYY").format(
          "YYYY-MM-DD"
        )
      );

      const searchParams = new URLSearchParams(formData);
      const newUrl = searchParams.toString()
        ? window.location.pathname + "?" + searchParams.toString()
        : window.location.pathname;

      if (window.location.href !== newUrl) {
        window.location.href = newUrl;
      }

      formChanged = false;
    }
  }

  [statusForm, nameFilterForm, idFilterForm].forEach((form) => {
    form.addEventListener("change", handleFormChange);
  });

  document
    .querySelector('input[name="datefilter_examination"]')
    .addEventListener("change", handleFormChange);
  document
    .querySelector('input[name="datefilter_notification"]')
    .addEventListener("change", handleFormChange);

  offcanvas.addEventListener("hidden.bs.offcanvas", submitForms);
  window.addEventListener("beforeunload", submitForms);

  const urlParams = new URLSearchParams(window.location.search);
  const statusValues = urlParams.getAll("status[]");
  statusValues.forEach((value) => {
    const checkbox = document.querySelector(
      `input[name="status[]"][value="${value}"]`
    );
    if (checkbox) checkbox.checked = true;
  });
});

//Dropdown for exam venue
document.addEventListener("DOMContentLoaded", function () {
  const dropdownButton = document.querySelector(
    ".btn-outline-primary.dropdown-toggle"
  );
  const dropdownItems = document.querySelectorAll(".dropdown-item");
  const clearButton = document.querySelector(".btn-success"); // Select the Clear button

  function updateVenue(venue) {
    dropdownButton.textContent = venue || "Select an Exam Venue"; // Default text if no venue
    const url = new URL(window.location.href);
    if (venue) {
      url.searchParams.set("exam_venue", venue);
    } else {
      url.searchParams.delete("exam_venue"); // Remove exam_venue parameter
    }
    window.location.href = url.toString();
  }

  const urlParams = new URLSearchParams(window.location.search);
  const initialVenue = urlParams.get("exam_venue");
  if (initialVenue) {
    dropdownButton.textContent = initialVenue;
  }

  dropdownItems.forEach((item) => {
    item.addEventListener("click", function (e) {
      e.preventDefault();
      const selectedVenue = this.textContent;
      updateVenue(selectedVenue);
    });
  });

  // Add click event listener to the Clear button
  clearButton.addEventListener("click", function (e) {
    e.preventDefault();
    updateVenue(null); // Pass null to clear the venue
  });
});
