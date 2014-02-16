<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/download.js');?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/custom.js');?>
<div class="wrapper">
    <h1>Тип закачки: </h1>
   <?php $this->breadcrumbs=array(
	'Скачать',
);
   ?>
    <br />
    <div class="type widget" id="byLink">
            <span>Для работы нужно указать свой remixsid</span>
        <?php $this->renderPartial('byLink', array('songs' => $songs)); ?>
    </div>
    <div class="type widget" id="byFile">
            <span>Количество песен зависит от загружаемого <i>.html</i> файла</span>
         <?php $this->renderPartial('byFile', array('songs' => $songs)); ?>
    </div>
        <?php
       //die(print_r($songs));
    if (!empty($songs)): 
        
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id'=>'mydialog',
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'Будут скачаны следующие песни (' . count($songs) . ' исполнителей, '.$count.' песен)',
        'autoOpen'=>1,
        'width' => 500,
        // on Open create global object SONGS which contains VK data (singer, song, download link, options)
        'open' => 'js:function() {'
        . 'SONGS = JSON.parse(decodeURIComponent("'.urlencode(json_encode($songs)).'"));'
        . 'console.log(SONGS);'
        . '}',
    ),
     
));
    $html = '<a href="javascript:void(0)" id="switchAll" class="optlink" >Выбрать/отменить всё</a>';
    foreach ($songs as $singer => $obj):
        $html .= '<div class="block"><p class="singer">'.CHtml::checkbox('chsinger',true)
                    .'<a href="#"><b>'.$singer.'</b></a></p>';
        $i = 1;
        foreach ($obj->music as $composition) {
            $html .= '<span>'.CHtml::checkbox('chsong',true).$i.') '
                    .$composition['song'].'</span><br />';
            ++$i;
        }
        $html .= '</div><hr />';
    endforeach;
    $html .= '<input type="button" id="saveMusic" value="Скачать" />';
    echo $html;
$this->endWidget('zii.widgets.jui.CJuiDialog');
 
    endif;
    ?>
</div>
