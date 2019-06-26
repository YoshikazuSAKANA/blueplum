$(function() {
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
});
