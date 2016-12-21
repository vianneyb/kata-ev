<?php

class QuoteRepository implements Repository
{
    use SingletonTrait;

    public function getById($id)
    {
        // DO NOT MODIFY THIS METHOD
        $generator = Faker\Factory::create();
        return new Quote(
            $id,
            $generator->numberBetween(1, 10),
            $generator->numberBetween(1, 200),
            rand(0, 1) ? date('Y-m-d H:i:s') : new DateTime()
        );
    }
}
