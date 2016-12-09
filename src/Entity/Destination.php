<?php

class Destination
{
    public $id;
    public $countryName;
    public $conjunction;
    public $name;
    public $computerName;

    public function __construct($id, $countryName, $conjuction, $computerName)
    {
        $this->id = $id;
        $this->countryName = $countryName;
        $this->conjunction = $conjuction;
        $this->computerName = $computerName;
    }
}
