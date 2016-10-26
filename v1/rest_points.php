<?php

/**
 * Rest object example
 * 
 * GNU General Public License (Version 2, June 1991) 
 * 
 * This program is free software; you can redistribute 
 * it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free 
 * Software Foundation; either version 2 of the License, 
 * or (at your option) any later version. 
 * 
 * This program is distributed in the hope that it will 
 * be useful, but WITHOUT ANY WARRANTY; without even the 
 * implied warranty of MERCHANTABILITY or FITNESS FOR A 
 * PARTICULAR PURPOSE. See the GNU General Public License 
 * for more details. 
 *
 * @author Rafał Przetakowski <rafal.p@beeflow.co.uk>
 */
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
	
	/**
	 * Example of an Endpoint
	 * @return array
	 */
	public function example() {
		$this->id = 1111;
		$this->name = 'John';
		$this->lastName = 'Doe';
		$this->login = 'Test';
		$this->response = $this->getMyVars();
		return $this->getResponse();
	}

	/**
	 * 
	 * @param integer $id
	 * @return array
	 */
	public function get($id) {
		$logged = $this->haveToBeLogged();
		if (true !== $logged) {
			return $logged;
		}
		
		if (!$this->isMethodCorrect('GET')) {
			return $this->getResponse(405);
		}
		
		$this->setIdFromRequest($id);
		$this->response = $this->getMyVars();
		return $this->getResponse();
	}
	
}
