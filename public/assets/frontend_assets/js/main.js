"use strict";

const canvas = document.getElementById("custom_canvas");
const jsConfetti = new JSConfetti({ canvas });

$(document).ready(function () {
  var body = $("body");
  var bodyParent = $("html");
  /* page load as iframe */
  if (self !== top) {
    body.addClass("iframe");
  } else {
    body.removeClass("iframe");
  }
  /* menu open close */
  $(".menu-btn").on("click", function () {
    if (body.hasClass("filter-open") === true) {
      body.removeClass("filter-open");
    }

    if (body.hasClass("menu-open") === true) {
      body.removeClass("menu-open");
      bodyParent.removeClass("menu-open");
    } else {
      body.addClass("menu-open");
      bodyParent.addClass("menu-open");
    }
    return false;
  });


  //Open Location Box

  $(".geo-btn").on("click", function () {
    $("#geopopup").modal("show");
  });


  body.on("click", function (e) {
    if (
      !$(".sidebar").is(e.target) &&
      $(".sidebar").has(e.target).length === 0
    ) {
      body.removeClass("menu-open");
      bodyParent.removeClass("menu-open");
    }
    if (!$(".filter").is(e.target) && $(".filter").has(e.target).length === 0) {
      body.removeClass("filter-open");
      bodyParent.removeClass("filter-open");
    }
    return true;
  });
  /* menu style switch */
  $("#menu-pushcontent").on("change", function () {
    if ($(this).is(":checked") === true) {
      body.addClass("menu-push-content");
      body.removeClass("menu-overlay");
    }
    return false;
  });
  $("#menu-overlay").on("change", function () {
    if ($(this).is(":checked") === true) {
      body.removeClass("menu-push-content");
      body.addClass("menu-overlay");
    }
    return false;
  });
  /* back page navigation */
  $(".back-btn").on("click", function () {
    window.history.back();
    return false;
  });
  /* Filter button */
  $(".filter-btn").on("click", function () {
    if (body.hasClass("filter-open") === true) {
      body.removeClass("filter-open");
    } else {
      body.addClass("filter-open");
    }
    return false;
  });
  $(".filter-close").on("click", function () {
    if (body.hasClass("filter-open") === true) {
      body.removeClass("filter-open");
    }
  });
  /* scroll y limited container height on page  */
  var scrollyheight =
    Number(
      $(window).height() -
      $(".header").outerHeight() -
      $(".footer-info").outerHeight()
    ) - 40;
  $(".scroll-y").height(scrollyheight);
  CheckAgesessionset();
  addststic();
  checkUsernameset();
  checkEmailset();
  CheckGeosessionset();
});
$(window).on("load", function () {
  setTimeout(function () {
    $(".loader-wrap").fadeOut("slow");
  }, 500);
  /* coverimg */
  $(".coverimg").each(function () {
    var imgpath = $(this).find("img");
    $(this).css("background-image", "url(" + imgpath.attr("src") + ")");
    imgpath.hide();
  });
  /* main container minimum height set */
  if ($(".header").length > 0 && $(".footer-info").length > 0) {
    var heightheader = $(".header").outerHeight();
    var heightfooter = $(".footer-info").outerHeight();
    var containerheight = $(window).height() - heightheader - heightfooter - 2;
    $(".main-container ").css("min-height", containerheight);
  }
  /* url path on menu */
  var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
  $(" .main-menu ul a").each(function () {
    if (this.href === path) {
      $(" .main-menu ul a").removeClass("active");
      $(this).addClass("active");
    }
  });
  /* tooltip */
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
$(window).on("scroll", function () {
  /* scroll from top and add class */
  if ($(document).scrollTop() > "10") {
    $(".header").addClass("active");
  } else {
    $(".header").removeClass("active");
  }
});
$(window).on("resize", function () {
  /* main container minimum height set */
  if ($(".header").length > 0 && $(".footer-info").length > 0) {
    var heightheader = $(".header").outerHeight();
    var heightfooter = $(".footer-info").outerHeight();
    var containerheight = $(window).height() - heightheader - heightfooter;
    $(".main-container ").css("min-height", containerheight);
  }
});

//----------------------------------functions------------------------------------

//click on category
$(document).on("click", ".main_header", function () {
  var id = $(this).data("id");
  var is_clicked = $(this).attr("data-isclicked");
  if (is_clicked == 0) {
    $(this).attr("data-isclicked", 1);

    var data = {};
    data["type"] = "category";
    data["id"] = id;

    getHomeData(data);
  }
});

//click on brand
$(document).on("click", ".main_header_1", function () {
  var id = $(this).data("id");
  var cid = $(this).data("cid");
  var is_clicked = $(this).attr("data-isclicked");
  if (is_clicked == 0) {
    $(this).attr("data-isclicked", 1);

    var data = {};
    data["type"] = "product";
    data["id"] = id;
    data["cid"] = cid;

    getHomeData(data);
  }
});

//sidebar open button
function filter_button() {
  var data = {};
  data["type"] = "sidebar";
  getHomeData(data);
}

//product detail
$(document).on("click", ".view_product_detail", function () {
  var id = $(this).data("id");
  var page = $(this).data("page");
  var data = {};
  data["type"] = "product_detail";
  data["id"] = id;
  data["page"] = page;
  getHomeData(data);
});

function getHomeData(perm) {
  if (perm.type != "sidebar") {
    $(".loader-wrap").show();
  }

  $.ajaxSetup({
    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
  });
  $.ajax({
    url: "/getHomeData",
    type: "POST",
    data: perm,
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        //display product
        if (perm.type == "product" && perm.id && perm.cid) {
          $("#ball_" + perm.id + perm.cid).html(data.html);
        }
        //display brand
        else if (perm.type == "category" && perm.id) {
          $("#coll_" + perm.id).html(data.html);
        }
        //display category
        else if (perm.type == "category") {
          $("#prd_data").html(data.html);
        }
        //sidebar panel
        else if (perm.type == "sidebar") {
          $(".filter_data").html(data.html);
        } else if (perm.type == "product_detail") {
          $("#prd_dtl").html(data.html);

          $("#viewproductdetails").modal("toggle");

          var swiper5 = new Swiper(".imageswiper", {
            slidesPerView: "1",
            spaceBetween: 12,
            pagination: {
              el: ".imageswiper-pagination",
            },
            observer: true,
            observeParents: true,
          });
        }
      }
    },
    error: function (data) {
      console.log(data);
    },
    complete: function () {
      $(".loader-wrap").hide();
    },
  });
  cartcounter();
}

