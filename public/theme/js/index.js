$(function(){
    $(".js-btn-delete").click(function(){
        if(confirm('Удалить?') === false)
            return false;
    });
    $(".js-btn-excluded").click(function(){
        if(confirm('Исключить сотрудника?') === false)
            return false;
    });
});