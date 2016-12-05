<?php

class DestinationRepository implements Repository
{
    use SingletonTrait;

    public function getById($id)
    {
        // some logic here
        return new Destination($id, Faker\Factory::create()->country);
    }
}