function addToCart(type, prd_id, qty, page) {
  if (type && prd_id && qty) {
    var perm = {};
    perm["type"] = type;
    perm["id"] = prd_id;
    perm["qty"] = parseInt(qty);

    $.ajaxSetup({
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
    });
    $.ajax({
      url: "/addCart",
      type: "POST",
      data: perm,
      success: function (data) {
        data = JSON.parse(data);
        if (data.type == "success") {
          //alert(data.message);
          CartButton(prd_id, page);
          // getCartData();
        } else if (data.type == "error") {
          //console.log(data.message);
          toastr.error(data.message);
          CartButton(prd_id, page);
        }
        if (page == "cart") {
          getCartData();
        }
        if (page == "wishlist") {
          getWishlistData();
        }
        if (page == "recent_view") {
          getRecentData();
        }
        cartcounter();
      },
      error: function (data) {
        console.log(data);
      },
      complete: function () {
        $(".loader-wrap").hide();
      },
    });
  }
}

function addTowishlist(prd_id, page) {
  if (prd_id) {
    var perm = {};
    perm["id"] = prd_id;
    perm["page"] = page;

    $.ajaxSetup({
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
    });
    $.ajax({
      url: "/addWishlist",
      type: "POST",
      data: perm,
      success: function (data) {
        data = JSON.parse(data);
        if (data.type == "success") {
          //alert(data.message);
          CartButton(prd_id, page);
        } else if (data.type == "error") {
          //console.log(data.message);
          toastr.error(data.message);
          CartButton(prd_id, page);
        }
        if (page == "wishlist") {
          getWishlistData();
        }
        if (page == "recent_view") {
          getRecentData();
        }
      },
      error: function (data) {
        console.log(data);
      },
      complete: function () {
        $(".loader-wrap").hide();
      },
    });
  }
}

