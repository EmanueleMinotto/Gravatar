<?php

namespace EmanueleMinotto\Gravatar\Tests;

use EmanueleMinotto\Gravatar\Client;
use GuzzleHttp\Client as GuzzleHttp_Client;
use PHPUnit_Framework_TestCase;

/**
 * @author Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * @coversDefaultClass \EmanueleMinotto\Gravatar\Client
 */
class ClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test constructor.
     * 
     * @covers ::__construct
     *
     * @return void
     */
    public function testConstruct()
    {
        $guzzle = new GuzzleHttp_Client();

        new Client($guzzle);
    }

    /**
     * Test accessors.
     * 
     * @covers ::getHttpClient
     * @covers ::setHttpClient
     *
     * @return void
     */
    public function testHttpClientAccessors()
    {
        $guzzle = new GuzzleHttp_Client();

        $client = new Client();
        $client->setHttpClient($guzzle);

        $this->assertSame($guzzle, $client->getHttpClient());
    }

    /**
     * Test profile URLs.
     *
     * @param string $email   User email.
     * @param string $format  Request format (default json).
     * @param array  $options Request options.
     * 
     * @covers ::getProfileUrl
     * @dataProvider getProfileUrlDataProvider
     *
     * @return void
     */
    public function testGetProfileUrl($email, $format = 'json', array $options = [])
    {
        $client = new Client();

        $url = $client->getProfileUrl($email, $format, $options);

        $regexp = '/^.+\/[a-zA-Z0-9]{32,32}\.(json|php|vcf|xml|qr)(\?.+)?/';
        $this->assertRegExp($regexp, $url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED));
        $this->assertStringStartsWith('https://www.gravatar.com/', $url);
    }

    /**
     * Example profiles.
     * 
     * @return array
     */
    public function getProfileUrlDataProvider()
    {
        return [
            ['beau.lebens@gmail.com', 'json', ['callback' => 'test']],
            ['beau.lebens@gmail.com', 'json'],
            ['beau.lebens@gmail.com', 'php'],
            ['beau.lebens@gmail.com', 'qr', ['s' => 150]],
            ['beau.lebens@gmail.com', 'qr'],
            ['beau.lebens@gmail.com', 'vcf'],
            ['beau.lebens@gmail.com', 'xml'],
            ['beau.lebens@gmail.com'],
        ];
    }

    /**
     * Test profile data.
     * 
     * @covers ::getProfile
     *
     * @return void
     */
    public function testGetProfile()
    {
        $client = new Client();

        $data = $client->getProfile('beau.lebens@gmail.com');

        $this->assertInternalType('array', $data);

        $keys = ['id', 'hash', 'requestHash', 'profileUrl', 'preferredUsername', 'thumbnailUrl'];
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $data);
        }
    }

    /**
     * Test wrong profile request.
     * 
     * @covers ::getProfile
     * @expectedException \GuzzleHttp\Exception\ClientException
     *
     * @return void
     */
    public function testGetProfileWrong()
    {
        $client = new Client();

        $client->getProfile('wrong-user');
    }

    /**
     * Test gravatar.com avatars URLs.
     *
     * @param string $email     User email.
     * @param int    $size      Image size (default 80).
     * @param string $extension Image extension (default jpg).
     * @param int    $default   Error response (default 404).
     * @param string $rating    Image rating (default G).
     * 
     * @covers ::getAvatarUrl
     * @dataProvider getAvatarMethodsDataProvider
     *
     * @return void
     */
    public function testGetAvatarUrl($email, $size = 80, $extension = 'jpg', $default = 404, $rating = 'g')
    {
        $client = new Client();

        $url = $client->getAvatarUrl($email, $size, $extension, $default, $rating);

        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED));
        $this->assertStringStartsWith('https://www.gravatar.com/', $url);
    }

    /**
     * Test gravatar.com avatars URLs (as data URIs).
     *
     * @param string $email     User email.
     * @param int    $size      Image size (default 80).
     * @param string $extension Image extension (default jpg).
     * @param int    $default   Error response (default 404).
     * @param string $rating    Image rating (default G).
     * 
     * @covers ::getAvatar
     * @dataProvider getAvatarMethodsDataProvider
     *
     * @return void
     */
    public function testGetAvatar($email, $size = 80, $extension = 'jpg', $default = 404, $rating = 'g')
    {
        $client = new Client();

        $uri = $client->getAvatar($email, $size, $extension, $default, $rating);

        $this->assertStringStartsWith('data:image/', $uri);
    }

    /**
     * Example avatars.
     * 
     * @return array
     */
    public function getAvatarMethodsDataProvider()
    {
        return [
            ['beau.lebens@gmail.com'],
            ['beau.lebens@gmail.com', 50],
            ['beau.lebens@gmail.com', '50'],
            ['beau.lebens@gmail.com', 50, 'png'],
            ['beau.lebens@gmail.com', 50, 'gif'],
            ['beau.lebens@gmail.com', null, 'png'],
            ['beau.lebens@gmail.com', 50, 'png', 404],
            ['beau.lebens@gmail.com', 50, 'png', 404, 'g'],
        ];
    }

    /**
     * Test if a user exists.
     * 
     * @covers ::exists
     *
     * @return void
     */
    public function testExists()
    {
        $client = new Client();

        $this->assertFalse($client->exists('test'));
        $this->assertTrue($client->exists('beau.lebens@gmail.com'));
    }
}
