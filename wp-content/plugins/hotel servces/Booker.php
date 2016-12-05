<?php
/**
* 
*/
class Booker
{    
	
	private $login;
	private $password;
	private $type;
	
	function __construct($login,$password,$type)
	{
		
		$this->login=$login;
		$this->password=$password;
		$this->type=$type;
		
	}
	
	function getLogin(){
		return $this->login;
	}
	function getPassword(){
		return $this->password;
	}
	function getType(){
		return $this->type;
	}
}

?>