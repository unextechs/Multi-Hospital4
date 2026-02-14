"use strict";
$(document).ready(function (e) {
  "use strict";
  $("#save_as_draft").click(function () {
    $("input[name='type']").removeAttr("required");
    $("#pos_select").removeAttr("required");
    $("#add_doctor").removeAttr("required");
    $(".multi-select").removeAttr("required");
    $("#d_name").prop("required", false);
    $("#d_email").prop("required", false);
    $("#d_phone").prop("required", false);
    $("#p_birth").prop("required", false);
    $("#p_name").prop("required", false);
    $("#p_email").prop("required", false);
    $("#p_phone").prop("required", false);

    e.preventDefault;
  });
  var tot = 0;
  $(".ms-list").on("click", ".ms-selected", function () {
    "use strict";
    var idd = $(this).data("idd");
    $("#id-div" + idd).remove();
    $("#idinput-" + idd).remove();
    $("#categoryinput-" + idd).remove();
  });
  $.each($("select.multi-select option:selected"), function () {
    "use strict";
    var idd = $(this).data("idd");
    var qtity = $(this).data("qtity");
    if ($("#idinput-" + idd).length) {
    } else {
      if ($("#id-div" + idd).length) {
      } else {
        $("#editPaymentForm .qfloww").append(
          '<div class="remove1" id="id-div' +
          idd +
          '">  ' +
          '<i class="remove_attr fa fa-times" id="id-remove-' +
          idd +
          '" style="font-size:16px;color:red"></i> ' +
          $(this).data("cat_name") +
          " - " +
          currency +
          $(this).data("id") +
          "</div>"
        );
      }
      var input2 = $("<input>")
        .attr({
          type: "text",
          class: "remove",
          id: "idinput-" + idd,
          name: "quantity[]",
          value: qtity,
        })
        .appendTo("#editPaymentForm .qfloww");

      $("<input>")
        .attr({
          type: "hidden",
          class: "remove",
          id: "categoryinput-" + idd,
          name: "category_id[]",
          value: idd,
        })
        .appendTo("#editPaymentForm .qfloww");
    }
    $(document).ready(function () {
      "use strict";

      $("#idinput-" + idd).keyup(function () {
        "use strict";
        var qty = 0;
        var total = 0;
        $.each($("select.multi-select option:selected"), function () {
          var id1 = $(this).data("idd");
          qty = $("#idinput-" + id1).val();
          var ekokk = $(this).data("id");
          total = total + qty * ekokk;
        });
        tot = total;
        var discount = ($("#dis_id_percent").val() * tot) / 100;
        var vat_amount = $("#vat").val();
        var vat = (vat_amount * tot) / 100;
        var gross = tot - discount + vat;
        $("#editPaymentForm").find('[name="subtotal"]').val(tot).end();
        $("#editPaymentForm")
          .find('[name="discount"]')
          .val(discount.toFixed(2))
          .end();
        $("#editPaymentForm").find('[name="grsss"]').val(gross);
        $("#editPaymentForm").find('[name="vat_amount"]').val(vat).end();
        var amount_received = $("#amount_received").val();
        var change = amount_received - gross;
        $("#editPaymentForm").find('[name="change"]').val(change).end();
        var id = $("#id_pay").val() ? $("#id_pay").val() : null;
        if (id !== null) {
          $.ajax({
            url: "finance/getDepositByInvoiceId?id=" + id,
            method: "GET",
            data: "",
            dataType: "json",
            success: function (response) {
              var due = $("#gross").val() - response.response;
              $("#due").val(due);
            },
          });
        } else {
          $("#due").val($("#gross").val() - amount_received);
        }
      });
    });
    ("use strict");
    var sub_total = $(this).data("id") * $("#idinput-" + idd).val();
    tot = tot + sub_total;
  });
  ("use strict");
  var discount = ($("#dis_id_percent").val() * tot) / 100;
  // if (discount_type === "flat") {
  var vat_amount = $("#vat").val();
  var vat = (vat_amount * tot) / 100;
  var gross = tot - discount + vat;
  // } else {
  //   var vat = (vat_amount * tot) / 100;

  //   var gross = tot - (tot * discount) / 100 + vat;
  // }

  $("#editPaymentForm").find('[name="subtotal"]').val(tot).end();
  $("#editPaymentForm")
    .find('[name="discount"]')
    .val(discount.toFixed(2))
    .end();
  $("#editPaymentForm").find('[name="vat_amount"]').val(vat.toFixed(2)).end();
  $("#editPaymentForm").find('[name="grsss"]').val(gross);
  var amount_received = $("#amount_received").val();
  var change = gross - amount_received;
  $("#editPaymentForm").find('[name="change"]').val(change).end();
  var id = $("#id_pay").val() ? $("#id_pay").val() : null;

  if (id !== null) {
    $.ajax({
      url: "finance/getDepositByInvoiceId?id=" + id,
      method: "GET",
      data: "",
      dataType: "json",
      success: function (response) {
        var due = $("#gross").val() - response.response;
        $("#due").val(due);
      },
    });
  } else {
    $("#due").val($("#gross").val() - amount_received);
  }
});

