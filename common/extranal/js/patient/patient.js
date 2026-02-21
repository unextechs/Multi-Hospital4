"use strict";
$(document).ready(function () {
  "use strict";
  $(".table").on("click", ".editbutton", function () {
    "use strict";
    $("#loader").show();
    var iid = $(this).attr("data-id");
    $("#img").attr("src", "uploads/cardiology-patient-icon-vector-6244713.jpg");
    $("#editPatientForm").trigger("reset");
    $.ajax({
      url: "patient/editPatientByJason?id=" + iid,
      method: "GET",
      data: "",
      dataType: "json",
      success: function (response) {
        "use strict";
        $("#editPatientForm")
          .find('[name="id"]')
          .val(response.patient.id)
          .end();
        $("#editPatientForm")
          .find('[name="name"]')
          .val(response.patient.name)
          .end();
        $("#editPatientForm")
          .find('[name="password"]')
          .val(response.patient.password)
          .end();
        $("#editPatientForm")
          .find('[name="email"]')
          .val(response.patient.email)
          .end();
        $("#editPatientForm")
          .find('[name="address"]')
          .val(response.patient.address)
          .end();
        $("#editPatientForm")
          .find('[name="phone"]')
          .val(response.patient.phone)
          .end();
        $("#editPatientForm")
          .find('[name="sex"]')
          .val(response.patient.sex)
          .end();
        $("#editPatientForm")
          .find('[name="birthdate"]')
          .val(response.patient.birthdate)
          .end();
        $("#editPatientForm")
          .find('[name="bloodgroup"]')
          .val(response.patient.bloodgroup)
          .end();
        $("#editPatientForm")
          .find('[name="p_id"]')
          .val(response.patient.patient_id)
          .end();

        // Populate age field from computed age_years
        if (typeof response.age_years !== "undefined" && response.age_years !== "") {
          $("#editPatientForm")
            .find('[name="age"]')
            .val(response.age_years)
            .end();
        }

        if (
          typeof response.patient.img_url !== "undefined" &&
          response.patient.img_url != ""
        ) {
          $("#img").attr("src", response.patient.img_url);
        }

        if (response.doctor !== null) {
          var option1 = new Option(
            response.doctor.name + "-" + response.doctor.id,
            response.doctor.id,
            true,
            true
          );
        } else {
          var option1 = new Option(" " + "-" + "", "", true, true);
        }
        $("#editPatientForm")
          .find('[name="doctor"]')
          .append(option1)
          .trigger("change");

        $(".js-example-basic-single.doctor")
          .val(response.patient.doctor)
          .trigger("change");




          $("#editPatientForm")
          .find('[name="national_id"]')
          .val(response.patient.national_id)
          .end();
        $("#editPatientForm")
          .find('[name="height"]')
          .val(response.patient.height)
          .end();
        $("#editPatientForm")
          .find('[name="weight"]')
          .val(response.patient.weight)
          .end();
        $("#editPatientForm")
          .find('[name="known_allergies"]')
          .val(response.patient.known_allergies)
          .end();
        $("#editPatientForm")
          .find('[name="medical_history"]')
          .val(response.patient.medical_history)
          .end();
        $("#editPatientForm")
          .find('[name="emergency_contact_name"]')
          .val(response.patient.emergency_contact_name)
          .end();
          $("#editPatientForm")
          .find('[name="emergency_contact_number"]')
          .val(response.patient.emergency_contact_number)
          .end();
        $("#myModal2").modal("show");
      },
      complete: function () {
        $("#loader").hide();
      },
    });
  });

  $(".table").on("click", ".inffo", function () {
    "use strict";
    $("#loader").show();
    var iid = $(this).attr("data-id");

    $(".patientIdClass").html("").end();
    $(".nameClass").html("").end();
    $(".emailClass").html("").end();
    $(".addressClass").html("").end();
    $(".genderClass").html("").end();
    $(".birthdateClass").html("").end();
    $(".bloodgroupClass").html("").end();
    $(".patientidClass").html("").end();
    $(".doctorClass").html("").end();
    $(".ageClass").html("").end();
    $(".phoneClass").html("").end();
    $(".national_id").html("").end();
    $(".height").html("").end();
    $(".weight").html("").end();
    $(".known_allergies").html("").end();
    $(".medical_history").html("").end();
    $(".emergency_contact_name").html("").end();
    $(".emergency_contact_number").html("").end();
    $.ajax({
      url: "patient/getPatientByJason?id=" + iid,
      method: "GET",
      data: "",
      dataType: "json",
      success: function (response) {
        "use strict";

        $(".patientIdClass").append(response.patient.id).end();
        $(".nameClass").append(response.patient.name).end();
        $(".emailClass").append(response.patient.email).end();
        $(".addressClass").append(response.patient.address).end();
        $(".phoneClass").append(response.patient.phone).end();
        $(".genderClass").append(response.patient.sex).end();
        $(".birthdateClass").append(response.patient.birthdate).end();
        // Show age in years if available, otherwise show computed age string
        if (typeof response.age_years !== "undefined" && response.age_years !== "") {
          $(".ageClass").append(response.age_years + " years").end();
        } else {
          $(".ageClass").append(response.age).end();
        }
        $(".bloodgroupClass").append(response.patient.bloodgroup).end();
        $(".patientidClass").append(response.patient.patient_id).end();

        $(".national_id").append(response.patient.national_id).end();
        $(".height").append(response.patient.height).end();
        $(".weight").append(response.patient.weight).end();
        $(".known_allergies").append(response.patient.known_allergies).end();
        $(".medical_history").append(response.patient.medical_history).end();
        $(".emergency_contact_name").append(response.patient.emergency_contact_name).end();
        $(".emergency_contact_number").append(response.patient.emergency_contact_number).end();

        if (response.doctor !== null) {
          $(".doctorClass").append(response.doctor.name).end();
        } else {
          $(".doctorClass").append("").end();
        }

        $("#img1").attr(
          "src",
          "uploads/cardiology-patient-icon-vector-6244713.jpg"
        );

        if (
          typeof response.patient.img_url !== "undefined" &&
          response.patient.img_url != ""
        ) {
          $("#img1").attr("src", response.patient.img_url);
        }

        $("#infoModal").modal("show");
      },
      complete: function () {
        $("#loader").hide();
      },
    });
  });
});

