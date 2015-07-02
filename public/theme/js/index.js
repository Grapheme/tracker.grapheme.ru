$(function(){
    $(".js-btn-delete").click(function(){
        if(confirm('Удалить?') === false)
            return false;
    });
});