<?php
/* @var $this SiteController */
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/custom.js');
$this->pageTitle=Yii::app()->name;
?>
   <?php 
   if (isset($_GET['directory'])) {
   $this->breadcrumbs=array(
	$_GET['directory'],
    );
   }
   ?>
<hr />
<div class='browse'>
<?php
if ($inDir) {
    $class = 'file';
    echo '<a class="file" href="#" onClick="location = \'http://\'+document.domain">..</a>';
}
$html = '';
if (!$folder) {
    echo 'папка пуста';
}
else {
    foreach ($folder as $key => $type) :
        if ($type === 'folder') {
            $html .= "<div class='$type'><span>$key</span></div>";
        }
        elseif ($type === 'file') {
            $path = 'music'.DIRECTORY_SEPARATOR.$destination.DIRECTORY_SEPARATOR.$key;
            $html .= "<div class='block' ><h5>$destination - $key</h5>";
            $html .= "<audio src='$path' type='audio/mpeg' controls>Осла выключи, дружище</audio></div>";
        }
      
    endforeach;   
}
echo $html;

?>
</div>

