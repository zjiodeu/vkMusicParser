<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
         private static $songsCount = 0;
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{   
            $inDir = isset($_GET['directory']);
            
                $dir = ($inDir)?urldecode($_GET['directory']):'';
            $folder = new Folder();
            $list = $folder->dirList($dir);
            //die(print_r($list));
            $this->render('index',array('folder' => $list['struct'], 'destination' =>$list['destination'], 
                                        'inDir' => $inDir));
	}

        public function actionDownload() 
        {   
            $data = '';
        // TODO: include html simple dom
$audioRegExp = '/<input[\s\S]+?id=["\']audio_info[_0-9]+["\'][\s\S]+?value=[\'"](.+?)[\'"][\s\S]+?<b>\s*<a.*?>(.+?)<\/a>[\s\S]+?<span\sclass=["\']title[\'"]>\s*?(?:<a.*?>)?(.+?)</i';
            $songs = array();
            
            if (isset($_POST['type'])) {
                switch ($_POST['type']) {
                    case 'link': {
                        $data = $this->parseUrl($_POST['target']);
                        break;
                    }
                    case 'file': {
                        if ($_FILES && $_FILES['target']) {
                            $file = $_FILES['target']['tmp_name'];
                            if (!empty($file))
                                $data = file_get_contents($file);
                        }                           
                        break;
                    }
                    default: {
                        throw new CHttpException(404,'неверный тип загружаемых данных');
                        break;
                    }
                }
                preg_match_all($audioRegExp, $data, $parse);
                $songs = self::serializeVkSongs($parse);
                
            }

           $this->render('download', array('songs' => $songs, 'count' => self::$songsCount)); 
            
        }
        
        // get ajax request from modal dialog
        public function actionAccept() {
            if (!isset($_POST) || !isset($_POST['songs']))
                return false;
            $songs = json_decode($_POST['songs'], 1);
            //die(var_dump($songs));
            $folder = new Folder();
            return $folder->saveMusic($songs);
        }
        
        // return vk page content
        private function parseUrl($url) {
            $sid = $this->RemixSid();
            $command = 'phantomjs '.$_SERVER['DOCUMENT_ROOT'].'/js/phantom.vk.js' . " $sid $url";
            $response = shell_exec($command);
            return $response;
        }
        private static function serializeVkSongs($arr) {

            $data = array(); //data format is one singer to a lot of [song , download link ,enabled (it will be downloaded)]
            $struct = array(); // contains array of Singer objects
            if (!is_array($arr)) {
                throw new CHttpException(400 ,'error when serialize');
            }

           self::$songsCount = count($arr[1]);
           
           for ($i=0; $i < self::$songsCount; ++$i) {
               $arr[2][$i] = Folder::cleaning($arr[2][$i]);
               $arr[3][$i] = Folder::cleaning($arr[3][$i]);
               $item = array('song' => $arr[3][$i],
                             'link' => $arr[1][$i],
                             'enabled' => true);
               $data[$arr[2][$i]][] = $item;
           }
           /*
            * class Singer defined in Components
            */
           $options = new XMLOptions();
           foreach ($data as $singer => $items) {
               // try to get real name from XML
               $name = $options->getNameByAlias($singer);
               // if it isset merge array of songs
               if (isset($struct[$name])) {
                    $struct[$name]->music = array_merge($struct[$name]->music,$items);
                    continue;
               }
               $obj = new stdClass();
               $obj->name = $name;
               $obj->music = $items;
               $obj->enabled = true;
               $struct[$name] = $obj;
           }
            return $struct;
        }
        
        private function RemixSid() {
            if (isset($_COOKIE['remixsid']) && !empty($_COOKIE['remixsid'])) {
                $sid = trim(strip_tags($_COOKIE['remixsid']));
                return $sid;
            }
            else
                throw new CHttpException(400 ,'remixsid doesn`t set'); 
        }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}


}