function CartButton(prd_id, page) {
  if (prd_id) {
    $.ajax({
      url: "/cart_btn/" + prd_id + "/" + page,
      type: "GET",
      success: function (data) {
        //$('#cart_btn_dspl_'+prd_id).html(atob(data));
        $('[id="cart_btn_dspl_' + prd_id + '"]').html(atob(data));
      },
    });
  }
}

//for cart page
function getCartData() {
  $.ajaxSetup({
    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
  });
  $.ajax({
    url: "/getCartData",
    type: "POST",
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        $("#offer").html("");
        $("#cart_data").html(data.html);
        $("#subtotal").html(data.sub_ttl);
        $("#shipping_cost").html(data.shipping);
        $("#more_cost").html(data.shipping);
        $("#bill_amount").html(data.total);
        $("#discount").html(data.discount);
        $("#extra_discount").html(data.extra_discount);

        var extraDiscountAmountGuest =
          ((data.sub_ttl - data.discount) * 10) / 100;

        if (data.extra_discount > 0) {
          toastr.success(
            data.extra_discount_percentage + "% Extra Discount Applied"
          );
          jsConfetti.addConfetti({
            confettiNumber: 700,

            // confettiNumber: 50,
            // emojis: ["🎉", "🎁", "🎊", "🥳"],
          });
        } else {

          if (data.extra_discount_percentage > 0) {
            toastr.error("Please Login or Register to get Extra Discount");
            $("#guestpopup").modal("show");

            var html =
              "Please Login or Register to get 10% extra discount or save £" +
              extraDiscountAmountGuest.toFixed(2) +
              "";

            document.getElementById("guest_user_extra_discount_msg").innerHTML =
              "<b>" + html + "</b>";
          }


        }

        if (data.shipping > 0) {
          $(".cart_extra_discount").show();
        } else {
          $(".cart_extra_discount").hide();
        }

        var referral = data.referral;
        var referral_percentage = data.referral_percentage;

        if (referral && referral_percentage) {
          $("#affiliate").show();
          $("#reffreal_perc").html(
            "Affiliate Discount (" + referral_percentage + "%)"
          );
          $("#referral").html(referral);
        } else {
          $("#affiliate").hide();
        }

        var cnt = 0;
        var i = 0;
        var offer_data = null;

        if (data.offers.length > 0) {
          if (data.offers[i] != null) {
            $.each(data.offers, function (index, value) {
              cnt = data.offers.length;

              $.each(value, function (index, val) {
                offer_data = "(" + index + ") x " + val + " ";
                if (cnt != i) {
                  offer_data + ",";
                }
                $("#offer").append(offer_data);
                i++;
              });
            });
          }
        } else {
          $("#offer").html("");
        }

        if (data.sub_ttl <= 0) {
          location.href = "/";
        }
      }
    },
    error: function (data) { },
    complete: function () {
      $(".loader-wrap").hide();
    },
  });
  //cartcounter();
}

function getExtraCartData() {
  $.ajaxSetup({
    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
  });
  $.ajax({
    url: "/getExtraCartData",
    type: "POST",
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        $("#extra_item").html(data.html);
      }
    },
    error: function (data) { },
    complete: function () {
      $(".loader-wrap").hide();
    },
  });
  //cartcounter();
}

function getWishlistData() {
  $.ajaxSetup({
    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
  });
  $.ajax({
    url: "/getWishlistData",
    type: "POST",
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        $("#wishlist_data").html(data.html);
      }
      cartcounter();
    },
    error: function (data) { },
    complete: function () {
      $(".loader-wrap").hide();
    },
  });
}

function getRecentData() {
  $.ajaxSetup({
    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
  });
  $.ajax({
    url: "/getRecentData",
    type: "POST",
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        $("#recent_data").html(data.html);
      }
      cartcounter();
    },
    error: function (data) { },
    complete: function () {
      $(".loader-wrap").hide();
    },
  });
}

