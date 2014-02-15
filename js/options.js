$(function(){
    $('#addAlias').on('click', function() {
        var field = $(this).prev();
        field.clone().insertBefore($(this));
    });
    $('#removeAlias').on('click', function() {
        var target = $('input[name="XMLOptions[alias][]"]');
        if (target.length > 1)
            target.last().remove();
    });
    
    $('.bands').on('click', function(e){
        //console.log(e.target.tagName);
        if (e.target.tagName === 'A') {
            $(e.target).next().slideToggle('slow');
        }
    });
})


