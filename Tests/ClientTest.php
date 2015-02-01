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
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $guzzle = new GuzzleHttp_Client();

        $client = new Client($guzzle);
    }

    /**
     * @covers ::getHttpClient
     * @covers ::setHttpClient
     */
    public function testHttpClientAccessors()
    {
        $guzzle = new GuzzleHttp_Client();

        $client = new Client();
        $client->setHttpClient($guzzle);

        $this->assertSame($guzzle, $client->getHttpClient());
    }

    /**
     * @covers ::getProfileUrl
     * @dataProvider getProfileUrlDataProvider
     */
    public function testGetProfileUrl($email, $format = 'json', array $options = array())
    {
        $client = new Client();

        $url = $client->getProfileUrl($email, $format, $options);

        $regexp = '/^.+\/[a-zA-Z0-9]{32,32}\.(json|php|vcf|xml|qr)(\?.+)?/';
        $this->assertRegExp($regexp, $url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED));
        $this->assertStringStartsWith('https://www.gravatar.com/', $url);
    }

    /**
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
     * @covers ::getProfile
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
     * @covers ::getProfile
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
    public function testGetProfileWrong()
    {
        $client = new Client();

        $data = $client->getProfile('wrong-user');
    }

    /**
     * @covers ::getAvatarUrl
     * @dataProvider getAvatarMethodsDataProvider
     */
    public function testGetAvatarUrl($email, $size = 80, $extension = 'jpg', $default = 404, $rating = 'g')
    {
        $client = new Client();

        $url = $client->getAvatarUrl($email, $size, $extension, $default, $rating);

        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED));
        $this->assertStringStartsWith('https://www.gravatar.com/', $url);
    }

    /**
     * @covers ::getAvatar
     * @dataProvider getAvatarMethodsDataProvider
     */
    public function testGetAvatar($email, $size = 80, $extension = 'jpg', $default = 404, $rating = 'g')
    {
        $client = new Client();

        $uri = $client->getAvatar($email, $size, $extension, $default, $rating);

        $this->assertStringStartsWith('data:image/', $uri);
    }

    /**
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
     * @covers ::exists
     */
    public function testExists()
    {
        $client = new Client();

        $this->assertFalse($client->exists('test'));
        $this->assertTrue($client->exists('beau.lebens@gmail.com'));
    }
}
