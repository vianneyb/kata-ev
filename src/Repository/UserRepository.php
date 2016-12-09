<?php

class UserRepository implements Repository
{
    use SingletonTrait;

    public function getById($id)
    {
        // DO NOT MODIFY THIS METHOD
        $faker = Faker\Factory::create();
        return new User($id, $faker->firstName, $faker->lastName);
    }
}
