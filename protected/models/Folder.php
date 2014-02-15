<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Folder
 *
 * @author Mikhail K
 */
class Folder extends CFormModel{
    
    public function dirList($folder='') {
        $dest = $this->destination().((!empty($folder))?DIRECTORY_SEPARATOR."$folder":'');
        if (!is_dir($dest) && empty($folder))
           mkdir($dest,0774);
        $list = scandir($dest);
        $struct = array();
        foreach ((array)$list as $val) { 
            if (preg_match('/^\.+/',$val)){
               unset($val);
               continue;
            }
            $name = $dest.DIRECTORY_SEPARATOR.$val;
            
            $struct[$val] = is_dir($name)?'folder':(is_file($name)?'file':false);
        }

        return (!empty($struct))?array('struct' => $struct, 
            'destination' => $folder):false;

    }
    
    
    private function destination() {
        return $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'music';
    }
    
    public function saveMusic($songs) {
        //exit();
         foreach ($songs as $singer => $album):
             /*
              * enabled arg contains checkbox value from modal dialog
              */
             if ($album['enabled'] === false)
                 continue;
             $folder = $this->destination().'/'.self::stripPluses($singer);
             if (!is_dir($folder))
                mkdir($folder,0774);
             foreach ($album['music'] as $composition) {
                if ($composition['enabled'] === false)
                    continue;
                $file = $folder.'/'.self::stripPluses($composition['song']).'.mp3';
                $link = $composition['link'];
                 if (!is_file($file)) {  
                    stream_copy_to_stream(fopen($link,'r'),fopen($file,'w'));
                 }
             }
         endforeach;
    return true;
    }
    
    
    public static function cleaning($name, $iconv = false) {
        /*if ($iconv)
            $name = iconv('windows-1251','utf-8',$str);*/
        $name = preg_replace('/[^-a-zа-яё0-9\s\.]/iu','',$name);
        $name = preg_replace('/^the\s/i','',$name);
        $name = mb_strtolower($name,'UTF-8');
       return trim($name);
    }
    
    // kostili suka  
    private static function stripPluses($str) {
       return preg_replace('/[\+\/]/',' ',$str); 
    }
    
    
    
}
