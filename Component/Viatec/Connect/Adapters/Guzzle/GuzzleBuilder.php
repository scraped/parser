<?php

namespace Onixcat\Component\Viatec\Connect\Adapters\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Onixcat\Component\Viatec\Connect\Adapters\HelperInterface;
use Onixcat\Component\Viatec\Connect\Adapters\ConnectAdapterInterface;

/**
 * Class GuzzleBuilder
 * Build Guzzle helper object
 */
class GuzzleBuilder implements ConnectAdapterInterface
{
    /**
     * @var $uri string
     */
    private $uri;

    /**
     * @var $login string
     */
    private $login;

    /**
     * @var $password string
     */
    private $password;

    /**
     * @var $needle string
     */
    private $needle;

    /**
     * GuzzleBuilder constructor.
     * @param $uri
     * @param $login
     * @param $password
     * @param $needle
     */
    public function __construct($uri, $login, $password, $needle)
    {
        $this->uri = $uri;
        $this->login = $login;
        $this->password = $password;
        $this->needle = $needle;
    }

    /**
     * @inheritdoc
     */
    public function build(): HelperInterface
    {
        $client = new Client();
        $cookieJar = new CookieJar();

        return new GuzzleHelper($client, $cookieJar, $this->uri, $this->login, $this->password, $this->needle);
    }
}
