<?php

class TemplateManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Init the mocks
     */
    public function setUp()
    {
    }

    /**
     * Closes the mocks
     */
    public function tearDown()
    {
    }

    public function test_it_tests_destination_name_injection()
    {
        $faker = \Faker\Factory::create();

        $quote = new \kata\Entity\Quote(
            $faker->randomNumber(),
            $faker->randomNumber(),
            $faker->randomNumber(),
            $faker->date()
        );
        $expectedDestination = \kata\Repository\DestinationRepository::getInstance()->getById(
            $faker->randomNumber()
        );
        $template = new \kata\Entity\Template(
            1,
            'desti [quote:destination_name]',
            "desti [quote:destination_name]"
        );
        $templateManager = new \kata\TemplateManager();
        $message = $templateManager->getTemplateComputed(
            $template,
            ['quote' => $quote]
        );
        $this->assertEquals('desti ' . $expectedDestination->countryName, $message->subject);
        $this->assertEquals('desti ' . $expectedDestination->countryName, $message->content);
    }

    /**
     * @test
     */
    public function test()
    {
        $faker = \Faker\Factory::create();

        $expectedDestination = \kata\Repository\DestinationRepository::getInstance()->getById(
            $faker->randomNumber()
        );
        $expectedUser = \kata\Context\ApplicationContext::getInstance()->getCurrentUser();

        $quote = new \kata\Entity\Quote(
            $faker->randomNumber(),
            $faker->randomNumber(),
            $faker->randomNumber(),
            $faker->date()
        );

        $template = new \kata\Entity\Template(
            1,
            'Votre voyage avec une agence locale [quote:destination_name]',
            "
Bonjour [user:first_name],

Merci d'avoir contacté un agent local pour votre voyage [quote:destination_name].

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
"
        );
        $templateManager = new \kata\TemplateManager();

        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote,
            ]
        );

        $this->assertEquals(
            'Votre voyage avec une agence locale ' . $expectedDestination->countryName,
            $message->subject
        );
        $this->assertEquals(
            "
Bonjour " . $expectedUser->firstname . ",

Merci d'avoir contacté un agent local pour votre voyage " . $expectedDestination->countryName . ".

Bien cordialement,

L'équipe Evaneos.com
www.evaneos.com
",
            $message->content
        );
    }
}