$(document).ready(function () {
  "use strict";
  $("#dis_id").keyup(function () {
    "use strict";
    var val_dis = 0;
    var amount = 0;
    var ggggg = 0;

    amount = $("#subtotal").val();
    val_dis = this.value;
    var vat_amount = $("#vat").val();
    var vat = (vat_amount * amount) / 100;
    var discount = (val_dis * 100) / amount;
    ggggg = amount - val_dis + vat;

    $("#editPaymentForm").find('[name="grsss"]').val(ggggg);
    $("#editPaymentForm")
      .find('[name="percent_discount"]')
      .val(discount.toFixed(2));

    var amount_received = $("#amount_received").val();
    var change = amount_received - ggggg;
    $("#editPaymentForm").find('[name="change"]').val(change).end();
    var id = $("#id_pay").val() ? $("#id_pay").val() : null;
    if (id !== null) {
      $.ajax({
        url: "finance/getDepositByInvoiceId?id=" + id,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response) {
          var due = $("#gross").val() - response.response;
          $("#due").val(due);
        },
      });
    } else {
      $("#due").val($("#gross").val() - amount_received);
    }
  });
  $("#dis_id_percent").keyup(function () {
    "use strict";
    var val_dis = 0;
    var amount = 0;
    var ggggg = 0;
    amount = $("#subtotal").val();
    val_dis = this.value;
    var vat_amount = $("#vat").val();
    var vat = (vat_amount * amount) / 100;

    var discount = (amount * val_dis) / 100;
    ggggg = amount - (amount * val_dis) / 100 + vat;
    $("#editPaymentForm").find('[name="discount"]').val(discount);
    $("#editPaymentForm").find('[name="grsss"]').val(ggggg);
    // $("#editPaymentForm").find('[name="vat"]').val(vat);
    var amount_received = $("#amount_received").val();
    var change = amount_received - ggggg;
    $("#editPaymentForm").find('[name="change"]').val(change).end();
    var id = $("#id_pay").val() ? $("#id_pay").val() : null;
    if (id !== null) {
      $.ajax({
        url: "finance/getDepositByInvoiceId?id=" + id,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response) {
          var due = $("#gross").val() - response.response;
          $("#due").val(due);
        },
      });
    } else {
      $("#due").val($("#gross").val() - amount_received);
    }
  });
});

