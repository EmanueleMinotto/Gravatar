<?php

namespace EmanueleMinotto\Gravatar;

use GuzzleHttp\Client as GuzzleHttp_Client;
use GuzzleHttp\ClientInterface as GuzzleHttp_ClientInterface;

/**
 * PHP library for gravatar.com
 *
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * @link http://www.gravatar.com/
 */
class Client
{
    /**
     * Guzzle HTTP client.
     *
     * @var \GuzzleHttp\ClientInterface
     */
    private $httpClient;

    /**
     * Class constructor, with an alternative Guzzle HTTP client (optional).
     *
     * @param \GuzzleHttp\ClientInterface|null $httpClient
     */
    public function __construct(GuzzleHttp_ClientInterface $httpClient = null)
    {
        $this->setHttpClient($httpClient ?: new GuzzleHttp_Client());
    }

    /**
     * HTTP client setter.
     *
     * @param \GuzzleHttp\ClientInterface $httpClient
     */
    public function setHttpClient(GuzzleHttp_ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * HTTP client getter.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Get user profile on gravatar.com
     *
     * @param string $email   User email.
     * @param string $format  Request format (default json).
     * @param array  $options Request options.
     *
     * @return string
     */
    public function getProfileUrl($email, $format = 'json', array $options = array())
    {
        $url = 'https://www.gravatar.com/';

        $url .= md5(strtolower($email)).'.';
        $url .= in_array($format, ['json', 'xml', 'php', 'vcf', 'qr']) ? $format : 'json';

        if ($options) {
            $url .= '?'.http_build_query($options);
        }

        return $url;
    }

    /**
     * Get user profile data.
     *
     * @param string $email User email.
     *
     * @return array
     */
    public function getProfile($email)
    {
        $url = $this->getProfileUrl($email);

        $data = $this->httpClient->get($url)->json();

        return $data['entry'][0];
    }

    /**
     * Get user avatar image URL.
     *
     * @param string  $email     User email.
     * @param integer $size      Image size (default 80).
     * @param string  $extension Image extension (default jpg).
     * @param integer $default   Error response (default 404).
     * @param string  $rating    Image rating (default G).
     *
     * @return string
     */
    public function getAvatarUrl($email, $size = 80, $extension = 'jpg', $default = 404, $rating = 'g')
    {
        $url = 'https://www.gravatar.com/avatar/';

        $url .= md5(strtolower($email)).'.'.$extension;

        $url .= '?'.http_build_query([
            'd' => $default,
            'r' => $rating,
            's' => $size,
        ]);

        return $url;
    }

    /**
     * Get user avatar image (as base 64 URI).
     *
     * @param string  $email     User email.
     * @param integer $size      Image size (default 80).
     * @param string  $extension Image extension (default jpg).
     * @param integer $default   Error response (default 404).
     * @param string  $rating    Image rating (default G).
     *
     * @return string
     */
    public function getAvatar($email, $size = 80, $extension = 'jpg', $default = 404, $rating = 'g')
    {
        $url = $this->getAvatarUrl($email, $size, $extension, $default, $rating);

        $response = $this->httpClient->get($url);

        $content = (string) $response->getBody();
        $mimeType = $response->getHeader('content-type');

        return 'data:'.$mimeType.';base64,'.base64_encode($content);
    }
}
