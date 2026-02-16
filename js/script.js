// Global functions
$(document).ready(function () {
  // Auto hide alerts after 5 seconds
  setTimeout(function () {
    $(".alert").fadeOut("slow");
  }, 5000);

  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]'),
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Confirmation for delete buttons
  $(".btn-delete").click(function (e) {
    e.preventDefault();
    var url = $(this).attr("href");
    confirmDelete(url);
  });
});

// Format currency
function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

// Print function
function printPage(selector) {
  var printContents = document.querySelector(selector).innerHTML;
  var originalContents = document.body.innerHTML;

  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
  location.reload();
}

// Export to Excel
function exportToExcel(tableId, filename) {
  var table = document.getElementById(tableId);
  var html = table.outerHTML;
  var url = "data:application/vnd.ms-excel," + escape(html);

  var link = document.createElement("a");
  link.download = filename + ".xls";
  link.href = url;
  link.click();
}

// Export to PDF (requires html2pdf library)
function exportToPDF(elementId, filename) {
  var element = document.getElementById(elementId);
  html2pdf()
    .from(element)
    .set({
      margin: 1,
      filename: filename + ".pdf",
      html2canvas: { scale: 2 },
      jsPDF: { orientation: "portrait" },
    })
    .save();
}

// Form validation
(function () {
  "use strict";
  window.addEventListener(
    "load",
    function () {
      var forms = document.getElementsByClassName("needs-validation");
      var validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener(
          "submit",
          function (event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add("was-validated");
          },
          false,
        );
      });
    },
    false,
  );
})();

// Search functionality
function searchTable(inputId, tableId) {
  var input = document.getElementById(inputId);
  var filter = input.value.toUpperCase();
  var table = document.getElementById(tableId);
  var tr = table.getElementsByTagName("tr");

  for (var i = 0; i < tr.length; i++) {
    var td = tr[i].getElementsByTagName("td");
    var found = false;

    for (var j = 0; j < td.length; j++) {
      if (td[j]) {
        var txtValue = td[j].textContent || td[j].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          found = true;
          break;
        }
      }
    }

    if (found) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }
  }
}
