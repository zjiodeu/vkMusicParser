<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Download
 *
 * @author zjiodeu
 */
class Upload extends CFormModel
{
    public $upload_file;

    public function rules()
    {
        return array(
        array('upload_file', 'file', 'types'=>'html,htm','maxSize'=>100*1024*1024),
        );
    }


}
