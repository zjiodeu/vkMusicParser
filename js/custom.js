$(function() {
    $('.folder').on('click', function() {
        var path = document.domain;
        location = 'http://' + path + '?directory=' + $(this).text();
    });
    $('input[name="target"]').on('mouseenter',function() {
        var val = $(this).val();
        $(this).attr('title', val);
    });
    
})

