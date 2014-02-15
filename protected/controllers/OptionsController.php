<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OptionsController
 *
 * @author zjiodeu
 */
class OptionsController extends Controller {
    
    public function actionIndex() {
        
        $model = new XMLOptions();
        if (isset($_POST['XMLOptions'])) {
            $model->attributes = $_POST['XMLOptions'];
            if ($model->validate()) {
                $model->save();
            }
        }
            
        $this->render('index', array('model' => $model));
        
    }
    
}