function set_cookie() {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    url: "/set_cookie",
    type: "POST",
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        $("#cookie_alert").alert("close");
      }
    },
  });
}

//-------------------------------------------------------- search_suggetion----------------------------------------

function searchSuggetion() {
  var search_data = $("#search").val();
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    url: "/searchSuggetion",
    type: "POST",
    data: {
      search_data: search_data,
    },
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        $("#result").html(data.data.html);
      }
    },
  });
}

function cartcounter() {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    url: "/cartcounter",
    type: "POST",
    success: function (data) {
      $("#cartcounter").html(data);
    },
  });
}

$(document).on("change", "#couponcode", function () {
  var coupon = $(this).val();
  if (coupon) {
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    $.ajax({
      url: "/applycoupon",
      type: "POST",
      data: { coupon: coupon },
      success: function (data) {
        data = JSON.parse(data);
        if (data.type == "success") {
        }
      },
    });
  }
});

//for set username
function checkUsernameset() {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    url: "/checkUsernameset",
    type: "POST",
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        if (data.islogin == 1) {
          if (data.isset == 1) {
            $("#namepopup").modal("hide");
          } else {
            $("#namepopup").modal("show");
          }
        }
      }
    },
  });
}

//for email set
function checkEmailset() {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    url: "/checkEmailset",
    type: "POST",
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        if (data.islogin == 1) {
          if (data.isset == 1) {
            $("#emailpopup").modal("hide");
          } else {
            $("#emailpopup").modal("show");
          }
        }
      }
    },
  });
}

//for check age session
function CheckAgesessionset() {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    url: "/checkagesessionset",
    type: "POST",
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        if (data.isset == 1) {
          $("#agepopup").modal("hide");
        } else {
          $("#agepopup").modal("show");
        }
      }
    },
  });
}


function CheckGeosessionset() {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    url: "/checkgeosessionset",
    type: "POST",
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        if (data.isset == 1) {
          $("#geopopup").modal("hide");
        } else {
          $("#geopopup").modal("show");
        }
      }
    },
  });
}


$(document).on("click", ".guest_ok_btn", function () {
  $("#guestpopup").modal("hide");
});

$(document).on("click", ".age_btn", function () {
  var value = $(this).data("val");
  if (value == "yes") {
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    $.ajax({
      url: "/agesessionset",
      type: "POST",
      data: {
        value: value,
      },
      success: function (data) {
        data = JSON.parse(data);
        if (data.type == "success") {
          if (data.isset == 1) {
            $("#agepopup").modal("hide");
            if (data.banner_image > 0) {
              setTimeout(function () {
                OpneBanner();
              }, 1000);
            }
          } else {
            location.reload();
          }
        }
      },
    });
  }
});

function addststic() {
  $("#agepopup").modal({
    backdrop: "static",
    keyboard: false,
  });

  $("#namepopup").modal({
    backdrop: "static",
    keyboard: false,
  });

  $("#emailpopup").modal({
    backdrop: "static",
    keyboard: false,
  });

  $("#geopopup").modal({
    backdrop: "static",
    keyboard: false,
  });
}

//for check banner session
function OpneBanner() {
  $("#bannerpopup").modal("show");
}

function OpneDiscountBanner() {
  $("#discountpopup").modal("show");
}

//for payment page

$(document).on("change", "#same_as_ship_checkbox", function () {
  var checked = $(this).prop("checked");
  getBillingAddress(checked);
});

function getBillingAddress(checked, method) {
  if (method) {
    $("#paynow_frm").hide();
  }

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    url: "/getBillingAddress",
    type: "POST",
    data: {
      checked: checked,
      method: method,
    },
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        $("#bill_div").html(data.html);
      }
    },
    error: function (data) {
      console.log(data);
    },
  });
}

