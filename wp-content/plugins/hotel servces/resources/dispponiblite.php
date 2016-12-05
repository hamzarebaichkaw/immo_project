<?php
 
/**
* 
*/
class Dispponibilte

{

	private $id;
	private $id_apportement;
	private $startDate;
	private $endDate;
	private $type;
	
	function __construct($id,$id_apportement,$startDate,$endDate,$type)
	{
		$this->id=$id;
		$this->id_apportement=$id_apportement;
		$this->startDate=$startDate;
		$this->endDate=$endDate;
		$this->type=$type;
	}
   

    function getId(){

    	return $this->id;
    }
    function getId_apportement(){
    	return $this->id_apportement;
    }
    function getStartDate(){
    	return $this->startDate;
    }
    function getEnd(){
    	return $this->endDate;
    }
    function getType(){
    	return $this->type;
    }
 

}


?>