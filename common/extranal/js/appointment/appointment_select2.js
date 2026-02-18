"use strict";
$(document).ready(function () {
  "use strict";
  $(".pos_client").hide();
  $(document.body).on("change", "#pos_select", function () {
    "use strict";
    var v = $(this).val();

    if (v && v !== "add_new") {
      $("#followup_buttons_container").show();
      $("#followup_status_text").html(
        '<i class="fas fa-spinner fa-spin mr-1"></i> Checking visit history...'
      );

      // Reset buttons to initial state
      $("#btn_new_appointment")
        .removeClass("btn-primary active")
        .addClass("btn-outline-primary")
        .css("opacity", "0.5")
        .prop("disabled", false);
      $("#btn_followup")
        .removeClass("btn-success active")
        .addClass("btn-outline-success")
        .css("opacity", "0.5")
        .prop("disabled", false);

      $.ajax({
        url: "patient/get_followup_status_ajax?id=" + v,
        method: "GET",
        dataType: "json",
        success: function (response) {
          if (response.success) {
            if (response.status === "followup") {
              $("#btn_followup")
                .removeClass("btn-outline-success")
                .addClass("btn-success active")
                .css("opacity", "1")
                .off('click')
                .on('click', function() {
                    // Quick add follow-up
                    var selectedDoctor = $("#adoctors").val();
                    if (confirm('Create a follow-up appointment for today?')) {
                        $.ajax({
                            url: 'appointment/addQuickFollowup',
                            type: 'POST',
                            data: { 
                                id: v, 
                                doctor: selectedDoctor,
                                csrf_test_name: $.cookie('csrf_cookie_name') 
                            },
                            dataType: 'json',
                            success: function(res) {
                                if (res.success) {
                                    alert(res.message);
                                    window.location.href = 'appointment/todays';
                                } else {
                                    alert(res.message);
                                }
                            }
                        });
                    }
                });
              $("#btn_new_appointment").css("opacity", "0.3");
              $("#followup_status_text").html(
                '<span class="text-success"><i class="fas fa-check-circle mr-1"></i> Patient last visit was on ' +
                  response.last_visit +
                  " (" +
                  response.days_since +
                  " days ago). Click Follow-up to quick-book for today.</span>"
              );
            } else {
              $("#btn_new_appointment")
                .removeClass("btn-outline-primary")
                .addClass("btn-primary active")
                .css("opacity", "1");
              $("#btn_followup").css("opacity", "0.3");
              if (response.last_visit) {
                $("#followup_status_text").html(
                  '<span class="text-primary"><i class="fas fa-info-circle mr-1"></i> Patient last visit was ' +
                    response.days_since +
                    " days ago. This is a New Appointment.</span>"
                );
              } else {
                $("#followup_status_text").html(
                  '<span class="text-primary"><i class="fas fa-info-circle mr-1"></i> No previous visit history found. This is a New Appointment.</span>'
                );
              }
            }
          } else {
            $("#followup_status_text").text("Error checking history");
          }
        },
        error: function () {
          $("#followup_status_text").text("Network error checking history");
        },
      });
    } else {
      $("#followup_buttons_container").hide();
    }

    if (v === "add_new") {
      $(".pos_client").show();
      $(".pos_client")
        .find('input[name="p_name"]')
        .prop("required", true);
      $(".pos_client")
        .find('input[name="p_phone"]')
        .prop("required", true);
      $(".pos_client")
        .find('input[name="p_birthdate"]')
        .prop("required", true);
      $(".pos_client")
        .find('select[name="p_gender"]')
        .prop("required", true);
    } else {
      $(".pos_client").hide();
      $(".pos_client")
        .find('input[name="p_name"]')
        .prop("required", false);
      $(".pos_client")
        .find('input[name="p_phone"]')
        .prop("required", false);
      $(".pos_client")
        .find('input[name="p_birthdate"]')
        .prop("required", false);
      $(".pos_client")
        .find('select[name="p_gender"]')
        .prop("required", false);
    }
  });
  $(".pos_client1").hide();
  $(document.body).on("change", "#pos_select1", function () {
    "use strict";
    var v = $("select.pos_select1 option:selected").val();
    if (v === "add_new") {
      $(".pos_client1").show();
      $(".pos_client1").find('input[name="p_name"]').prop('required', true);
      $(".pos_client1").find('input[name="p_phone"]').prop('required', true);
      $(".pos_client1").find('input[name="p_birthdate"]').prop('required', true);
      $(".pos_client1").find('select[name="p_gender"]').prop('required', true);
    } else {
      $(".pos_client1").hide();
      $(".pos_client1").find('input[name="p_name"]').prop('required', false);
      $(".pos_client1").find('input[name="p_phone"]').prop('required', false);
      $(".pos_client1").find('input[name="p_birthdate"]').prop('required', false);
      $(".pos_client1").find('select[name="p_gender"]').prop('required', false);
    }
  });
});

