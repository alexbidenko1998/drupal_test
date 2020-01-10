jQuery( document ).ready(function() {
  jQuery(".json-renderer").each(function() {
    var obj = JSON.parse( jQuery(this).text());
    jQuery(this).jsonViewer(obj, {collapsed: 2, withQuotes: true});
  });

  jQuery( "a.blocked" ).click( function(event) {
    event.preventDefault();

    var message = "Вы точно хотите выполнить это действие?"
    if (!confirm(message)) {
      return false;
    }


    // var url = jQuery(this).attr("href");
    var accountId = jQuery(this).attr("rel") ;


    var url = '/api/setBlockedStatus?accountId='+accountId;
    if(jQuery(this).hasClass('blocking')){
      url += '&blocked=true';
    } else {
      url += '&blocked=false';
    }


    jQuery.ajax({
      type: "GET",
      url: url,
      success: function (msg) {
        // console.log('work');
        if(msg["error"] == "ERROR_NOT") {
          /*
          // Ошибочный код
          if(jQuery(this).hasClass('blocking')){
            alert('Аккаунт успешно заблокирован');
          } else {
            alert('Аккаунт успешно разблокирован');
          }
          */
          alert('Выполнено');
          location.replace(location.toString());
        } else {
          alert("Не удалось выполнить действие");
        }

      },
      error: function (xhr) {
        alert("Не удалось выполнить действие. Проверте интернет соединение");
      }
    });
  });
});