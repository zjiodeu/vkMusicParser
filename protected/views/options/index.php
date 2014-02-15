<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/options.js');?>
<div class="wrapper">
 <div class="form widget">
    <h1>Добавить алиасы</h1>
 <?php $this->breadcrumbs=array(
	'Алиасы',
        );
 ?>
    <?php $form = $this->beginWidget('CActiveForm');
    echo $form->errorSummary($model);
    ?>
    <div class="row">
    <?php
    
    echo $form->label($model,'singer');
    
    echo $form->textField($model, 'singer');
    //die(get_class($model).var_dump($model,1));
    ?>
    </div>
   <div class="row">
  <?php
    echo $form->label($model,'alias');
    echo $form->textField($model,'alias[]');
     echo CHtml::Link('Добавить', '#', array('id' => 'addAlias'));
     echo CHtml::Link('Удалить', '#', array('id' => 'removeAlias'));
    ?> 
    </div>
     <div class="row submit"> 
   <?php echo CHtml::submitButton('Сохранить'); ?>
    </div>
  <?php $this->endWidget();?>
 </div>
  <?php
   $this->renderPartial('bands', array('model' => $model));
  ?>
    
</div>

