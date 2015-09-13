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
     * Tested extension and required dependencies for functional tests.
     *
     * @return array
     */
    public function getExtensions()
    {
        return [
            new GravatarExtension(),
        ];
    }

    /**
     * Fixtures directory.
     *
     * @return string
     */
    public function getFixturesDir()
    {
        return dirname(__FILE__).'/Fixtures/';
    }
}
