<?php

class DestinationRepository implements Repository
{
    use SingletonTrait;

    public function getById($id)
    {
        // DO NOT MODIFY THIS METHOD
        return new Destination(
            $id,
            Faker\Factory::create()->country,
            Faker\Factory::create()->text(2),
            Faker\Factory::create()->slug()
        );
    }
}