$(document).ready(function () {
  "use strict";

  $(document.body).on("change", ".multi-select", function () {
    "use strict";
    var tot = 0;

    $(".ms-list").on("click", ".ms-selected", function () {
      "use strict";
      var idd = $(this).data("idd");
      $("#id-div" + idd).remove();
      $("#idinput-" + idd).remove();
      $("#categoryinput-" + idd).remove();
    });
    $.each($("select.multi-select option:selected"), function () {
      "use strict";
      var curr_val = $(this).data("id");
      var idd = $(this).data("idd");

      var cat_name = $(this).data("cat_name");
      if ($("#idinput-" + idd).length) {
      } else {
        if ($("#id-div" + idd).length) {
        } else {
          $("#editPaymentForm .qfloww").append(
            '<div class="remove1" id="id-div' +
            idd +
            '">  ' +
            '<i class="remove_attr fa fa-times" id="id-remove-' +
            idd +
            '" style="font-size:16px;color:red"></i> ' +
            $(this).data("cat_name") +
            " - " +
            currency +
            $(this).data("id") +
            "</div>"
          );
        }

        var input2 = $("<input>")
          .attr({
            type: "text",
            class: "remove",
            id: "idinput-" + idd,
            name: "quantity[]",
            value: "1",
          })
          .appendTo("#editPaymentForm .qfloww");

        $("<input>")
          .attr({
            type: "hidden",
            class: "remove",
            id: "categoryinput-" + idd,
            name: "category_id[]",
            value: idd,
          })
          .appendTo("#editPaymentForm .qfloww");
      }

      $(document).ready(function () {
        "use strict";
        $("#idinput-" + idd).keyup(function () {
          "use strict";

          var qty = 0;
          var total = 0;
          $.each($("select.multi-select option:selected"), function () {
            var id1 = $(this).data("idd");
            qty = $("#idinput-" + id1).val();
            var ekokk = $(this).data("id");
            total = total + qty * ekokk;
          });

          tot = total;

          // var discount = $("#dis_id").val();
          var discount = (tot * $("#dis_id_percent").val()) / 100;
          var vat_amount = $("#vat").val();
          var vat = (vat_amount * tot) / 100;
          var gross = tot - discount + vat;

          $("#editPaymentForm").find('[name="subtotal"]').val(tot).end();
          $("#editPaymentForm").find('[name="discount"]').val(discount).end();
          $("#editPaymentForm").find('[name="vat_amount"]').val(vat).end();
          $("#editPaymentForm").find('[name="grsss"]').val(gross);

          var amount_received = $("#amount_received").val();
          var change = amount_received - gross;
          $("#editPaymentForm").find('[name="change"]').val(change).end();
          var asdid = $("#id_pay").val() ? $("#id_pay").val() : null;
          if (asdid !== null) {
            $.ajax({
              url: "finance/getDepositByInvoiceId?id=" + asdid,
              method: "GET",
              data: "",
              dataType: "json",
              success: function (response) {
                var due = $("#gross").val() - response.response;
                $("#due").val(due);
              },
            });
          } else {
            $("#due").val($("#gross").val() - amount_received);
          }
        });
      });
      ("use strict");
      var sub_total = $(this).data("id") * $("#idinput-" + idd).val();
      tot = tot + sub_total;
    });
    ("use strict");
    var discount = ($("#dis_id_percent").val() * tot) / 100;

    // if (discount_type === "flat") {
    //   var vat = (vat_amount * tot) / 100;
    //   var gross = tot - discount + vat;
    // } else {
    var vat_amount = $("#vat").val();
    var vat = (vat_amount * tot) / 100;

    var gross = tot - discount + vat;
    //}
    $("#editPaymentForm").find('[name="subtotal"]').val(tot).end();
    $("#editPaymentForm").find('[name="discount"]').val(discount).end();
    $("#editPaymentForm").find('[name="vat_amount"]').val(vat);
    $("#editPaymentForm").find('[name="grsss"]').val(gross);

    var amount_received = $("#amount_received").val();
    var change = gross - amount_received;
    $("#editPaymentForm").find('[name="change"]').val(change).end();
    var asdid = $("#id_pay").val() ? $("#id_pay").val() : null;

    if (asdid !== null) {
      $.ajax({
        url: "finance/getDepositByInvoiceId?id=" + asdid,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response) {
          var due = $("#gross").val() - response.response;
          $("#due").val(due);
        },
      });
    } else {
      $("#due").val($("#gross").val() - amount_received);
    }
  });
});

