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
        if(!$('#notAlert').is(':checked')){
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
        $('#alert_notice').removeClass('hidden');
      }
      });

    $('#notAlert').change(function() {
      if ($('#notAlert').is(':checked')) {
        $('#alert_config')
        .prop('disabled', true)
        .addClass('bg-gray-200')
        .val('');
      } else {
        $('#alert_config')
        .prop('disabled', false)
        .removeClass('bg-gray-200');
      }
    });
    if($('#notAlert').is(':checked')){
      $('#alert_config')
      .prop('disabled', true)
      .addClass('bg-gray-200')
      .val('');
    }

    // ログの横の▼ボタンを押すと詳細を表示
    $('.view-log-button').on('click', function(){
      var i = $('.view-log-button').index(this);
      $('.log-content').eq(i).fadeToggle();
      $(this).toggleClass('opened-view-log');
    });
    $('body').on('click', '#view-all-logs', function(){
      $('.log-content').fadeIn();
      $('.view-log-button').addClass('opened-view-log');
      $(this).addClass('opened-view-all');  
    });
    $('body').on('click', '.opened-view-all', function(){
      $('.log-content').css('display', 'none');
      $('.view-log-button').removeClass('opened-view-log');
      $(this).removeClass('opened-view-all');
    });
  });
