// Carousel
$(document).ready(function() {
  $(".lazy").slick({
    dots: true,
    lazyLoad: 'ondemand', // ondemand progressive anticipated
    infinite: true
  });
});


// Search
$(document).ready(function() {
  $("#ajax_form").submit(function() {
    search('result_form', 'ajax_form', 'search.php');
    return false;
  });
});

$(document).ready(function() {
  $("#query").keydown(function(event) {
    if(event.keyCode == 13) {
      search('result_form', 'ajax_form', 'search.php');
      return false;
    }
  });
});

$(document).ready(function() {
  $("#query").focusout(function() {
    search('result_form', 'ajax_form', 'search.php');
    return false;
  });
});

function search(result_form, ajax_form, url) {
  $.ajax({
    url: url, //url страницы (action_ajax_form.php)
    type: "POST", //метод отправки
    dataType: "html", //формат данных
    data: $("#" + ajax_form).serialize(),  // Сеарилизуем объект
    success: function(html) {
      $('#search_result').html(html);
    },
    error: function(response) { // Данные не отправлены
      $('#result_form').html('Ошибка. Данные не отправлены.');
    }
  });
};


// Notification
function notification(text) {
  $.ajax({
    url: "notification.html",
    dataType: "html",
    success: function(html) {
      $('#notification').html(html);
      var element = document.getElementById("notification_text").textContent;
      document.getElementById("notification_text").textContent = text;
    },
  });
};


// Page UP
$(function() {
  $(window).scroll(function() {
    if($(this).scrollTop() != 0)
      $('#toTop').fadeIn();
    else $('#toTop').fadeOut();
  });
  $('#toTop').click(function() {
    $('body,html').animate({
      scrollTop: 0
    }, 300);
  });
});


$('.show-popup').magnificPopup({
  type: 'inline',
  removalDelay: 500, //delay removal by X to allow out-animation
  callbacks: {
    beforeOpen: function () {
        this.st.mainClass = this.st.el.attr('data-effect');
    }
  },
  midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
});

var mh = 0;
$(".size").each(function () {
  var h_block = $(this).height();
  if(h_block > mh) {
     mh = h_block;
  };
});
$(".size_").height(mh);
