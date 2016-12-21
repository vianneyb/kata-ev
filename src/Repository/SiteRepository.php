<?php

class SiteRepository implements Repository
{
    use SingletonTrait;

    public function getById($id)
    {
        // DO NOT MODIFY THIS METHOD
        return new Site($id, Faker\Factory::create()->url);
    }
}
