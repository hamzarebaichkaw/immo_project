<?php
/**
* 
*/
class Services 
{
    private $id;
    private $prix;
    privte $description; 	
	function __construct($id ,$prix, $description)
	{
		$this->id=$id;
		$this->prix=$prix;
		$this->description=$description;
	}



	public function getid_services(){
		return $this->id;
	}
	public function getprix_services()
	{
		return $this->prix;

	}
	public function getdescription_services(){
		return $this->description;
	}
}

?>