function getShipingAddress(method = null) {
  if (method) {
    $("#paynow_frm").hide();
  }

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $.ajax({
    url: "/getShipingAddress",
    type: "POST",
    data: {
      method: method,
    },
    success: function (data) {
      data = JSON.parse(data);
      if (data.type == "success") {
        $("#ship_div").html(data.html);
      }
    },
    error: function (data) {
      console.log(data);
    },
  });
}

$(document).on("click", "#add_new_shipping_btn", function () {
  var name = $("#name").val();
  var addr_line_1 = $("#addr_line_1").val();
  var addr_line_2 = $("#addr_line_2").val();
  var city = $("#city").val();
  var pin = $("#pin").val();
  var state = $("#state").val();
  var country = $("#country").val();
  var type = "shipping";

  if (!name) {
    toastr.error("Name field cannot be null");
  } else if (!addr_line_1) {
    toastr.error("address line 1 field cannot be null");
  } else if (!city) {
    toastr.error("city field cannot be null");
  } else if (!pin) {
    toastr.error("pin field cannot be null");
  } else if (!state) {
    toastr.error("state field cannot be null");
  } else if (!country) {
    toastr.error("country field cannot be null");
  }

  if (name && addr_line_1 && city && pin && state && country) {
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    $.ajax({
      url: "/addNewAddress",
      type: "POST",
      data: {
        name: name,
        addr_line_1: addr_line_1,
        addr_line_2: addr_line_2,
        city: city,
        pin: pin,
        state: state,
        country: country,
        type: type,
      },
      success: function (data) {
        data = JSON.parse(data);
        if (data.type == "success") {
          getShipingAddress();
        } else if (data.type == "error") {
          toastr.error(data.msg);
        }
      },
    });
  }
});

$(document).on("click", "#add_new_billing_btn", function () {
  //$('#bill_csrf').val($('meta[name="csrf-token"]').attr('content'));
  var name = $("#name").val();
  var addr_line_1 = $("#addr_line_1").val();
  var addr_line_2 = $("#addr_line_2").val();
  var city = $("#city").val();
  var pin = $("#pin").val();
  var state = $("#state").val();
  var country = $("#country").val();
  var type = "billing";

  if (!name) {
    toastr.error("Name field cannot be null");
  } else if (!addr_line_1) {
    toastr.error("address line 1 field cannot be null");
  } else if (!city) {
    toastr.error("city field cannot be null");
  } else if (!pin) {
    toastr.error("pin field cannot be null");
  } else if (!state) {
    toastr.error("state field cannot be null");
  } else if (!country) {
    toastr.error("country field cannot be null");
  }

  if (name && addr_line_1 && city && pin && state && country) {
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    $.ajax({
      url: "/addNewAddress",
      type: "POST",
      data: {
        name: name,
        addr_line_1: addr_line_1,
        addr_line_2: addr_line_2,
        city: city,
        pin: pin,
        state: state,
        country: country,
        type: type,
      },
      success: function (data) {
        data = JSON.parse(data);
        if (data.type == "success") {
          getShipingAddress();
        } else if (data.type == "error") {
          toastr.error(data.msg);
        }
      },
    });
  }
});

function seterror(id) {
  $(id).addClass("is-invalid");
  $(id).focus();
}

$(document).on("click", ".banner_btn", function () {
  $("#bannerpopup").modal("hide");
});


function initAutocomplete() {
  const input = document.getElementById("my_location");
  const autocomplete = new google.maps.places.Autocomplete(input, {
    componentRestrictions: { country: "uk" }, // restrict to UK
    fields: ["address_components", "geometry"]
  });

  autocomplete.addListener("place_changed", function () {
    const place = autocomplete.getPlace();

    let postcode = "";
    place.address_components.forEach(comp => {
      if (comp.types.includes("postal_code")) {
        postcode = comp.long_name;
      }
    });


    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    $.ajax({
      url: "/geosessionset",
      type: "POST",
      data: {
        postcode: postcode.split(" ")[0],
      },
      success: function (data) {
        data = JSON.parse(data);
        if (data.type == "success") {
          location.reload();
        }
      },
    });


  });
}

google.maps.event.addDomListener(window, "load", initAutocomplete);
