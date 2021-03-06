<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
         private static $songsCount = 0;
         
         const AUDIO = 'http://vk.com/audio';
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
                    case 'search': {
                        $data = $this->parseUrl(self::AUDIO, urlencode($_POST['query']));                     
                        break;
                    }
                    default: {
                        throw new CHttpException(400,'неверный тип загружаемых данных');
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
        private function parseUrl() {
            $args = func_get_args();
            if (empty($args))
                throw new CHttpException(400, 'parseUrl got no arguments...');
            // get remixsid value
            $sid = $this->RemixSid();
            // get scrolled page
            $command = 'phantomjs '.$_SERVER['DOCUMENT_ROOT'].'/js/phantom.vk.js' . " $sid " . implode(' ', $args);
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

           $options = new XMLOptions();
           foreach ($data as $singer => $items) {
               // try to get real name from XML
               $name = $options->getNameByAlias($singer);
               // if it isset merge array of songs
               if ($name && isset($struct[$name])) {
                    $struct[$name]->music = array_merge($struct[$name]->music,$items);
                    continue;
               }
               $name = $name ? $name : $singer;
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
                $sid = trim($_COOKIE['remixsid']);
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