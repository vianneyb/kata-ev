<?php

class Destination
{
    public $id;
    public $countryName;
    public $conjunction;
    public $name;
    public $computerName;

    public function __construct($id, $countryName)
    {
        $this->id = $id;
        $this->countryName = $countryName;
        $this->conjunction = Faker\Factory::create()->text(2);
        $this->name = $countryName;
        $this->computerName = Faker\Factory::create()->slug;
    }
}
