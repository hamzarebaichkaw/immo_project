<?php

/**
 * Created by PhpStorm.
 * User: chkaw
 * Date: 30/11/2016
 * Time: 15:18
 */
class Calander

{
     private $jour;
     private $semaine;
     private $mois;
     private $anne;


    function  __construct($jour,$semaine,$mois,$anne)
  {

      $this->jour=$jour;
      $this->semaine=$semaine;
      $this->mois=$mois;
      $this->anne=$anne;


  }

    /**
     * @return mixed
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * @return mixed
     */
    public function getAnne()
    {
        return $this->anne;
    }

    /**
     * @return mixed
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * @return mixed
     */
    public function getSemaine()
    {
        return $this->semaine;
    }

}