$(document).ready(function () {
  "use strict";
  $("#pos_select").select2({
    placeholder: select_patient,
    allowClear: true,
    dropdownParent: $("#myModal").length ? $("#myModal") : $(document.body),
    ajax: {
      url: "patient/getPatientinfoWithAddNewOption",
      type: "post",
      dataType: "json",
      delay: 250,
      data: function (params) {
        "use strict";
        return {
          searchTerm: params.term, // search term
          csrf_test_name: $.cookie('csrf_cookie_name')
        };
      },
      processResults: function (response) {
        "use strict";
        return {
          results: response,
        };
      },
      error: function(xhr, status, error) {
        "use strict";
        console.error('Select2 patient AJAX error:', status, error);
        return { results: [] };
      },
      cache: true,
    },
  });
  $("#pos_select1").select2({
    placeholder: select_patient,
    allowClear: true,
    dropdownParent: $("#myModal2").length ? $("#myModal2") : $(document.body),
    ajax: {
      url: "patient/getPatientinfoWithAddNewOption",
      type: "post",
      dataType: "json",
      delay: 250,
      data: function (params) {
        "use strict";
        return {
          searchTerm: params.term, // search term
          csrf_test_name: $.cookie('csrf_cookie_name')
        };
      },
      processResults: function (response) {
        "use strict";
        return {
          results: response,
        };
      },
      error: function(xhr, status, error) {
        "use strict";
        console.error('Select2 patient AJAX error:', status, error);
        return { results: [] };
      },
      cache: true,
    },
  });

  $("#adoctors").select2({
    placeholder: select_doctor,
    allowClear: true,
    dropdownParent: $("#myModal").length ? $("#myModal") : $(document.body),
    ajax: {
      url: "doctor/getDoctorInfo",
      type: "post",
      dataType: "json",
      delay: 250,
      data: function (params) {
        "use strict";
        return {
          searchTerm: params.term, // search term
          csrf_test_name: $.cookie('csrf_cookie_name')
        };
      },
      processResults: function (response) {
        "use strict";
        return {
          results: response,
        };
      },
      error: function(xhr, status, error) {
        "use strict";
        console.error('Select2 doctor AJAX error:', status, error);
        return { results: [] };
      },
      cache: true,
    },
  });
  $("#adoctors1").select2({
    placeholder: select_doctor,
    allowClear: true,
    dropdownParent: $("#myModal2").length ? $("#myModal2") : $(document.body),
    ajax: {
      url: "doctor/getDoctorInfo",
      type: "post",
      dataType: "json",
      delay: 250,
      data: function (params) {
        "use strict";
        return {
          searchTerm: params.term, // search term
          csrf_test_name: $.cookie('csrf_cookie_name')
        };
      },
      processResults: function (response) {
        "use strict";
        return {
          results: response,
        };
      },
      error: function(xhr, status, error) {
        "use strict";
        console.error('Select2 doctor AJAX error:', status, error);
        return { results: [] };
      },
      cache: true,
    },
  });
});










