<?php

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        $APPLICATION_CONTEXT = ApplicationContext::getInstance();

        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;

        if ($quote)
        {
            $_quoteFromRepository = QuoteRepository::getInstance()->getById($quote->id);
            $usefulObject = SiteRepository::getInstance()->getById($quote->siteId);
            $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->destinationId);

            if(strpos($text, '[quote:destination_link]') !== false){
                $destination = DestinationRepository::getInstance()->getById($quote->destinationId);

                if ($destination)
                    $text = str_replace('[quote:destination_link]', $usefulObject->url . '/' . $destination->countryName . '/quote/' . $_quoteFromRepository->id, $text);
                else
                    $text = str_replace('[quote:destination_link]', '', $text);
            }

            $url = $usefulObject->url . '/' . $destinationOfQuote->computerName;

            if (strpos($text, '[quote:form]') !== false) {
                $url = $APPLICATION_CONTEXT->getCurrentSite()->url . '/' . $_quoteFromRepository->id . '/note/';
                $baseText = <<<STR
<table align="center" style="font-family:Arialna, Arial;" cellspacing="10">
  <tr>
    <td align="right">
        <span><strong>bad</strong></span>
    </td>
    <td style="background-color:#d4410e">
      <a href="{$url}/1" style="color: #fff; text-decoration: none;">
        <div align="center" style="width:40px"><span style="font-size: 2em">1</span></div>
      </a>
    <td align="left">
        <span><strong>good</strong></span>
    </td>
</table>
STR;
                $formText = str_replace("\n", '', $baseText);
                $text = str_replace('[quote:form]', $formText, $text);
            }

            $containsSummaryHtml = strpos($text, '[quote:summary_html]');
            $containsSummary     = strpos($text, '[quote:summary]');

            if ($containsSummaryHtml !== false || $containsSummary !== false) {
                if ($containsSummaryHtml !== false) {
                    $text = str_replace(
                        '[quote:summary_html]',
                        Quote::renderHtml($_quoteFromRepository),
                        $text
                    );
                }
                if ($containsSummary !== false) {
                    $text = str_replace(
                        '[quote:summary]',
                        Quote::renderText($_quoteFromRepository),
                        $text
                    );
                }
            }

            if($quote && $quote->dateQuoted && is_string($quote->dateQuoted)) {
                $date = new DateTime($quote->dateQuoted);
            } else if($quote->dateQuoted instanceof DateTime) {
                $date = $quote->dateQuoted;
            } else {
                $date = new DateTime();
            }

            $dateQuoted = $date->format('m-Y');

            (strpos($text, '[quote:date]') !== false)                 and $text = str_replace('[quote:date]'                 ,"".$dateQuoted."",$text);
            (strpos($text, '[quote:destination_name]') !== false)         and $text = str_replace('[quote:destination_name]'         ,$destinationOfQuote->countryName,$text);
            (strpos($text, '[quote:destination_link]') !== false)         and $text = str_replace('[quote:destination_link]'         , $url, $text);
        }

        /*
         * SITE
         * [site:*]
         */
        $text = str_replace('[site:url]', $APPLICATION_CONTEXT->getCurrentSite()->url, $text);
        $text = str_replace('[site:id]', $APPLICATION_CONTEXT->getCurrentSite()->id, $text);

        if ($_quoteFromRepository) {
            $text = str_replace('[quote:id]', $_quoteFromRepository->id, $text);
            $text = str_replace('[quote:site_id]', $_quoteFromRepository->siteId, $text);
        } else {
            $text = str_replace('[quote:id]', '', $text);
            $text = str_replace('[quote:site_id]', '', $text);
        }

        /*
         * USER
         * [user:*]
         */
        $_user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();
        if($_user) {
            if(isset($_user->firstname)){ $text = str_replace('[Logged:last_name]',ucfirst(mb_strtolower($_user->firstname)),$text);}
            if(isset($_user->lastname)){ $text = str_replace('[Logged:first_name]',ucfirst(mb_strtolower($_user->lastname)),$text);}
            (strpos($text, '[user:first_name]') !== false) and $text = str_replace('[user:first_name]'       , ucfirst(mb_strtolower($_user->firstname)), $text);

            $token = base64_encode($_user->id . $_user->email);
            $registerUrl = $APPLICATION_CONTEXT->getCurrentSite()->url . '/club-evaneos/inscription/?cToken=' . $token;
            (strpos($text, '[traveller:quote_url]') !== false) and $text = str_replace('[traveller:quote_url]', '<a href="'.$registerUrl.'">' . $registerUrl . '</a>', $text);
        }

        return $text;
    }
}