<?php

class downloadType extends CWidget {
    
    public $view;
    
    public $url = '';
    
    public function init() {
        if (empty($this->view))
            throw new CHttpException(400, 'Don`t forget to add the view name');
        $this->render($this->view);
    }
 
    public function run() {
        
    }
}
