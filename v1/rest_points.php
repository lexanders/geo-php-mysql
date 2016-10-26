<?php

class points extends restObject {

	/**
	 * user data
	 */
	public $id;
	public $lat;
	public $lon;
	public $text;

	/**
	 * 
	 * @param string $method
	 * @param array $request
	 * @param string $file
	 */
	public function __construct($method, $request = null, $file = null) {
		parent::__construct($method, $request, $file);
	}



	public function def(){
		global $db_connector;

		if($this->method=='POST'){
			$arr=$db_connector->arrayPrepare($_POST,array('point_lat','point_lon','point_text'));
			foreach($arr as $v){
				if (!$v) {
					$this->setError('Fields can not be empty.');
					return $this->getResponse(400);	
				}
			};
			$point_id=$db_connector->insert('points',$arr,true,'',true);
			if(!$point_id){
				$this->setError('DB error');
				return $this->getResponse(500);
			}
			//$this->response = $this->getMyVars(array('id'=>$point_id));
			$arr['id']=$point_id;
			$this->response = $this->getMyVars($arr);
			return $this->getResponse();		
		}
		
		if($this->method=='GET'){
			$arr=$db_connector->queryAssoc('SELECT * FROM points');
			$this->response = $this->getMyVars($arr);
			return $this->getResponse();
		}
		
	}

	
}
