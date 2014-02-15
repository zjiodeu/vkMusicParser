<div class="form">
    <?php
    echo CHtml::beginForm($this->createUrl('site/download'), 'post', array('enctype' => 'multipart/form-data'));
    echo CHtml::fileField('target');
    echo CHtml::hiddenField('type','file');
    echo CHtml::SubmitButton('Скачать');
    echo CHtml::endForm();
    
?>

</div>