<?php

class QuoteRepository implements Repository
{
    use SingletonTrait;

    public function getById($id)
    {
        $generator = Faker\Factory::create();

        return new Quote($id, $generator->numberBetween(1, 10), $generator->numberBetween(1, 200));
    }
}
