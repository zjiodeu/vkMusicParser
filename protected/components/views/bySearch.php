<div class="type widget">
            <span>Скачать песни по ключевому слову</span>
<div class="form">
    <?php
    echo CHtml::beginForm($this->url, 'post');
    echo CHtml::textField('query');
    echo CHtml::hiddenField('type','search');
    echo CHtml::SubmitButton('Скачать');
    echo CHtml::endForm();
    
?>
</div>
    </div>