$(document).ready(function () {
  $("#visit_description").change(function () {
    var id = $(this).val();
    $("#visit_charges").val(" ");
    $.ajax({
      url: "doctor/getDoctorVisitCharges?id=" + id,
      method: "GET",
      dataType: "json",
      success: function (response) {
        var visit_charge = response.response.visit_charges;
        var discount_percent = response.settings.discount_percent;
        var discount_amount = (visit_charge * discount_percent) / 100;

        $("#visit_charges").val(visit_charge).end();
        $("#discount").val(discount_amount).end();
        $("#grand_total")
          .val(parseFloat(visit_charge - discount_amount))
          .end();
      },
    });
  });
  $("#discount").keyup(function () {
    var discount = $(this).val();
    var price = $("#visit_charges").val();
    $("#grand_total")
      .val(parseFloat(price - discount))
      .end();
  });
});


$(document).ready(function () {
  $("#visit_description1").change(function () {
    var id = $(this).val();
    $("#visit_charges1").val(" ");
    $.ajax({
      url: "doctor/getDoctorVisitCharges?id=" + id,
      method: "GET",
      dataType: "json",
      success: function(response) {
        var visit_charge = response.response.visit_charges;
        var discount_percent = response.settings.discount_percent;
        var discount_amount = (visit_charge * discount_percent) / 100;

        $("#visit_charges1").val(visit_charge).end();
        $("#discount1").val(discount_amount).end();
        $("#grand_total1")
          .val(parseFloat(visit_charge - discount_amount))
          .end();
      },
    });
  });
  $("#discount1").keyup(function () {
    var discount = $(this).val();
    var price = $("#visit_charges1").val();
    $("#grand_total1")
      .val(parseFloat(price - discount))
      .end();
  });
});






function cardValidation() {
  "use strict";
  var valid = true;
  var cardNumber = $("#card").val();
  var expire = $("#expire").val();
  var cvc = $("#cvv").val();

  $("#error-message").html("").hide();

  if (cardNumber.trim() == "") {
    valid = false;
  }

  if (expire.trim() == "") {
    valid = false;
  }
  if (cvc.trim() == "") {
    valid = false;
  }

  if (valid == false) {
    $("#error-message").html("All Fields are required").show();
  }

  return valid;
}
//set your publishable key
Stripe.setPublishableKey(publish);

//callback to handle the response from stripe
function stripeResponseHandler(status, response) {
  "use strict";
  if (response.error) {
    $("#submit-btn").show();
    $("#loader").css("display", "none");

    $("#error-message").html(response.error.message).show();
    $("#submit-btn").attr("disabled", false);
    $("#error-message").html(response.error.message).show();
  } else {
    var token = response["id"];
    if (token != null) {
      $("#token").val(token);
      $("#addAppointmentForm").append(
        "<input type='hidden' name='token' value='" + token + "' />"
      );
      $("#addAppointmentForm").submit();
    } else {
      alert("Please Check Your Card details");
      $("#submit-btn").attr("disabled", false);
    }
  }
}

function stripePay(e) {
  "use strict";
  e.preventDefault();
  var valid = cardValidation();

  if (valid == true) {
    $("#submit-btn").attr("disabled", true);
    $("#loader").css("display", "inline-block");
    var expire = $("#expire").val();
    var arr = expire.split("/");
    Stripe.createToken(
      {
        number: $("#card").val(),
        cvc: $("#cvv").val(),
        exp_month: arr[0],
        exp_year: arr[1],
      },
      stripeResponseHandler
    );

    return false;
  }
}
function cardValidation1() {
  var valid = true;
  var cardNumber = $("#card1").val();
  var expire = $("#expire1").val();
  var cvc = $("#cvv1").val();

  $("#error-message").html("").hide();

  if (cardNumber.trim() == "") {
    valid = false;
  }

  if (expire.trim() == "") {
    valid = false;
  }
  if (cvc.trim() == "") {
    valid = false;
  }

  if (valid == false) {
    $("#error-message").html("All Fields are required").show();
  }

  return valid;
}
//set your publishable key
Stripe.setPublishableKey(publish);

