<?php

class SiteRepository implements Repository
{
    use SingletonTrait;

    public function getById($id)
    {
        return new Site($id, Faker\Factory::create()->url);
    }
}
