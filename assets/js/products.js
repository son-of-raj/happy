(function ($) {
  "use strict";

  var base_url = $("#base_url").val();
  var csrf_token = $("#csrf_token").val();
  var csrfName = $("#csrfName").val();
  var csrfHash = $("#csrfHash").val();

  var moduName = $("#modules_page").val();
  var actiPage = $("#current_page").val();

  $(document).ready(function () {
    $(".select").selectpicker();
    //get sub category based on cat
    $("#product_category").on("change", function () {
      $("#subcategory").val("default");
      $("#subcategory").selectpicker("refresh");

      $.ajax({
        type: "POST",
        url: base_url + "user/products/get_subcategory",
        data: { id: $(this).val(), csrf_token_name: csrf_token },
        beforeSend: function () {
          $("#subcategory option:gt(0)").remove();
          $("#subcategory").selectpicker("refresh");
          $("#subcategory").selectpicker();
          $("#subcategory").find("option:eq(0)").html("Please wait..");
          $("#subcategory").selectpicker("refresh");
          $("#subcategory").selectpicker();
        },
        success: function (data) {
          $("#subcategory").selectpicker("refresh");
          $("#subcategory").selectpicker();
          $("#subcategory").find("option:eq(0)").html("Select SubCategory");
          $("#subcategory").selectpicker("refresh");
          var obj = jQuery.parseJSON(data);
          $("#subcategory").selectpicker("refresh");
          $("#subcategory").selectpicker();

          $.each(obj, function (index, value) {
            var option = $("<option />");
            option.attr("value", value["id"]).text(value["subcategory_name"]);
            $("#subcategory").append(option);
          });
          $("#subcategory").selectpicker("refresh");
          $("#subcategory").selectpicker();
        },
      });
    });
    $("#price, #discount").on("keyup", function () {
      var price = Number($("#price").val());
      var discount = Number($("#discount").val());
      var sale_price = Number($(this).val());
      if (price > 0) {
        sale_price = price - discount;
      }
      $("#sale_price").val(sale_price);
    });
    $("#country_id").on("change", function () {
      var id = $(this).val();
      country_changes(id, "");
    });
    $("#state_id").on("change", function () {
      var id = $(this).val();
      state_changes(id, "");
    });
    $("#save_billing").on("click", function () {
      save_billing_details();
    });
    //insert
    $("#add_product")
      .bootstrapValidator({
        fields: {
          "products[category]": {
            validators: {
              notEmpty: {
                message: "Please select category...",
              },
            },
          },
          "products[subcategory]": {
            validators: {
              notEmpty: {
                message: "Please select category...",
              },
            },
          },
          "products[product_name]": {
            validators: {
              notEmpty: {
                message: "Please Enter Product Name...",
              },
            },
          },
          "products[unit_value]": {
            validators: {
              notEmpty: {
                message: "Please Enter Unit Value",
              },
              integer: {
                message: "The value is not an integer",
              },
            },
          },
          "products[unit]": {
            validators: {
              notEmpty: {
                message: "Please select unit type",
              },
            },
          },
          "products[price]": {
            validators: {
              notEmpty: {
                message: "Please Enter Price",
              },
              regexp: {
                regexp: /^\d+(?:\.\d{1,2})?$/,
                message: "Accepts only numbers",
              },
            },
          },
          "products[discount]": {
            validators: {
              notEmpty: {
                message: "Please Enter discount in amount",
              },
              regexp: {
                regexp: /^\d+(?:\.\d{1,2})?$/,
                message: "Accepts only numbers",
              },
            },
          },
          "products[sale_price]": {
            validators: {
              notEmpty: {
                message: "Please Enter Price",
              },
              regexp: {
                regexp: /^\d+(?:\.\d{1,2})?$/,
                message: "Accepts only numbers",
              },
            },
          },
          "products[manufactured_by]": {
            validators: {
              notEmpty: {
                message: "Please Enter Manufactured By",
              },
            },
          },
          "products[short_description]": {
            validators: {
              notEmpty: {
                message: "Please Give short description",
              },
              stringLength: {
                max: 500,
                message:
                  "The short description must be less than 500 characters",
              },
            },
          },
          "products[description]": {
            validators: {
              notEmpty: {
                message: "Please Give description",
              },
            },
          },
          "images[]": {
            validators: {
              file: {
                extension: "jpeg,png,jpg",
                type: "image/jpeg,image/png,image/jpg",
                message:
                  "The selected file is not valid. Only allowed jpeg,png files",
              },
              notEmpty: {
                message: "Please upload service image...",
              },
            },
          },
        },
      })
      .on("success.form.bv", function (e) {
        return true;
      });
    //
    //edit
    $("#edit_product")
      .bootstrapValidator({
        fields: {
          "products[category]": {
            validators: {
              notEmpty: {
                message: "Please select category...",
              },
            },
          },
          "products[subcategory]": {
            validators: {
              notEmpty: {
                message: "Please select category...",
              },
            },
          },
          "products[product_name]": {
            validators: {
              notEmpty: {
                message: "Please Enter Product Name...",
              },
            },
          },
          "products[unit_value]": {
            validators: {
              notEmpty: {
                message: "Please Enter Unit Value",
              },
              integer: {
                message: "The value is not an integer",
              },
            },
          },
          "products[unit]": {
            validators: {
              notEmpty: {
                message: "Please select unit type",
              },
            },
          },
          "products[price]": {
            validators: {
              notEmpty: {
                message: "Please Enter Price",
              },
              regexp: {
                regexp: /^\d+(?:\.\d{1,2})?$/,
                message: "Accepts only numbers",
              },
            },
          },
          "products[discount]": {
            validators: {
              notEmpty: {
                message: "Please Enter discount in amount",
              },
              regexp: {
                regexp: /^\d+(?:\.\d{1,2})?$/,
                message: "Accepts only numbers",
              },
            },
          },
          "products[sale_price]": {
            validators: {
              notEmpty: {
                message: "Please Enter Price",
              },
              regexp: {
                regexp: /^\d+(?:\.\d{1,2})?$/,
                message: "Accepts only numbers",
              },
            },
          },
          "products[manufactured_by]": {
            validators: {
              notEmpty: {
                message: "Please Enter Manufactured By",
              },
            },
          },
          "products[short_description]": {
            validators: {
              notEmpty: {
                message: "Please Give short description",
              },
              stringLength: {
                max: 500,
                message:
                  "The short description must be less than 500 characters",
              },
            },
          },
          "products[description]": {
            validators: {
              notEmpty: {
                message: "Please Give description",
              },
            },
          },
        },
      })
      .on("success.form.bv", function (e) {
        return true;
      });
  });

  $("#adds,#subs").on("click", function () {
    var id_type = $(this).val();
    var qty = Number($("#pqty").val());
    if (id_type == "+") {
      qty = qty + 1;
    } else {
      qty = qty - 1;
    }
    if (qty > 0) {
      $("#pqty").val(qty);
    }
  });

  $(".inc,.dec").on("click", function () {
    var cart_id = $(this).attr("cart_id");
    var product_id = $(this).attr("product_id");
    var id_type = $(this).val();
    alert(id_type);
    var qty = Number($("#qty_" + cart_id).val());
    if (id_type == "+") {
      qty = qty + 1;
    } else {
      qty = qty - 1;
    }
    if (qty > 0) {
      add_cart(cart_id, product_id, qty, id_type);
    }
  });
  $("#checkout").on("click", function () {
    //create order
    $.ajax({
      type: "GET",
      url: base_url + "user/products/createorder",
      params: { csrf_token_name: csrf_token },
      success: function (response) {
        console.log(response);
        window.location.href = base_url + "checkout/" + response;
      },
    });
  });
  $(document).on("click", ".add_cart_btn", function () {
    var usertype = $("#user_type").val();
    if (usertype == "" || typeof usertype === "undefined") {
      $("#tab_login_modal").modal("show");
    } else {
      var pid = $(this).attr("pid");
      var qty = $("#pqty").val();
      //add to cart
      add_cart("", pid, qty, "");
    }
  });

  $(document).on("click", ".add_buy_btn", function () {
    var usertype = $("#user_type").val();
    if (usertype == "" || typeof usertype === "undefined") {
      $("#tab_login_modal").modal("show");
    } else {
      var pid = $(this).attr("pid");
      var qty = $("#pqty").val();
      //add to cart
      add_checkout(pid, qty);
    }
  });

  $(".catid").on("click", function () {
    var cat_id = $(this).attr("id");
    $("#f_cat_id").val(cat_id);
    searchFilter(0);
  });
  $(".pricefilter").on("click", function () {
    var pricefilter = $(this).val();
    var cc = $(this).attr("cc");
    $("#f_pricerange").val(pricefilter);
    $("#f_cc").val(cc);
    searchFilter(0);
  });
  $(".filter_order").on("click", function () {
    var stype = $(this).attr("stype");
    if (stype == "c") {
      $(".cf").val("");
    }
    orderFilter(0);
  });
  $("#delivery_status").on("change", function () {
    orderFilter(0);
  });
  $(document).on("click", ".edit_billing", function () {
    var billing_id = $(this).attr("bid");
    $.ajax({
      type: "POST",
      url: base_url + "user/products/billingdetails",
      data: {
        type: "edit",
        billing_id: billing_id,
        csrf_token_name: csrf_token,
      },
      success: function (response) {
        var result = jQuery.parseJSON(response);
        $("#billing_pop").modal("show");
        //state
        country_changes(result.country_id, result.state_id);
        //city
        state_changes(result.state_id, result.city_id);
        address_type_changes(result.address_type);
        $.each(result, function (key, value) {
          //address
          if (key == "address") {
            $("#checkout_address").val(value);
          }
          $("#" + key).val(value);
        });
        $("#bd_id").val(billing_id);
      },
    });
  });
  $(document).on("click", ".pay-chadr-link", function () {
    $("#address_list").modal("show");
  });
  $(document).on("click", ".delete_billing", function () {
    var billing_id = $(this).attr("bid");
    $("#delete_pop").modal("show");
    $("#hb_id").val(billing_id);
  });
  $("#confirm_dbd").on("click", function () {
    var billing_id = $("#hb_id").val();
    $.ajax({
      type: "POST",
      url: base_url + "user/products/billingdetails",
      data: {
        type: "delete",
        billing_id: billing_id,
        csrf_token_name: csrf_token,
      },
      success: function (response) {
        $("#billing_list").html(response);
        $(".my-checkout-col").show();
        $("#delete_pop").modal("hide");
      },
    });
  });
  $("#proceed_payment").on("click", function () {
    var select_billing = $("input[class=select_billing]:checked").val();
    var address_type = $("#address_type").val();
    var pay_type = $("input[name=order_payment_type]:checked").val();

    var url_v1 = $("#url_v1").val();
    if (pay_type == undefined) {
      toastnotification("error", "Please Select Pay Type", "", "", "");
      return false;
    } else if (select_billing == undefined) {
      toastnotification(
        "error",
        "Please Select or Add Billing Details",
        "",
        "",
        ""
      ); //save checkout and go to payment
    } else {
      if (pay_type == "paypal") {
        $("#moyasarpay_form").hide();
        var tot_amt = $("#product_total_amt").val();
        var order_id = $("#payment_order_id").val();
        var urlv1 = $("#url_v1").val();

        var url =
          base_url +
          "user/products/paypal_order_payment/" +
          tot_amt +
          "?orderid=" +
          order_id +
          "&billing_id=" +
          select_billing +
          "&address_type=" +
          address_type +
          "&urlv1=" +
          urlv1;
        $("#paypal_return_url").val(url);
        document.getElementById("order_paypal_detail").submit();
      } else if (pay_type == "stripe") {
        $("#moyasarpay_form").hide();
        $("#order_stripe_payment").click();
      } else if (pay_type == "razorpay") {
        $("#moyasarpay_form").hide();
        $("#rzp-button-product").click();
      } else if (pay_type == "moyasarpay") {
        $("#moyasarpay_form").show();
        order_moyasar_payment();
      }
    }
  });
  $("#update_order").on("click", function () {
    var select_billing = $("input[class=select_billing]:checked").val();
    if (select_billing != undefined) {
      //save checkout and go to payment
      $.ajax({
        type: "POST",
        url: base_url + "user/products/updatecheckout",
        data: {
          billing_id: select_billing,
          order_id: $("#order_id").val(),
          csrf_token_name: csrf_token,
        },
        success: function (response) {
          console.log(response);
          window.location.reload();
        },
      });
    } else {
      toastnotification(
        "error",
        "Please Select or Add Billing Details",
        "",
        "",
        ""
      );
    }
  });

  $(".change_at").on("click", function () {
    var order_id = $("#order_id").val();
    var address_type = $(this).val();
    //change call back url
    var razorpay_redirect = $("#razorpay_redirect").val();
    var url =
      base_url +
      "user/products/moyasar_redirect/" +
      order_id +
      "/" +
      address_type;
    $("#order_callbackurl").val(url);
    order_moyasar_payment();
  });

  $(".make_payment").on("click", function () {
    var order_id = $("#order_id").val();
    var gateway = $(this).attr("pgw");
    var rtype = $(this).attr("rtype");
    var idg = $(this).attr("idg");

    var address_type = $("#address_type").val();
    //card details
    var card_number = $("#" + idg + "_card_number").val();
    var card_name = $("#" + idg + "_card_name").val();
    var expiry_month = $("#" + idg + "_expiry_month").val();
    var expiry_year = $("#" + idg + "_expiry_year").val();
    var cvv = $("#" + idg + "_cvv").val();

    //paypal
    if (gateway == "paypal" || gateway == "stripe") {
      //validate card
      var vc = validate_card(idg);
      if (vc == true) {
        $.ajax({
          type: "POST",
          url: base_url + "user/products/make_payment",
          data: {
            order_id: order_id,
            gateway: gateway,
            rtype: rtype,
            csrf_token_name: csrf_token,
            card_number: card_number,
            card_name: card_name,
            expiry_month: expiry_month,
            expiry_year: expiry_year,
            cvv: cvv,
            address_type: address_type,
          },
          success: function (response) {
            var obj = JSON.parse(response);
            if (obj.error == true) {
              swal({
                title: "Payment failed",
                text: obj.message,
                icon: "error",
                button: "okay",
                closeOnEsc: false,
                closeOnClickOutside: false,
              }).then(function () {});
            } else {
              window.location.href =
                base_url + "order-confirmation/" + obj.hash_orderid;
            }
          },
        });
      }
    } else if (gateway == "moyasar") {
    } else if (gateway == "razorpay") {
      var amount = Number($("#razorpay_amt").val()) * 100;
      var razorpay_redirect = $("#razorpay_redirect").val();
      var options = {
        key: $("#razorpay_apikey").val(),
        currency: "INR",
        amount: Math.round(amount),
        name: $("#razorpay_name").val(),
        description: $("#razorpay_name").val(),
        handler: function (response) {
          $.ajax({
            url: base_url + "user/products/razorpay_payment",
            type: "post",
            dataType: "json",
            data: {
              order_id: order_id,
              razorpay_payment_id: response.razorpay_payment_id,
              csrf_token_name: csrf_token,
              address_type: address_type,
            },
            success: function (msg) {
              window.location.href =
                base_url + "order-confirmation/" + razorpay_redirect;
            },
          });
        },
        theme: {
          color: "#F37254",
        },
      };

      var rzp1 = new Razorpay(options);
      rzp1.open();
      return false;
    } else if (gateway == "wallet") {
      var razorpay_redirect = $("#razorpay_redirect").val();
      $.ajax({
        type: "POST",
        url: base_url + "user/products/make_payment",
        data: {
          order_id: order_id,
          gateway: gateway,
          csrf_token_name: csrf_token,
          address_type: address_type,
        },
        success: function (response) {
          var obj = JSON.parse(response);
          if (obj.error == true) {
            swal({
              title: "Payment failed",
              text: obj.message,
              icon: "error",
              button: "okay",
              closeOnEsc: false,
              closeOnClickOutside: false,
            }).then(function () {});
          } else {
            window.location.href =
              base_url + "order-confirmation/" + obj.hash_orderid;
          }
        },
      });
    } else {
      var razorpay_redirect = $("#razorpay_redirect").val();
      $.ajax({
        type: "POST",
        url: base_url + "user/products/make_payment",
        data: {
          order_id: order_id,
          gateway: gateway,
          csrf_token_name: csrf_token,
          address_type: address_type,
        },
        success: function (response) {
          window.location.href =
            base_url + "order-confirmation/" + razorpay_redirect;
        },
      });
    }
  });
  function convert(json) {
    return Object.keys(json)
      .map(function (key) {
        return encodeURIComponent(key) + "=" + encodeURIComponent(json[key]);
      })
      .join("&");
  }
  function add_checkout(product_id, qty) {
    $.ajax({
      type: "POST",
      url: base_url + "user/products/product_buy_now",
      data: { product_id: product_id, qty: qty, csrf_token_name: csrf_token },
      success: function (response) {
        var obj = $.parseJSON(response);
        if (obj.err == false) {
          window.location.href = base_url + "checkout/" + obj.order_id;
        } else {
          toastnotification(
            "error",
            "Sorry quantity is not available",
            "",
            "",
            ""
          );
        }
      },
    });
  }
  function save_billing_details() {
    //validate billing
    var validate = validate_billing();
    if (validate == true) {
      var address_types = $("#address_types :selected").val();
      $.ajax({
        type: "POST",
        url: base_url + "user/products/save_billing_details",
        data: {
          id: $("#bd_id").val(),
          full_name: $("#full_name").val(),
          phone_no: $("#phone_no").val(),
          email_id: $("#email_id").val(),
          address: $("#checkout_address").val(),
          address_type: address_types,
          country_id: $("#country_id").val(),
          state_id: $("#state_id").val(),
          city_id: $("#city_id").val(),
          zipcode: $("#zipcode").val(),
          csrf_token_name: csrf_token,
        },
        success: function (response) {
          $("#billing_list").html(response);
          $(".my-checkout-col").show();
          $("#billing_pop").modal("hide");
        },
      });
    }
  }
  function validate_billing() {
    var i = 0;
    var err_msg = "<ul>";
    var full_name = $("#full_name").val();
    if (full_name.trim().length == 0) {
      i = 1;
      err_msg += "<li>Name is Required</li>";
    }
    var phone_no = $("#phone_no").val();
    if (phone_no.trim().length == 0) {
      i = 1;
      err_msg += "<li>Mobile No is Required</li>";
    } else if (phone_no.trim().length < 10 || phone_no.trim().length > 10) {
      i = 1;
      err_msg += "<li>Mobile No should be 10 digits</li>";
    } else if (/^\d{10}$/.test(phone_no) == false) {
      i = 1;
      err_msg += "<li>Invalid Mobile no</li>";
    }
    var email_id = $("#email_id").val();
    if (email_id.trim().length == 0) {
      i = 1;
      err_msg += "<li>Email ID is Required</li>";
    } else if (
      /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(
        email_id
      ) == false
    ) {
      i = 1;
      err_msg += "<li>Invalid Email ID</li>";
    }
    var address = $("#checkout_address").val();
    if (address.trim().length == 0) {
      i = 1;
      err_msg += "<li>Address is Required</li>";
    } else if (phone_no.trim().length > 150) {
      i = 1;
      err_msg += "<li>Address should be less than 150</li>";
    }
    var country_id = $("#country_id").val();
    if (country_id == "") {
      i = 1;
      err_msg += "<li>Country is Required</li>";
    }
    var state_id = $("#state_id").val();
    if (state_id == "") {
      i = 1;
      err_msg += "<li>State is Required</li>";
    }
    var city_id = $("#city_id").val();
    if (city_id == "") {
      i = 1;
      err_msg += "<li>City is Required</li>";
    }
    var zipcode = $("#zipcode").val();
    if (zipcode.trim().length == 0) {
      i = 1;
      err_msg += "<li>Zipcode is Required</li>";
    } else if (/^\d+$/.test(zipcode) == false) {
      i = 1;
      err_msg += "<li>Invalid Zipcode</li>";
    }
    var address_type = $("#address_type").find("option:selected").val();
    if (address_type == "") {
      i = 1;
      err_msg += "<li>Address Type is Required</li>";
    }
    err_msg += "</ul>";
    if (i == 1) {
      toastnotification("error", err_msg, "Clear the errors", "", "");
      return false;
    } else {
      return true;
    }
  }
  function validate_card(idg) {
    var i = 0;
    var err_msg = "<ul>";
    var card_number = $("#" + idg + "_card_number").val();
    var card_name = $("#" + idg + "_card_name").val();
    var expiry_month = $("#" + idg + "_expiry_month").val();
    var expiry_year = $("#" + idg + "_expiry_year").val();
    var cvv = $("#" + idg + "_cvv").val();
    var full_name = $("#full_name").val();
    if (card_number.trim().length == 0) {
      i = 1;
      err_msg += "<li>Card Number is Required</li>";
    } else if (card_number.trim().length > 16) {
      i = 1;
      err_msg += "<li>Card Number should be 16 digits</li>";
    }
    if (card_name.trim().length == 0) {
      i = 1;
      err_msg += "<li>Card Name is Required</li>";
    }
    if (expiry_month.trim().length == 0) {
      i = 1;
      err_msg += "<li>Month is Required</li>";
    } else if (
      expiry_month.trim().length < 2 ||
      expiry_month.trim().length > 2
    ) {
      i = 1;
      err_msg += "<li>Month should be 2 digits</li>";
    } else if (/^\d{2}$/.test(expiry_month) == false) {
      i = 1;
      err_msg += "<li>Invalid Month</li>";
    }
    if (expiry_year.trim().length == 0) {
      i = 1;
      err_msg += "<li>Year is Required</li>";
    } else if (expiry_year.trim().length < 2 || expiry_year.trim().length > 2) {
      i = 1;
      err_msg += "<li>Year should be 2 digits</li>";
    } else if (/^\d{2}$/.test(expiry_year) == false) {
      i = 1;
      err_msg += "<li>Invalid Year</li>";
    }
    if (cvv.trim().length == 0) {
      i = 1;
      err_msg += "<li>CVV is Required</li>";
    } else if (/^\d+$/.test(cvv) == false) {
      i = 1;
      err_msg += "<li>Invalid CVV</li>";
    }
    err_msg += "</ul>";
    if (i == 1) {
      toastnotification("error", err_msg, "Clear the errors", "", "");
      return false;
    } else {
      return true;
    }
  }
  function add_cart(cart_id, product_id, qty, id_type) {
    $.ajax({
      type: "POST",
      url: base_url + "user/products/managecart",
      data: {
        cart_id: cart_id,
        product_id: product_id,
        qty: qty,
        id_type: id_type,
        csrf_token_name: csrf_token,
      },
      success: function (response) {
        var obj = $.parseJSON(response);

        if (obj.err == false) {
          if (obj.add_rem_type == "add") {
            toastnotification(
              "success",
              "Product Added Successfully",
              "",
              "",
              ""
            );
          } else {
            toastnotification(
              "success",
              "Product Added Successfully",
              "",
              "",
              ""
            );
          }
          $("#qty_" + cart_id).val(qty);
          var pro_tot = $("#product_price_original_" + cart_id).val();
          var tot_amt = pro_tot * qty;
          $("#product_subtotal_" + cart_id).text(tot_amt.toFixed(2));
        } else {
          toastnotification(
            "error",
            "Sorry quantity is not available",
            "",
            "",
            ""
          );
        }
        $(".cart_count").text(obj.count);
        if (cart_id != "") {
          $("#total").text(obj.total);
          $("#sub_total").text(obj.total);
        }
        //
      },
    });
  }
  function country_changes(id, selected_id) {
    if (id != "") {
      $.ajax({
        type: "POST",
        url: base_url + "user/service/get_state_details",
        data: { id: id, csrf_token_name: csrf_token },
        dataType: "json",
        beforeSend: function () {
          $("#state_id").find("option:eq(0)").html("Please wait..");
        },
        success: function (data) {
          $("#state_id option").remove();
          if (data != "") {
            var add = "";
            add += '<option value="">Select State</option>';
            $(data).each(function (index, value) {
              add +=
                "<option value=" + value.id + ">" + value.name + "</option>";
            });
            $("#state_id").append(add);
            if (selected_id != "") {
              $("#state_id option[value=" + selected_id + "]").attr(
                "selected",
                "selected"
              );
            }
          }
        },
      });
    }
  }
  function state_changes(id, selected_id) {
    if (id != "") {
      $.ajax({
        type: "POST",
        url: base_url + "user/service/get_city_details",
        data: { id: id, csrf_token_name: csrf_token },
        dataType: "json",
        beforeSend: function () {
          $("#city_id").find("option:eq(0)").html("Please wait..");
        },
        success: function (data) {
          $("#city_id option").remove();
          if (data != "") {
            var add = "";
            add += '<option value="">Select City</option>';
            $(data).each(function (index, value) {
              add +=
                "<option value=" + value.id + ">" + value.name + "</option>";
            });
            $("#city_id").append(add);
            if (selected_id != "") {
              $("#city_id option[value=" + selected_id + "]").attr(
                "selected",
                "selected"
              );
            }
          }
        },
      });
    }
  }
  function address_type_changes(type) {
    if (type != "") {
      $("#address_types option[value=" + type + "]").attr(
        "selected",
        "selected"
      );
    }
  }
  //
  //Image Upload
  if ($("#add_product, #edit_product").length > 0) {
    document.addEventListener("DOMContentLoaded", init, false);
    //To save an array of attachments
    var AttachmentArray = [];
    //counter for attachment array
    var arrCounter = 0;

    //to make sure the error message for number of files will be shown only one time.
    var filesCounterAlertStatus = false;

    //un ordered list to keep attachments thumbnails
    var ul = document.createElement("ul");
    ul.className = "upload-wrap";
    ul.id = "imgList";

    function init() {
      //add javascript handlers for the file upload event
      document
        .querySelector("#images")
        .addEventListener("change", handleFileSelect, false);
    }

    //the handler for file upload event
    function handleFileSelect(e) {
      //to make sure the user select file/files
      if (!e.target.files) return;

      //To obtaine a File reference
      var files = e.target.files;

      // Loop through the FileList and then to render image files as thumbnails.
      for (var i = 0, f; (f = files[i]); i++) {
        //instantiate a FileReader object to read its contents into memory
        var fileReader = new FileReader();

        // Closure to capture the file information and apply validation.
        fileReader.onload = (function (readerEvt) {
          return function (e) {
            //Apply the validation rules for attachments upload
            ApplyFileValidationRules(readerEvt);

            //Render attachments thumbnails.
            RenderThumbnail(e, readerEvt);

            //Fill the array of attachment
            FillAttachmentArray(e, readerEvt);
          };
        })(f);

        fileReader.readAsDataURL(f);
      }
      document
        .getElementById("images")
        .addEventListener("change", handleFileSelect, false);
    }

    //To remove attachment once user click on x button
    jQuery(function ($) {
      $("div").on("click", ".upload-images .file_close", function () {
        var id = $(this).closest(".upload-images").find("img").data("id");

        //to remove the deleted item from array
        var elementPos = AttachmentArray.map(function (x) {
          return x.FileName;
        }).indexOf(id);
        if (elementPos !== -1) {
          AttachmentArray.splice(elementPos, 1);
        }

        //to remove image tag
        $(this).parent().find("img").not().remove();

        //to remove div tag that contain the image
        $(this).parent().find("div").not().remove();

        //to remove div tag that contain caption name
        $(this).parent().parent().find("div").not().remove();

        //to remove li tag
        var lis = document.querySelectorAll("#imgList li");
        for (var i = 0; (li = lis[i]); i++) {
          if (li.innerHTML == "") {
            li.parentNode.removeChild(li);
          }
        }
      });
    });

    //Apply the validation rules for attachments upload
    function ApplyFileValidationRules(readerEvt) {
      //To check file type according to upload conditions
      if (CheckFileType(readerEvt.type) == false) {
        alert(
          "The file (" +
            readerEvt.name +
            ") does not match the upload conditions, You can only upload jpg/png/gif files"
        );
        e.preventDefault();
        return;
      }

      //To check files count according to upload conditions
      if (CheckFilesCount(AttachmentArray) == false) {
        if (!filesCounterAlertStatus) {
          filesCounterAlertStatus = true;
          alert(
            "You have added more than 10 files. According to upload conditions you can upload 10 files maximum"
          );
        }
        e.preventDefault();
        return;
      }
    }

    //To check file type according to upload conditions
    function CheckFileType(fileType) {
      if (fileType == "image/jpeg") {
        return true;
      } else if (fileType == "image/png") {
        return true;
      } else if (fileType == "image/gif") {
        return true;
      } else {
        return false;
      }
      return true;
    }

    //To check file Size according to upload conditions
    function CheckFileSize(fileSize) {
      if (fileSize < 300000) {
        return true;
      } else {
        return false;
      }
      return true;
    }

    //To check files count according to upload conditions
    function CheckFilesCount(AttachmentArray) {
      //Since AttachmentArray.length return the next available index in the array,
      //I have used the loop to get the real length
      var len = 0;
      for (var i = 0; i < AttachmentArray.length; i++) {
        if (AttachmentArray[i] !== undefined) {
          len++;
        }
      }
      //To check the length does not exceed 10 files maximum
      if (len > 9) {
        return false;
      } else {
        return true;
      }
    }

    //Render attachments thumbnails.
    function RenderThumbnail(e, readerEvt) {
      var ul = document.createElement("ul");
      ul.className = "upload-wrap";
      ul.id = "imgList";
      document.getElementById("uploadPreview").innerHTML = "";
      var li = document.createElement("li");
      ul.appendChild(li);
      li.innerHTML = [
        '<div class=" upload-images"> ' +
          '<a style="display:none;" href="javascript:void(0);" class="file_close btn btn-icon btn-danger btn-sm"><i class="far fa-trash-alt"></i></a><img class="thumb" src="',
        e.target.result,
        '" title="',
        escape(readerEvt.name),
        '" data-id="',
        readerEvt.name,
        '"/>' + "</div>",
      ].join("");

      var div = document.createElement("div");
      div.className = "FileNameCaptionStyle d-none";
      li.appendChild(div);
      div.innerHTML = [readerEvt.name].join("");
      document.getElementById("uploadPreview").insertBefore(ul, null);
    }

    //Fill the array of attachment
    function FillAttachmentArray(e, readerEvt) {
      AttachmentArray[arrCounter] = {
        AttachmentType: 1,
        ObjectType: 1,
        FileName: readerEvt.name,
        FileDescription: "Attachment",
        NoteText: "",
        MimeType: readerEvt.type,
        Content: e.target.result.split("base64,")[1],
        FileSizeInBytes: readerEvt.size,
      };
      arrCounter = arrCounter + 1;
    }
  }
  //order_moyasar_payment

  function order_moyasar_payment() {
    var amount = Number($("#amount").val()) * 100;
    if ($("#language_option").val() != "ar") {
      var lanval = "en";
    } else {
      var lanval = $("#language_option").val();
    }
    Moyasar.init({
      // Required
      // Specify where to render the form
      // Can be a valid CSS selector and a reference to a DOM element
      element: ".order-mysr-form",

      language: lanval,

      // Required
      // Amount in the smallest currency unit
      // For example:
      // 10 SAR = 10 * 100 Halalas
      // 10 KWD = 10 * 1000 Fils
      // 10 JPY = 10 JPY (Japanese Yen does not have fractions)
      amount: amount,

      // Required
      // Currency of the payment transation
      currency: $("#usercurrency").val(),

      // Required
      // A small description of the current payment process
      description: "Confirm order",

      // Required
      publishable_api_key: $("#publishable_apikey").val(),

      // Required
      // This URL is used to redirect the user when payment process has completed
      // Payment can be either a success or a failure, which you need to verify on you system (We will show this in a couple of lines)
      callback_url: $("#order_callbackurl").val(),

      // Optional
      // Required payments methods
      // Default: ['creditcard', 'applepay', 'stcpay']
      methods: ["creditcard"],
    });
  }

  var handler = StripeCheckout.configure({
    key: $("#stripe_key").val(),
    image: $("#logo_front").val(),
    locale: "auto",
    token: function (token, args) {
      // You can access the token ID with `token.id`.
      $("#access_token").val(token.id);
      var tokenid = token.id;
      var order_amt = $("#total_amt").val();
      var order_id = $("#order_id").val();
      var billing_id = $("input[class=select_billing]:checked").val();
      var address_type = $("#address_type").val();
      var order_amount = order_amt * 100;
      var redirect_url = $("#razorpay_redirect").val();
      $.ajax({
        url: base_url + "user/products/stripe_order_payment",
        data: {
          order_id: order_id,
          billing_id: billing_id,
          amount: order_amount,
          token: tokenid,
          address_type: address_type,
          csrf_token_name: csrf_token,
        },
        type: "POST",
        dataType: "JSON",
        success: function (response) {
          if (response == "success") {
            window.location.href =
              base_url + "order-confirmation/" + redirect_url;
          } else {
            window.location.reload();
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    },
  });

  $("#order_stripe_payment").on("click", function (e) {
    var tot_amt = $("#product_tot_amt").val();
    var currency = $("#user_currency").val();
    var tot_amount = tot_amt * 100; //  dollar to cent
    // Open Checkout with further options:
    handler.open({
      name: base_url,
      description: "Order Payment",
      amount: tot_amount,
      currency: currency,
    });
    e.preventDefault();
  });

  $("#rzp-button-product").on("click", function (e) {
    var tot_amt = $("#product_tot_amt").val();
    var currency = $("#user_currency").val();
    var tot_amount = tot_amt * 100;

    var order_id = $("#order_id").val();
    var billing_id = $("input[class=select_billing]:checked").val();
    var address_type = $("#address_type").val();
    var redirect_url = $("#razorpay_redirect").val();

    var options = {
      key: $("#razorpay_apikey").val(),
      amount: tot_amount,
      currency: currency,
      name: "Happy Ceremonies", // Your business name
      description: "product-order-id-" + order_id,
      image: base_url + "uploads/logo/1692544919_logo_happy.png",
      handler: function (response) {
        // Process the payment response here
        if (response.razorpay_payment_id) {
          $.ajax({
            url: base_url + "user/products/razorpay_order_payment",
            data: {
              token: response.razorpay_payment_id,
              order_id: order_id,
              billing_id: billing_id,
              amount: tot_amt,
              address_type: address_type,
              csrf_token_name: csrf_token,
            },
            type: "POST",
            dataType: "JSON",
            success: function (response) {
              if (response == "success") {
                window.location.href =
                  base_url + "order-confirmation/" + redirect_url;
              } else {
                window.location.reload();
              }
            },
            error: function (error) {
              console.log(error);
            },
          });
        } else {
          // Payment failed or was canceled
          swal("Payment failed!", "Please try again.", "error");
        }
      },
      theme: {
        color: "#3399cc",
      },
    };

    var rzp1 = new Razorpay(options);
    rzp1.open();
    e.preventDefault();
  });
})(jQuery);

var base_url = $("#base_url").val();
var csrf_token = $("#csrf_token").val();
var csrfName = $("#csrfName").val();
var csrfHash = $("#csrfHash").val();
function searchFilter(page_num) {
  var f_cat_id = $("#f_cat_id").val();
  var f_pricerange = $("#f_pricerange").val();
  var f_cc = $("#f_cc").val();
  page_num = page_num ? page_num : 0;
  $.ajax({
    type: "POST",
    url: base_url + "user/products/ajaxproductlist/" + page_num,
    data:
      "page=" +
      page_num +
      "&f_cat_id=" +
      f_cat_id +
      "&f_pricerange=" +
      f_pricerange +
      "&f_cc=" +
      f_cc +
      "&csrf_token_name=" +
      csrf_token,
    beforeSend: function () {
      $(".loading").show();
    },
    success: function (html) {
      console.log(html);
      $("#dataList").html(html);
      $(".loading").fadeOut("slow");
      $("html, body").animate({ scrollTop: jQuery("body").offset().top }, 1000);
    },
  });
}
function orderFilter(page_num) {
  page_num = page_num ? page_num : 0;
  $.ajax({
    type: "POST",
    url: base_url + "user/products/ajaxuserorders/" + page_num,
    data:
      "page=" +
      page_num +
      "&order_code=" +
      $("#order_code").val() +
      "&csrf_token_name=" +
      csrf_token +
      "&shop_name=" +
      $("#shop_name").val() +
      "&product_name=" +
      $("#product_name").val() +
      "&delivery_status=" +
      $("#delivery_status").val(),
    beforeSend: function () {
      $(".loading").show();
    },
    success: function (html) {
      $("#orderList").html(html);
      $(".loading").fadeOut("slow");
      $("html, body").animate({ scrollTop: jQuery("body").offset().top }, 1000);
    },
  });
}

function checkordercancel(cart_id, order_id) {
  $.ajax({
    type: "GET",
    url: base_url + "user/products/checkordercancel",
    data: {
      submit_type: "check",
      cart_id: cart_id,
      order_id: order_id,
      csrf_token_name: csrf_token,
    },
    success: function (response) {
      var obj = $.parseJSON(response);
      if (obj.error == true) {
        swal({
          title: "Cannot Be cancelled",
          text: obj.msg,
          icon: "error",
          button: "okay",
          closeOnEsc: false,
          closeOnClickOutside: false,
        }).then(function () {});
      } else {
        //modal
        $("#order_cancel_pop").modal("show");
        $("#hc_id").val(cart_id);
        $("#ho_id").val(order_id);
      }
    },
  });
}
function cancel_order() {
  var cancel_reason = $("#cancel_reason").val();
  if (cancel_reason != "") {
    $.ajax({
      type: "POST",
      url: base_url + "user/products/checkordercancel?submit_type=delete",
      data: {
        cart_id: $("#hc_id").val(),
        order_id: $("#ho_id").val(),
        csrf_token_name: csrf_token,
        cancel_reason: cancel_reason,
      },
      success: function (response) {
        var obj = $.parseJSON(response);
        // toastnotification(obj.error ? "error" : "success", obj.msg, "", "", "");
        swal({
          title: obj.error
            ? "Failed to cancel properly"
            : "Successfully Cancelled",
          text: obj.msg,
          icon: obj.error ? "error" : "success",
          button: "okay",
          closeOnEsc: false,
          closeOnClickOutside: false,
        }).then(function () {
          window.location.reload();
        });
      },
    });
  } else {
    toastnotification("error", "Reason is required", "", "", "");
  }
}
function porderFilter(page_num) {
  page_num = page_num ? page_num : 0;
  $.ajax({
    type: "POST",
    url: base_url + "user/products/ajaxproviderorders/" + page_num,
    data:
      "page=" +
      page_num +
      "&order_code=" +
      $("#order_code").val() +
      "&csrf_token_name=" +
      csrf_token +
      "&user_name=" +
      $("#user_name").val() +
      "&product_name=" +
      $("#product_name").val() +
      "&delivery_status=" +
      $("#delivery_status").val(),
    beforeSend: function () {
      $(".loading").show();
    },
    success: function (html) {
      $("#orderList").html(html);
      $(".loading").fadeOut("slow");
      $("html, body").animate({ scrollTop: jQuery("body").offset().top }, 1000);
    },
  });
}
function filter_porder(stype) {
  if (stype == "c") {
    $(".cf").val("");
  }
  porderFilter(0);
}
function filterdeliverystatus() {
  porderFilter(0);
}
function changedeliverystatus(cart_id, cds, pc) {
  $.ajax({
    type: "POST",
    url: base_url + "user/products/change_delivery_status",
    data: { cart_id: cart_id, cds: cds, csrf_token_name: csrf_token },
    success: function (response) {
      porderFilter(pc);
    },
  });
}
function view_order(cart_id, order_id) {
  $.ajax({
    type: "POST",
    url: base_url + "user/products/order_details",
    data: { cart_id: cart_id, order_id: order_id, csrf_token_name: csrf_token },
    success: function (response) {
      var obj = $.parseJSON(response);
      $("#order_details_pop").modal("show");
      $.each(obj.order, function (key, value) {
        $("#d_" + key).html(value);
      });
      $("#c_delivery_address").html(obj.delivery_address);
    },
  });
}

function view_provider_order(cart_id, order_id) {
  $.ajax({
    type: "POST",
    url: base_url + "user/products/provider_order_details",
    data: { cart_id: cart_id, order_id: order_id, csrf_token_name: csrf_token },
    success: function (response) {
      var obj = $.parseJSON(response);
      console.log(response); //return false;
      $("#view_order").modal("show");
      $.each(obj.order, function (key, value) {
        $("#d_" + key).html(value);
        $("#p_" + key).html(value);
        $("#p_sub_" + key).html("$" + value);
        $("#d_pro_" + key).html("$" + value);
        $("#p_pros_" + key).html("$" + value);
      });
      $("#c_delivery_address").html(obj.delivery_address);
      $("#p_product_name").html(obj.product_name);
      $("#p_product_img").attr("src", obj.product_img);
    },
  });
}

function check_product_delete(product_id) {
  $("#deleteConfirmModal").modal("show");
  $("#hp_did").val(product_id);
}
function confirm_product_delete() {
  $.ajax({
    type: "POST",
    url: base_url + "user/products/delete_product",
    data: { product_id: $("#hp_did").val(), csrf_token_name: csrf_token },
    success: function (response) {
      toastnotification("success", "Product Deleted Successfully", "", "", "");
      $("#prow_" + $("#hp_did").val()).fadeOut(300, function () {
        $(this).remove();
      });
      $("#deleteConfirmModal").modal("hide");
    },
  });
}
function getData(page_num) {
  page_num = page_num ? page_num : 0;
  $.ajax({
    type: "POST",
    url: base_url + "user/products/ajaxmyproducts/" + page_num,
    data:
      "page=" +
      page_num +
      "&csrf_token_name=" +
      csrf_token +
      "&shop_id=" +
      $("#hshop_id").val(),
    beforeSend: function () {
      $(".loading").show();
    },
    success: function (html) {
      console.log(html);
      $("#mplist").html(html);
    },
  });
}

function empty_form() {
  alert();
  $("#billing_details_form").trigger("reset");
}
