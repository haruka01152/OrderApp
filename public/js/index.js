$(function() {
    // ヘルプのモーダル設定
    $('.js-open').on('click', function() {
      $('#overlay, .modal-window').fadeIn();
      return false;
    });
    $('.js-close, #overlay').on('click', function() {
      $('#overlay, .modal-window').fadeOut();
      return false;
    });

    // PDFのモーダル表示設定
    $('.pdf').modaal({
      type: 'iframe',
    });

    // メッセージの表示設定
    $('#target_msg_box').delay(1000).fadeOut(800);

    // カレンダーで顧客名にホバーすると案件名が表示される設定
    $('.a-text').hover(
      function() {
        var i = $('.a-text').index(this);
        $('.fukidashi').eq(i).css('display', 'block');
      },
      function() {
        var i = $('.a-text').index(this);
        $('.fukidashi').eq(i).css('display', 'none');
      });

    //   工事日を入力するとアラート発出日が自動入力される設定
    $('#construction_date').change(function() {
      var str = $(this).val();
      str = new Date(str);
      str.setDate(str.getDate() - 5);
      var year = str.getFullYear();
      var month = str.getMonth() + 1;
      var day = str.getDate();
      
      if(String(month).length == 1){
        month = '0'+month;
      }
      if(String(day).length == 1){
        day = '0'+day;
      }
      $('#alert_config').val(year+'-'+month+'-'+day);
    });

    $('#notAlert').change(function() {
      if ($('#notAlert').is(':checked')) {
        $('#alert_config').prop('disabled', true);
        $('#alert_config').addClass('bg-gray-200');
      } else {
        $('#alert_config').prop('disabled', false);
        $('#alert_config').removeClass('bg-gray-200');
      }
    });
  });
