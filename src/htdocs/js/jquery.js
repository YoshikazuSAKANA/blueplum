$(function() {
    // Ajax button click
    $('#button_entry_task').on('click', function() {
        $.ajax({
            url:'/ajax.php',
            type:'POST',
            data: { 'entry_task':$('#entry_task').val()}
        })
        // Ajaxリクエスト成功時
        .done((data) => {
            $('.result').html(data);
            console.log(data);
        })
        .fail((data) => {
            $('.result').html(data);
            console.log(data);
        })
        .always((data) => {
            alert('aaa');
        });
    });
});