//callback to handle the response from stripe
function stripeResponseHandler1(status, response) {
  if (response.error) {
    //enable the submit button
    $("#submit-btn1").show();
    $("#loader").css("display", "none");
    //display the errors on the form
    $("#error-message").html(response.error.message).show();
  } else {
    //get token id
    var token = response["id"];
    //insert the token into the form
    $("#token").val(token);
    $("#editAppointmentForm").append(
      "<input type='hidden' name='token' value='" + token + "' />"
    );
    //submit form to the server
    $("#editAppointmentForm").submit();
  }
}

function stripePay1(e) {
  e.preventDefault();
  var valid = cardValidation1();

  if (valid == true) {
    $("#submit-btn1").attr("disabled", true);
    $("#loader").css("display", "inline-block");
    var expire = $("#expire1").val();
    var arr = expire.split("/");
    Stripe.createToken(
      {
        number: $("#card1").val(),
        cvc: $("#cvv1").val(),
        exp_month: arr[0],
        exp_year: arr[1],
      },
      stripeResponseHandler1
    );

    //submit from callback
    return false;
  }
}

if (payment_gateway == "2Checkout") {
  var successCallback = function (data) {
    "use strict";
    var myForm = document.getElementById("addAppointmentForm");

    $("#addAppointmentForm").append(
      "<input type='hidden' name='token' value='" +
      data.response.token.token +
      "' />"
    );

    myForm.submit();
  };
  // Called when token creation fails.
  var errorCallback = function (data) {
    "use strict";
    if (data.errorCode === 200) {
      tokenRequest();
    } else {
      alert(data.errorMsg);
    }
  };
  var tokenRequest = function () {
    "use strict";
    var expire = $("#expire").val();
    var expiresep = expire.split("/");
    var dateformat = moment(expiresep[1], "YY");
    var year = dateformat.format("YYYY");
    var args = {
      sellerId: merchant,
      publishableKey: publishable,
      ccNo: $("#card").val(),
      cvv: $("#cvv").val(),
      expMonth: expiresep[0],
      expYear: year,
    };
    console.log(
      $("#card").val() + "-" + $("#cvv").val() + expiresep[0] + year + merchant
    );

    TCO.requestToken(successCallback, errorCallback, args);
  };

  function twoCheckoutPay(e) {
    "use strict";
    e.preventDefault();

    TCO.loadPubKey("sandbox", function () {
      // for sandbox environment
      publishableKey = publishable; //your public key
      tokenRequest();
    });

    return false;
  }

  var successCallback1 = function (data) {
    "use strict";
    var myForm = document.getElementById("editAppointmentForm");

    $("#editAppointmentForm").append(
      "<input type='hidden' name='token' value='" +
      data.response.token.token +
      "' />"
    );

    myForm.submit();
  };
  // Called when token creation fails.
  var errorCallback1 = function (data) {
    "use strict";
    if (data.errorCode === 200) {
      tokenRequest1();
    } else {
      alert(data.errorMsg);
    }
  };
  var tokenRequest1 = function () {
    "use strict";
    var expire = $("#expire1").val();
    var expiresep = expire.split("/");
    var dateformat = moment(expiresep[1], "YY");
    var year = dateformat.format("YYYY");
    var args = {
      sellerId: merchant,
      publishableKey: publishable,
      ccNo: $("#card1").val(),
      cvv: $("#cvv1").val(),
      expMonth: expiresep[0],
      expYear: year,
    };
    console.log(
      $("#card1").val() +
      "-" +
      $("#cvv1").val() +
      expiresep[0] +
      year +
      merchant
    );

    TCO.requestToken(successCallback, errorCallback, args);
  };

  function twoCheckoutPay1(e) {
    "use strict";
    e.preventDefault();

    TCO.loadPubKey("sandbox", function () {
      // for sandbox environment
      publishableKey = publishable; //your public key
      tokenRequest1();
    });

    return false;
  }
}
