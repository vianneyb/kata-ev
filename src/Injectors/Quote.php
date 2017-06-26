<?php

namespace kata\Injectors;

use kata\Entity\Destination;
use kata\Entity\Site;

/**
 * User: vianney-evaneos
 * Date: 23/06/2017
 * Time: 19:20
 */
class Quote implements Injector
{

    /**
     * @var \User
     */
    private $user;

    /**
     * @var \kata\Entity\Quote
     */
    private $quote;

    /**
     * @var Site
     */
    private $site;

    /**
     * @var Destination
     */
    private $destination;

    public function __construct(\kata\Entity\Quote $quote, Site $site, Destination $destination)
    {

        $this->quote = $quote;
        $this->site = $site;
        $this->destination = $destination;
    }

    public function inject($text)
    {
        $text = str_replace(
            '[quote:summary_html]',
            \kata\Entity\Quote::renderHtml($this->quote),
            $text
        );
        $text = str_replace(
            '[quote:summary]',
            \kata\Entity\Quote::renderText($this->quote),
            $text
        );
        $text = str_replace(
            '[quote:destination_name]',
            $this->destination->countryName,
            $text
        );

        $destinationLink = $this->site->url . '/' . $this->destination->countryName . '/quote/' . $this->quote->id;
        $text = str_replace('[quote:destination_link]', $destinationLink, $text);

        return $text;
    }

}