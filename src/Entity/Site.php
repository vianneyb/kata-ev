<?php

namespace kata\Entity;

class Site
{
    public $id;
    public $url;

    public function __construct($id, $url)
    {
        $this->id = $id;
        $this->url = $url;
    }
}
