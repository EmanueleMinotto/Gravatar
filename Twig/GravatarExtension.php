<?php

namespace EmanueleMinotto\Gravatar\Twig;

use EmanueleMinotto\Gravatar\Client;
use GuzzleHttp\Exception\ClientException;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;
use Twig_SimpleTest;

/**
 * Twig extension for gravatar.com APIs.
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 */
class GravatarExtension extends Twig_Extension
{
    /**
     * Gravatar library client.
     *
     * @var Client
     */
    private $client;

    /**
     * Class constructor with optional client.
     *
     * @param Client|null $client Gravatar library client.
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('gravatar_profile_url', function ($email) {
                try {
                    $profile = $this->client->getProfile($email);
                } catch (ClientException $exception) {
                    return;
                }

                return $profile['profileUrl'];
            }),
            new Twig_SimpleFilter('gravatar_url', [$this->client, 'getAvatarUrl']),
        ];
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('gravatar', [$this->client, 'getAvatar']),
            new Twig_SimpleFunction('gravatar_profile', [$this->client, 'getProfile']),
            new Twig_SimpleFunction('gravatar_url', [$this->client, 'getAvatarUrl']),
        ];
    }

    /**
     * Returns a list of tests to add to the existing list.
     *
     * @return array An array of tests
     */
    public function getTests()
    {
        return [
            new Twig_SimpleTest('gravatar', [$this->client, 'exists']),
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'emanueleminotto_gravatar_twigextension';
    }
}
