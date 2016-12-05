<?php

class Quote
{
    public $id;
    public $siteId;
    public $destinationId;

    public function __construct($id, $siteId, $destinationId)
    {
        $this->id = $id;
        $this->siteId = $siteId;
        $this->destinationId = $destinationId;
        $this->dateQuoted = rand(0, 1) ? date('Y-m-d H:i:s') : new DateTime();
    }

    public static function renderHtml(Quote $quote)
    {
        // some logic here
        return Faker\Factory::create()->text(2000);
    }

    public static function renderText(Quote $quote)
    {
        // some other logic here
        return Faker\Factory::create()->text(2000);
    }
}