$(document).ready(function () {
  "use strict";
  var table = $("#editable-sample").DataTable({
    responsive: true,
    //   dom: 'lfrBtip',

    processing: true,
    serverSide: true,
    searchable: true,
    ajax: {
      url: "patient/getPatient",
      type: "POST",
    },
    scroller: {
      loadingIndicator: true,
    },
    dom:
      "<'row mb-3'<'col-sm-3'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",

    buttons: [
      { extend: "copyHtml5", exportOptions: { columns: [0, 1, 2] } },
      { extend: "excelHtml5", exportOptions: { columns: [0, 1, 2] } },
      { extend: "csvHtml5", exportOptions: { columns: [0, 1, 2] } },
      { extend: "pdfHtml5", exportOptions: { columns: [0, 1, 2] } },
      { extend: "print", exportOptions: { columns: [0, 1, 2] } },
    ],
    aLengthMenu: [
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, "All"],
    ],
    iDisplayLength: 100,
    order: [[0, "desc"]],

    language: {
      lengthMenu: "_MENU_",
      search: "_INPUT_",
      url: "common/assets/DataTables/languages/" + language + ".json",
    },
  });
  table.buttons().container().appendTo(".custom_buttons");
});

$(document).ready(function () {
  "use strict";
  $("#doctorchoose").select2({
    placeholder: select_doctor,
    allowClear: true,
    ajax: {
      url: "doctor/getDoctorinfo",
      type: "post",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          searchTerm: params.term, // search term
        };
      },
      processResults: function (response) {
        return {
          results: response,
        };
      },
      cache: true,
    },
  });
  $("#doctorchoose1").select2({
    placeholder: select_doctor,
    allowClear: true,
    ajax: {
      url: "doctor/getDoctorInfo",
      type: "post",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          searchTerm: params.term, // search term
        };
      },
      processResults: function (response) {
        return {
          results: response,
        };
      },
      cache: true,
    },
  });
});

$(document).ready(function () {
  "use strict";
  $(".flashmessage").delay(3000).fadeOut(100);
});

// =====================================================
// DOB <-> Age Auto-Sync Logic
// =====================================================
$(document).ready(function () {
  "use strict";

  // Helper: compute age in years from a date string
  function computeAgeFromDate(dateStr) {
    if (!dateStr) return "";
    var dob = new Date(dateStr);
    if (isNaN(dob.getTime())) return "";
    var today = new Date();
    var age = today.getFullYear() - dob.getFullYear();
    var m = today.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
      age--;
    }
    return age >= 0 ? age : "";
  }

  // Helper: compute a birthdate string (YYYY-MM-DD) from age in years
  function computeDateFromAge(ageYears) {
    if (ageYears === "" || ageYears === null || isNaN(ageYears)) return "";
    var age = parseInt(ageYears);
    if (age < 0 || age > 150) return "";
    var today = new Date();
    var birthYear = today.getFullYear() - age;
    var month = ("0" + (today.getMonth() + 1)).slice(-2);
    var day = ("0" + today.getDate()).slice(-2);
    return birthYear + "-" + month + "-" + day;
  }

  // Sync pairs: [birthdateSelector, ageSelector]
  var pairs = [
    ["#addModalBirthdate", "#addModalAge"],
    ["#editModalBirthdate", "#editModalAge"],
    ["#addNewBirthdate", "#addNewAge"]
  ];

  pairs.forEach(function (pair) {
    var birthdateSel = pair[0];
    var ageSel = pair[1];

    // When birthdate changes -> update age
    $(document).on("change dp.change", birthdateSel, function () {
      var dateVal = $(this).val();
      if (dateVal) {
        var age = computeAgeFromDate(dateVal);
        $(ageSel).val(age);
      }
    });

    // When age changes -> update birthdate
    $(document).on("change input", ageSel, function () {
      var ageVal = $(this).val();
      if (ageVal !== "" && !isNaN(ageVal)) {
        var dateStr = computeDateFromAge(ageVal);
        $(birthdateSel).val(dateStr);
      }
    });
  });
});
