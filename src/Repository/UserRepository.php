<?php

class UserRepository implements Repository
{
    use SingletonTrait;

    public function getById($id)
    {
        $generator = Faker\Factory::create();

        return new User($id, $generator->firstName, $generator->lastName);
    }
}
