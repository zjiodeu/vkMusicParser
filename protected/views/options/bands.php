<div class="bands widget">
    <ul>
        <?php $list = $model->load();
         if (is_object($list) && method_exists($list,'children')) :
        ?>
        <?php foreach ($list->children() as $item): ?>
        
        <li><a href="#">
            <?php
                echo $item['name'];
                unset($item['name']);
            ?></a>
            <ol class="songs">
                <?php foreach ($item as $alias) : ?>
                <li><?php echo $alias; ?></li>                        
            <?php endforeach; ?>
            </ol>
        </li>
        
        <?php endforeach;?>
       <?php endif; ?>
    </ul>
</div>


