$(function(){
    /*
     * SONGS - contains serialized VK songs
     */
    $('input[type="checkbox"]').on('click',function() {
        var called = $(this).attr('name'),
            info = {};            
        if (typeof window.SONGS === 'undefined') {
            alert('Проблемы с аяксом, на помошчь-на помошчь');
            return false;
        }
        
        if (called === 'chsinger') {       
            singerChBox.call($(this));
            return;
        }
       
       info.song = getSongInfo.call($(this)); 
       info.singer = getSingerInfo.call($(this));

       switchStatus.call($(this),info);
    });

    $('#saveMusic').on('click', function(){
        $.post('/index.php?r=site/accept',{"songs":JSON.stringify(SONGS)});
        $('#mydialog').dialog('close');
    });
});


 function singerChBox() {
    var parent = this.closest('.block'),
    status = this.is(':checked'),
    singer = getSingerInfo.call(this);
    if (SONGS[singer]) {
        SONGS[singer].enabled = !SONGS[singer].enabled;
    }
    console.log(SONGS);
    if (!status) {
        parent.find('input[type="checkbox"]').removeAttr('checked');
        parent.find('input[name="chsong"]').attr('disabled','disabled');
    }
    else {
        parent.find('input[type="checkbox"]').attr('checked','checked'); 
        parent.find('input[name="chsong"]').removeAttr('disabled'); 
    }
    
 }
 
 function getSongInfo() {
    var parent = this.parent(),
        content,
        regExp = /^\d+?\)\s?(.*)/m,
        songName = '';
        try {
        content = parent.contents().filter(function() {
            return (this.nodeType === 3);
        });
        } catch(e) {
            console.log(e);
        }
        songName = regExp.exec(content[0].data);
        console.log(songName[1]);
        return songName[1].replace(/\s/g,'+');
 }
 
 function getSingerInfo() {
    var block = this.closest('.block'),
        content,
        singerName;
        content = block.children('.singer').find('b').text();
        singerName = content.replace(/\s/g,'+');      
        return singerName;
         
 }

 
 function switchStatus(info) {
     var music;
     if (SONGS.hasOwnProperty(info.singer)) {
         music = SONGS[info.singer].music;
         for (var i in music) {
             if (music[i].song === info.song)
                 music[i].enabled = !music[i].enabled;
         }
     }
 }
    



