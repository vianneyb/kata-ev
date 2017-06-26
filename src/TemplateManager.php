<?php

namespace kata;

use kata\Context\ApplicationContext;
use kata\Entity\Quote;
use kata\Entity\Template;
use kata\Repository\DestinationRepository;
use kata\Repository\QuoteRepository;
use kata\Repository\SiteRepository;

class TemplateManager
{

    private $quoteRepository;

    private $siteRepository;

    private $destinationRepository;

    private $appContext;

    public function __construct()
    {
        $this->quoteRepository = QuoteRepository::getInstance();
        $this->siteRepository = SiteRepository::getInstance();
        $this->destinationRepository = DestinationRepository::getInstance();
        $this->appContext = ApplicationContext::getInstance();
    }

    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);

        $user = (isset($data['user']) and ($data['user'] instanceof User)) ?
            $data['user'] : $this->appContext->getCurrentUser();

        /** @var  $placeHolderInjectors */
        $placeHolderInjectors = [];
        $placeHolderInjectors[] = new \kata\Injectors\User($user);


        if (isset($data['quote']) and $data['quote'] instanceof Quote) {
            $quote = $this->quoteRepository->getById($data['quote']->id);
            $site = $this->siteRepository->getById($quote->siteId);
            $destination = $this->destinationRepository->getById($quote->destinationId);
            $placeHolderInjectors[] = new \kata\Injectors\Quote($quote, $site, $destination);

        }



        foreach ($placeHolderInjectors as $injector) {
            $replaced->subject = $injector->inject($replaced->subject);
            $replaced->content = $injector->inject($replaced->content);
        }

        return $replaced;
    }

}
