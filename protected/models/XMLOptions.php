<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of XMLOptions
 *
 * @author Mikhail K
 */
class XMLOptions extends CFormModel {
    
    public $singer;
    public $alias;
    
   public function rules() {
       return array(
           array('singer', 'required'),
           array('alias','type','type'=>'array','allowEmpty' => false)
       );
   }
   
   public function attributeLabels()
	{
	return array(
			'band'=>'Название группы, исполнителя',
                        'alias'=>'Алиас',
		);
	}
    
        // TODO rewrite with DOM api!
        
    public function save() {
        $file = $this->destination();
        if (!is_file($file)) {
            file_put_contents($file,'<?xml version= "1.0"?><options></options>');
        }
       $xml = simplexml_load_file($file);       
       if (FALSE === ($singer = $this->addSinger($xml)) )
            throw new CHttpException(404,'ощибка парсинга хмл');

       foreach ($this->alias as $val) {
            if (!empty($val))
                $singer->addChild('alias',strtolower($val));
       }
       $xml->asXML($file);
    }
    
    public function load() {
        $file = $this->destination();
        if (!is_file($file)) {
            return false;
        }
        $xml = simplexml_load_file($file);
        return $xml;
    }
    
    public function getNameByAlias($alias) {
        $xml = $this->load();
        $name = mb_strtolower($alias, 'UTF-8');
        foreach ($xml->children() as $singer) {
            for ($i=0, $len = count($singer->alias); $i < $len; ++$i) {
                if ($name == $singer->alias[$i]) {
                    $name = (string)$singer->attributes()->name;
                    break 2;
                }
            }
        }
        return $name;
    }
    
    private function addSinger($xml) {
        //die(var_sump($xml));
        foreach ($xml->children() as $item) {
            if ($item['name'] == strtolower($this->singer)) {
                $singer = $item;
                break;
            }
        }
        //echo $singer;
        if (!isset($singer)) {
            $singer = $xml->addChild('singer');
            $singer->addAttribute('name',strtolower($this->singer));
        }
        return $singer;
    }
 
    private function destination() {
        return $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'options.xml';
    }
    
}
