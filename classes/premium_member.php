<?php
/**
 * Created in PhpStorm
 * @author Brian Kiehn
 * @date 2/14/2020
 * @version 2.0
 * premium_member.php
 * GreenRiverDev
 * @link https://github.com/medioxumate/dating4.git
 */

class premium_member extends member
{
    private $_indoorInterests;
    private $_outdoorInterests;

    /**
     * premium_member constructor.
     * @param $fname - first name
     * @param $lname - last name
     * @param $age - age
     * @param $phone - phone number
     * @param string $gender - gender
     * @param array $_indoorInterests - array of indoor interests
     * @param array $_outdoorInterests - array of outdoor interests
     */
    public function __construct($fname, $lname, $age, $phone, $gender  = "Not Given", $_indoorInterests = array(),
                                $_outdoorInterests = array())
    {
        parent::__construct($fname, $lname, $age, $phone, $gender);
        $this->_indoorInterests = $_indoorInterests;
        $this->_outdoorInterests = $_outdoorInterests;
    }

    //getters
    /**
     * @return array
     */
    public function getIndoorInterests(){
        return $this->_indoorInterests;
    }

    /**
     * @return array
     */
    public function getOutdoorInterests(){
        return $this->_outdoorInterests;
    }

    //setters
    /**
     * @param array $indoorInterests
     */
    public function setIndoorInterests($indoorInterests){
        $this->_indoorInterests = $indoorInterests;
    }

    /**
     * @param array $outdoorInterests
     */
    public function setOutdoorInterests($outdoorInterests){
        $this->_outdoorInterests = $outdoorInterests;
    }


    //Helper methods
    public function hobbyToString(array $interests){
        $string ='';

        foreach ($interests as $hobby){
            $string .= $hobby;
            $string .= ', ';
        }

        $length = strlen($string);
        $string = substr($string, 0, $length-2);

        return $string;
    }

    public function addInElement($element){
        $indoor = $this->getIndoorInterests();
        array_push($indoor, $element);
        $this->setIndoorInterests($indoor);
    }

    public function addOutElement($element){
        $outdoor = $this->getOutdoorInterests();
        array_push($outdoor, $element);
        $this->setOutdoorInterests($outdoor);
    }
}