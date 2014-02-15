<div class="form">
    <?php
    echo CHtml::beginForm($this->createUrl('site/download'));
    echo CHtml::textField('target','http://vk.com/audios7470140?act=recommendations');
    echo CHtml::hiddenField('type','link');
    echo CHtml::SubmitButton('Скачать');
    echo CHtml::endForm();
    
?>
    </div>

