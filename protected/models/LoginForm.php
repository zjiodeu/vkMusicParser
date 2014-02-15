<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $remixSid;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('remixSid', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
                        'remixSid' => 'remixSid',
			'rememberMe'=>'Запомни меня, чудо-программа',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate()
	{
		if(!$this->hasErrors())
		{
                    $curl = curl_init();
                    $options = array(CURLOPT_URL => 'http://vk.com',
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_FOLLOWLOCATION => false,
                            CURLOPT_HEADER => true,  
                            CURLOPT_COOKIE => 'remixsid='.$this->remixSid);
                    curl_setopt_array($curl, $options);
                    $response = curl_exec($curl);
                    curl_close($curl);
			die(var_dump($response));
			$this->addError('remixId','Кука не подходит, проверьте еще разок');
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
            $this->authenticate();
        }
	/*	if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}*/
}
