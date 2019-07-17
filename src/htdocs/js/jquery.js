$(function() {
var api = 'https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404';
var applicationId = '1025653256501622754';
var affiliateId = '1d009b0.0dfb50a9.18d009b1.13448471';
var keyword = '佐藤 優';
    // Ajax button click
    $('#button_entry_task').on('click', function() {
        $.ajax({
            url:'/ajax.php',
            type:'POST',
            data: {
                'user_id':$('#user_id').val(),
                'entry_task':$('#entry_task').val()
                  }
        })
        // Ajaxリクエスト成功時
        .done((data) => {
            $('.result').html(data);
            alert('タスク追加しました');
        })
        // Ajaxリクエスト失敗
        .fail((data) => {
            $('.result').html(data);
        })
    });

    $('#search_book').on('click', function() {
        $.ajax({
            url:api,
            type:'GET',
            data: {
                'applicationId' : applicationId,
                'affiliateId'   : affiliateId,
                'author'        : keyword,
		'hits'          : 30,
		'page'          : 9,
		'carrier'       : 0,
		'formatVersion' : 2,
		'format'        : 'json',
	        'sort'          : 'sales'
                  }
        })
        // Ajaxリクエスト成功時
        .done((data) => {
            alert("OK");
            $('p').text(data);
        })
        // Ajaxリクエスト失敗
        .fail((data) => {
            alert('failed');
            console.log(data);
        })
    });
});
