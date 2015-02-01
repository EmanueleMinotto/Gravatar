<?php

namespace EmanueleMinotto\Gravatar\Tests\Twig;

use EmanueleMinotto\Gravatar\Twig\GravatarExtension;
use Twig_Test_IntegrationTestCase;

/**
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * @coversDefaultClass \EmanueleMinotto\Gravatar\Twig\GravatarExtension
 */
class GravatarExtensionTest extends Twig_Test_IntegrationTestCase
{
    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return array(
            new GravatarExtension(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFixturesDir()
    {
        return dirname(__FILE__).'/Fixtures/';
    }
}
