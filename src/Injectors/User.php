<?php

namespace kata\Injectors;
/**
 * User: vianney-evaneos
 * Date: 23/06/2017
 * Time: 19:20
 */
class User implements Injector
{

    /**
     * @var \User
     */
    private $user;

    public function __construct(\kata\Entity\User $user)
    {
        $this->user = $user;
    }

    public function inject($text)
    {
        $text = str_replace(
            '[user:first_name]',
            ucfirst(mb_strtolower($this->user->firstname)),
            $text
        );

        return $text;
    }

}