$(document).ready(function () {
  "use strict";
  $("#dis_id").keyup(function () {
    "use strict";
    var val_dis = 0;
    var amount = 0;
    var ggggg = 0;
    amount = $("#subtotal").val();
    val_dis = this.value;
    var vat_amount = $("#vat").val();
    var vat = (vat_amount * amount) / 100;
    var discount = (val_dis * 100) / amount;
    ggggg = amount - val_dis + vat;

    $("#editPaymentForm").find('[name="grsss"]').val(ggggg);
    $("#editPaymentForm")
      .find('[name="percent_discount"]')
      .val(discount.toFixed(2));

    var amount_received = $("#amount_received").val();
    var change = amount_received - ggggg;
    $("#editPaymentForm").find('[name="change"]').val(change).end();
    var id = $("#id_pay").val() ? $("#id_pay").val() : null;
    if (id !== null) {
      $.ajax({
        url: "finance/getDepositByInvoiceId?id=" + id,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response) {
          var due = $("#gross").val() - response.response;
          $("#due").val(due);
        },
      });
    } else {
      $("#due").val($("#gross").val() - amount_received);
    }
  });
  $("#dis_id_percent").keyup(function () {
    "use strict";
    var val_dis = 0;
    var amount = 0;
    var ggggg = 0;
    amount = $("#subtotal").val();
    val_dis = this.value;
    var vat_amount = $("#vat").val();
    var vat = (vat_amount * amount) / 100;

    var discount = (amount * val_dis) / 100;
    ggggg = amount - discount + vat;
    $("#editPaymentForm").find('[name="discount"]').val(discount).toFixed(2);
    $("#editPaymentForm").find('[name="grsss"]').val(ggggg);

    var amount_received = $("#amount_received").val();

    var id = $("#id_pay").val() ? $("#id_pay").val() : null;
    if (id !== null) {
      $.ajax({
        url: "finance/getDepositByInvoiceId?id=" + id,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response) {
          var due = $("#gross").val() - response.response;
          $("#due").val(due);
        },
      });
    } else {
      $("#due").val($("#gross").val() - amount_received);
    }
  });
});
$(document).ready(function () {
  "use strict";
  $("#vat_amount").keyup(function () {
    "use strict";
    var val_dis = 0;
    var amount = 0;
    var ggggg = 0;

    amount = $("#subtotal").val();
    val_dis = $(this).val();

    var vat = (100 * val_dis) / amount;
    var discount = $("#dis_id").val();
    ggggg = amount - discount + parseFloat(val_dis);
    $("#vat").val("");
    $("#editPaymentForm").find('[name="grsss"]').val(ggggg);
    $("#editPaymentForm").find('[name="vat"]').val(vat.toFixed(2));

    var amount_received = $("#amount_received").val();
    var change = amount_received - ggggg;
    $("#editPaymentForm").find('[name="change"]').val(change).end();
    var id = $("#id_pay").val() ? $("#id_pay").val() : null;
    if (id !== null) {
      $.ajax({
        url: "finance/getDepositByInvoiceId?id=" + id,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response) {
          var due = $("#gross").val() - response.response;
          $("#due").val(due);
        },
      });
    } else {
      $("#due").val($("#gross").val() - amount_received);
    }
  });
  $("#vat").keyup(function () {
    "use strict";
    var val_dis = 0;
    var amount = 0;
    var ggggg = 0;
    amount = $("#subtotal").val();
    val_dis = this.value;
    var discount = $("#dis_id").val();
    var vat = (val_dis * amount) / 100;

    ggggg = amount - discount + vat;
    $("#vat").val("");
    $("#vat_amount").val("");
    $("#editPaymentForm").find('[name="grsss"]').val(ggggg);
    $("#editPaymentForm").find('[name="vat_amount"]').val(vat.toFixed(2));
    $("#editPaymentForm").find('[name="vat"]').val(val_dis);
    var amount_received = $("#amount_received").val();
    var change = amount_received - ggggg;
    $("#editPaymentForm").find('[name="change"]').val(change).end();
    var id = $("#id_pay").val() ? $("#id_pay").val() : null;
    if (id !== null) {
      $.ajax({
        url: "finance/getDepositByInvoiceId?id=" + id,
        method: "GET",
        data: "",
        dataType: "json",
        success: function (response) {
          var due = $("#gross").val() - response.response;
          $("#due").val(due);
        },
      });
    } else {
      $("#due").val($("#gross").val() - amount_received);
    }
  });
});
$(document).ready(function () {
  "use strict";
  // $("#due").keyup(function () {
  //   var id = $("#id_pay").val();
  //   if (id !== null) {
  //     $.ajax({
  //       url: "finance/getDepositByInvoiceId?id=" + id,
  //       method: "GET",
  //       data: "",
  //       dataType: "json",
  //       success: function (response) {
  //         var due = $("#gross").val() - response.response;
  //         $("#due").val(due);
  //       },
  //     });
  //   } else {
  //     $("#due").val($("#gross").val());
  //   }
  // });
  $("#amount_received").keyup(function () {
    var gross = $("#gross").val();

    var ammount_recived = $(this).val();

    $("#due").val(gross - ammount_recived);
  });
  if ($.trim($("#id_pay").val()) == "") {
    $(".pos_client").hide();
  }

  $(document.body).on("change", "#pos_select", function () {
    "use strict";
    var v = $("select.pos_select option:selected").val();
    if (v === "add_new" || v === "walk_in") {
      $(".pos_client").show();
      $("#editPaymentForm").find('[name="p_name"]').prop('required', true);
      $("#editPaymentForm").find('[name="p_phone"]').prop('required', true);
      $("#editPaymentForm").find('[name="p_gender"]').prop('required', true);
      
      if (v === 'walk_in') {
         $("#editPaymentForm").find('[name="p_birth"]').prop('required', false);
      } else {
         $("#editPaymentForm").find('[name="p_birth"]').prop('required', true);
      }

      $("#editPaymentForm").find('[name="p_name"]').val(" ").end();
      $("#editPaymentForm").find('[name="p_email"]').val(" ").end();
      $("#editPaymentForm").find('[name="p_phone"]').val(" ").end();
      $("#editPaymentForm").find('[name="p_birth"]').val(" ").end();

      $("#editPaymentForm").find('[name="years"]').val(" ").end();
      $("#editPaymentForm").find('[name="months"]').val(" ").end();
      $("#editPaymentForm").find('[name="days"]').val(" ").end();
      $("#editPaymentForm").find('[name="p_gender"]').val(" ").end();
      $("#editPaymentForm").find('[name="years"]').attr("readonly", false);
      $("#editPaymentForm").find('[name="months"]').attr("readonly", false);
      $("#editPaymentForm").find('[name="days"]').attr("readonly", false);
      $("#editPaymentForm").find('[name="p_birth"]').attr("readonly", false);
      $("#editPaymentForm").find('[name="p_phone"]').attr("readonly", false);
      $("#editPaymentForm").find('[name="p_name"]').attr("readonly", false);
      $("#editPaymentForm").find('[name="p_email"]').attr("readonly", false);
      $("#p_birth").attr("readonly", false);
      $("#p_gender").attr("readonly", false);
    } else {
      $(".pos_client").hide();
      $("#editPaymentForm").find('[name="p_name"]').prop('required', false);
      $("#editPaymentForm").find('[name="p_phone"]').prop('required', false);
      $("#editPaymentForm").find('[name="p_birth"]').prop('required', false);
      $("#editPaymentForm").find('[name="p_gender"]').prop('required', false);
      // $.ajax({
      //   url: "finance/getPatientById?id=" + v,
      //   method: "GET",
      //   data: "",
      //   dataType: "json",
      //   success: function (response) {
      //     "use strict";
      //     $("#editPaymentForm")
      //       .find('[name="p_name"]')
      //       .val(response.patient.name)
      //       .end();
      //     $("#editPaymentForm")
      //       .find('[name="p_email"]')
      //       .val(response.patient.email)
      //       .end();
      //     $("#editPaymentForm")
      //       .find('[name="p_phone"]')
      //       .val(response.patient.phone)
      //       .end();
      //     $("#editPaymentForm")
      //       .find('[name="p_birth"]')
      //       .val(response.patient.birthdate)
      //       .end();
      //     var age = response.patient.age.split("-");
      //     $("#editPaymentForm").find('[name="years"]').val(age[0]).end();
      //     $("#editPaymentForm").find('[name="months"]').val(age[1]).end();
      //     $("#editPaymentForm").find('[name="days"]').val(age[2]).end();
      //     $("#editPaymentForm")
      //       .find('[name="p_gender"]')
      //       .val(response.patient.sex)
      //       .trigger("change");
      //     $("#editPaymentForm").find('[name="years"]').attr("readonly", true);
      //     $("#editPaymentForm").find('[name="months"]').attr("readonly", true);
      //     $("#editPaymentForm").find('[name="days"]').attr("readonly", true);
      //     $("#editPaymentForm").find('[name="p_birth"]').attr("readonly", true);
      //     $("#editPaymentForm").find('[name="p_phone"]').attr("readonly", true);
      //     $("#editPaymentForm").find('[name="p_name"]').attr("readonly", true);
      //     $("#editPaymentForm").find('[name="p_email"]').attr("readonly", true);
      //     $("#p_birth").attr("readonly", true);
      //     $("#p_gender").attr("readonly", true);
      //     $("#p_birth").prop("required", false);
      //     $("#p_name").prop("required", false);
      //     $("#p_email").prop("required", false);
      //     $("#p_phone").prop("required", false);
      //   },
      // });
      // $("#p_birth").prop("required", false);
    }
  });
});

$(document).ready(function () {
  "use strict";
  if (add_doctor === "no") {
    $(".pos_doctor").hide();
  } else {
    $(".pos_doctor").show();
  }

  $(document.body).on("change", "#add_doctor", function () {
    "use strict";

    var v = $("select.add_doctor option:selected").val();
    if (v === "add_new" || v === "external") {
      $(".pos_doctor").show();
      $("#d_name").prop("required", true);
      // Email and phone optional for external/referral
      if (v === 'external') {
        $("#d_email").prop("required", false);
        $("#d_phone").prop("required", false);
      } else {
        $("#d_email").prop("required", true);
        $("#d_phone").prop("required", true);
      }
    } else {
      $(".pos_doctor").hide();
      $("#d_name").prop("required", false);
      $("#d_email").prop("required", false);
      $("#d_phone").prop("required", false);
    }
  });
});

$(document).ready(function () {
  "use strict";
  $(".cardPayment").hide();
  $(document.body).on("change", "#selecttype", function () {
    "use strict";
    var v = $("select.selecttype option:selected").val();
    if (v === "Card") {
      $(".insurance_div").addClass("hidden");
      $(".cardsubmit").removeClass("d-none");
      $(".cashsubmit").addClass("d-none");
      $(".cardsubmit3").removeClass("d-none");
      $(".cashsubmit2").addClass("d-none");
      $("#amount_received").prop("required", true);

      $(".cardPayment").show();
    } if (v === "Insurance") {
      $(".insurance_div").removeClass("hidden");
      $(".cardPayment").hide();
      $(".cashsubmit").removeClass("d-none");
      $(".cardsubmit").addClass("d-none");
      $(".cashsubmit2").removeClass("d-none");
      $(".cardsubmit3").addClass("d-none");
      $("#amount_received").prop("required", false);
    }
    if (v === "Cash") {
      $(".insurance_div").addClass("hidden");
      $(".cardPayment").hide();
      $(".cashsubmit").removeClass("d-none");
      $(".cardsubmit").addClass("d-none");
      $(".cashsubmit2").removeClass("d-none");
      $(".cardsubmit3").addClass("d-none");
      $("#amount_received").prop("required", false);
    }
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
    alert(response.error.message);
    $("#submit-btn").show();
    $("#submit-btn2").show();
    $("#loader").css("display", "none");
    $("#submit-btn").attr("disabled", false);
    $("#submit-btn2").show();
    $("#error-message").html(response.error.message).show();
  } else {
    var token = response["id"];
    if (token != null) {
      $("#token").val(token);
      $("#editPaymentForm").append(
        "<input type='hidden' name='token' value='" + token + "' />"
      );
      $("#editPaymentForm").submit();
    } else {
      alert("Please Check Your Card details");
      $("#submit-btn").attr("disabled", false);
      $("#submit-btn2").attr("disabled", false);
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



$(document).ready(function () {
  "use strict";
  $("#pos_select").select2({
    placeholder: select_patient,
    allowClear: true,
    ajax: {
      url: "patient/getPatientinfoWithAddNewOption",
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
    },
  });

  $("#add_doctor").select2({
    placeholder: select_doctor,
    allowClear: true,
    ajax: {
      url: "doctor/getDoctorWithAddNewOption",
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











// $(document).ready(function() {



//     $('#selected_testpkz').on('change', function() {
//         var id = $(this).val();
//         var count = 0;
//         // var testpkz_id = $('#testpkz_id').val();
//         $.ajax({
//             url: 'testpkz/getTestpkzDetails?id=' + id,
//             method: 'GET',
//             data: '',
//             dataType: 'json',
//             success: function(response2) {
//                 var testpkz_item1 = response2.testpkz_item_list.split(",");

//                 $.each(testpkz_item1, function(index, value) {
//                     let testpkz_item_extended111 = [];
//                     testpkz_item_extended111 = value.split("****");
//                     var $select = $("#selected_testpkz");
//                     var idToRemove = testpkz_item_extended111[0] + '*' +
//                     testpkz_item_extended111[1];
//                     $("#selected_testpkz option[value='" +
//                     testpkz_item_extended111[0] + '*' +
//                     testpkz_item_extended111[1] + "']").remove();
//                     $('.select2 - selection__clear').find('[title="' +
//                     testpkz_item_extended111[1] + '"]').remove();
//                     var values = $select.val();
//                     if (values) {
//                         var i = values.indexOf(idToRemove);
//                         if (i >= 0) {
//                             values.splice(i);
//                             $select.val(values).change();
//                         }
//                     }
//                     $('#med_selected_section-' + testpkz_item_extended111[0])
//                         .remove();
//                 });
//             },
//         });
//         // $(".board_doc").find("option").remove();
//         $.ajax({
//             url: 'testpkz/getTestpkzDetails?id=' + id,
//             method: 'GET',
//             data: '',
//             dataType: 'json',
//             //  timeout: 5000
//             success: function(response) {
//                 // $('#team_id').val(id);
//                 // var lead_doctor = response.team.lead_doctor
//                 // $('#board_leader_id').val(lead_doctor);
//                 var testpkz_item = response.testpkz.payment_category.split(",");
//                 $.each(testpkz_item, function(index, value) {
//                     var testpkz_item_extended = [];
//                     testpkz_item_extended = value.split("***");
//                     var item_id = testpkz_item_extended[0];
//                     $.ajax({
//                         url: 'finance/getPaymentCategoryByJason?id=' + item_id,
//                         method: 'GET',
//                         data: '',
//                         dataType: 'json',
//                         success: function(response1) {
//                             var id = response1.testpkz.id;
//                             // var id = $(this).data('id');
//                             // var med_id = response1.testpkz.id;
//                             var med_name = response1.testpkz.category;
//                             var price = response1.testpkz.c_price;
//                             var selection = $('selection').text();
//                             // var option = new Option(med_name, true,
//                             //     true);
//                             // $('.ms-list').append(option).trigger(
//                             //     'change');
//                             $('.ms-list').append('<li class="ooppttiioonn ms-elem-selection ms-selected" data-id="' + price + '" data-iid="' + id + '" data-cat_name="' + med_name + '" id="' + id + '-selection' + '">' + med_name + '</li>');
//                             // $('.ms-list').append('<li class="ooppttiioonn ms-elem-selection ms-selected">' + med_name + '</li>');
//                         },




//                     });
//                 });
//             },

//         });



//     });
// });


$(document).ready(function () {
  // Listen for changes in the first dropdown
  $('#selected_testpkz').change(function () {
    // Get the selected option's value
    var selectedValue = $(this).val();

    // Click the first two <li> items in the second dropdown
    $('.ms-list li').removeClass('selected');
    $('.ms-list li[data-idd="' + selectedValue + '"]').addClass('selected');
    $('.ms-list li[data-idd="' + selectedValue + '"]').next().addClass('selected');
  });
});


$(document).ready(function () {
  // Add a change event listener to the country dropdown
  $('#selected_testpkz').change(function () {
    // Get the selected country
    const country = $(this).val();

    // Deselect all list items
    //$('li').removeClass('selected');
    // alert('hi');
    // Select the list items that match the selected country
    $('ms-list').click();
    // $('li[data-idd="66"]').click().addClass('ms-selected');
    // $('li').css("display", "none");
  });
});


// Logic for Pending Lab/Daycare/Emergency Requests
$(document).ready(function () {
    "use strict";

    // Listen to patient selection change
    $(document.body).on("change", "#pos_select", function () {
        var patient_id = $(this).val();
        
        // Lab Containers
        var lab_container = $("#pending_lab_requests_container");
        var lab_list = $("#pending_lab_requests_list");

        // Daycare Containers
        var daycare_container = $("#pending_daycare_requests_container");
        var daycare_list = $("#pending_daycare_requests_list");

        // Emergency Containers
        var emergency_container = $("#pending_emergency_requests_container");
        var emergency_list = $("#pending_emergency_requests_list");

        if (patient_id && patient_id !== "add_new") {
            // Fetch Lab Requests
            $.ajax({
                url: "finance/getUnpaidPatientLabs",
                type: "POST",
                data: { patient_id: patient_id },
                dataType: "json",
                success: function (response) {
                    lab_list.empty();
                    if (response.length > 0) {
                        lab_container.show();
                        
                        // Autofill doctor if not already selected
                        var firstLab = response[0];
                        if (firstLab.doctor_id && !$('#add_doctor').val()) {
                            var option = new Option(firstLab.doctor_name, firstLab.doctor_id, true, true);
                            $('#add_doctor').append(option).trigger('change');
                        }

                        $.each(response, function (index, lab) {
                            var row = '<tr>' +
                                '<td>' + lab.category_name + '</td>' +
                                '<td>' + lab.date + '</td>' +
                                '<td class="text-right">' +
                                '<button type="button" class="btn btn-sm btn-primary add-lab-to-invoice" ' +
                                'data-id="' + lab.category_id + '" ' +
                                'data-name="' + lab.category_name + '" ' +
                                'data-price="' + lab.price + '" ' +
                                'data-lab-id="' + lab.lab_id + '">' +
                                '<i class="fa fa-plus"></i> Add' +
                                '</button>' +
                                '</td>' +
                                '</tr>';
                            lab_list.append(row);
                        });
                    } else {
                        lab_container.hide();
                    }
                }
            });

            // Fetch Daycare Requests
            $.ajax({
                url: "finance/getUnpaidPatientDaycare",
                type: "POST",
                data: { patient_id: patient_id },
                dataType: "json",
                success: function (response) {
                    daycare_list.empty();
                    if (response.length > 0) {
                        daycare_container.show();
                        $.each(response, function (index, req) {
                            var row = '<tr>' +
                                '<td>' + req.reason + '</td>' +
                                '<td>' + req.date + '</td>' +
                                '<td class="text-right">' +
                                '<button type="button" class="btn btn-sm btn-primary add-daycare-to-invoice" ' +
                                'data-req-id="' + req.id + '" ' +
                                'data-price="' + req.price + '">' +
                                '<i class="fa fa-plus"></i> Add' +
                                '</button>' +
                                '</td>' +
                                '</tr>';
                            daycare_list.append(row);
                        });
                    } else {
                        daycare_container.hide();
                    }
                }
            });

            // Fetch Emergency Requests
            $.ajax({
                url: "finance/getUnpaidPatientEmergency",
                type: "POST",
                data: { patient_id: patient_id },
                dataType: "json",
                success: function (response) {
                    emergency_list.empty();
                    if (response.length > 0) {
                        emergency_container.show();
                        $.each(response, function (index, req) {
                            var row = '<tr>' +
                                '<td>' + req.complaint + '</td>' +
                                '<td>' + req.date + '</td>' +
                                '<td class="text-right">' +
                                '<button type="button" class="btn btn-sm btn-primary add-emergency-to-invoice" ' +
                                'data-req-id="' + req.id + '" ' +
                                'data-price="' + req.price + '">' +
                                '<i class="fa fa-plus"></i> Add' +
                                '</button>' +
                                '</td>' +
                                '</tr>';
                            emergency_list.append(row);
                        });
                    } else {
                        emergency_container.hide();
                    }
                }
            });
        } else {
            lab_container.hide();
            daycare_container.hide();
            emergency_container.hide();
            lab_list.empty();
            daycare_list.empty();
            emergency_list.empty();
        }
    });

    function addItemToInvoice(id, mapName, mapValue, btn, typeLabel) {
        var select = $("#my_multi_select3");
        var option = select.find("option[value='" + id + "']");

        if (option.length) {
            var current = select.val();
            if (!current) current = [];
            if(!Array.isArray(current)) current = [current];

            if (current.indexOf(String(id)) === -1) {
                // If the multiselect plugin is active, use its select method
                if (typeof select.multiSelect === 'function' && $(".ms-container").length > 0) {
                    select.multiSelect('select', String(id));
                } else {
                    current.push(String(id));
                    select.val(current);
                    select.trigger('change');
                }

                if (typeLabel === 'lab') {
                    // Lab still uses maps because it's test-specific
                    $("<input>").attr({
                        type: "hidden",
                        class: "remove",
                        name: mapName + "[" + id + "]",
                        value: mapValue
                    }).appendTo("#editPaymentForm .qfloww");
                    btn.prop('disabled', true).html('<i class="fa fa-check"></i> Added');
                } else {
                    // For Daycare/Emergency, we disable all buttons of that type as it's a flat fee
                    if (typeLabel === 'daycare') {
                        $(".add-daycare-to-invoice").prop('disabled', true).html('<i class="fa fa-check"></i> Added');
                    } else if (typeLabel === 'emergency') {
                        $(".add-emergency-to-invoice").prop('disabled', true).html('<i class="fa fa-check"></i> Added');
                    }
                }
            } else {
                alert('This item is already in the invoice.');
            }
        } else {
            console.error('Category not found for:', typeLabel);
            alert('Item category not found. Please refresh and ensure "Daycare" and "Emergency" are in your payment categories.');
        }
    }

    function findCategoryId(searchTerms) {
        var foundId = null;
        $("#my_multi_select3 option").each(function() {
            var catName = $(this).data("cat_name") || "";
            var optText = $(this).text() || "";
            
            for (var i = 0; i < searchTerms.length; i++) {
                var term = searchTerms[i].toLowerCase().trim();
                if (catName.toLowerCase().trim() === term || optText.toLowerCase().trim().indexOf(term) === 0) {
                    foundId = $(this).val();
                    return false; // break each
                }
            }
        });
        return foundId;
    }

    // Handle Lab "Add" button click
    $(document.body).on("click", ".add-lab-to-invoice", function () {
        var id = $(this).data("id");
        var lab_id = $(this).data("lab-id");
        addItemToInvoice(id, "lab_request_map", lab_id, $(this), 'lab');
    });

    // Handle Daycare "Add" button click
    $(document.body).on("click", ".add-daycare-to-invoice", function () {
        var req_id = $(this).data("req-id");
        var id = findCategoryId(["Daycare", "Day care"]);
        addItemToInvoice(id, null, req_id, $(this), 'daycare');
    });

    // Handle Emergency "Add" button click
    $(document.body).on("click", ".add-emergency-to-invoice", function () {
        var req_id = $(this).data("req-id");
        var id = findCategoryId(["Emergency"]);
        addItemToInvoice(id, null, req_id, $(this), 'emergency');
    });
});
