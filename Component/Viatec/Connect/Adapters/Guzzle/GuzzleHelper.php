<?php

namespace Onixcat\Component\Viatec\Connect\Adapters\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\CookieJarInterface;
use Onixcat\Component\Viatec\Connect\Exception\AuthException;
use Onixcat\Component\Viatec\Connect\Adapters\HelperInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Guzzle helper
 *
 * Class GuzzleHelper
 */
class GuzzleHelper implements HelperInterface
{

    /**
     * @var Client
     */
    private $client;


    /**
     * @var CookieJar
     */
    private $cookieJar;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $needle;

    /**
     * @var int
     */
    private $attemptsAmount = 5;

    /**
     * @var string
     */
    private $html;

    /**
     * Auth constructor.
     * @param ClientInterface $client
     * @param CookieJarInterface $cookieJar
     */
    public function __construct(ClientInterface $client, CookieJarInterface $cookieJar, $uri, $login, $password, $needle)
    {
        $this->client = $client;
        $this->cookieJar = $cookieJar;

        $this->uri = $uri;
        $this->login = $login;
        $this->password = $password;
        $this->needle = $needle;
    }


    /**
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function auth(): ?ResponseInterface
    {
        $this->attemptsAmount--;

        if ($this->attemptsAmount < 0){
            throw new AuthException('Login or password are incorrect');
        }
        try {
            return $this->client->request('POST', $this->uri,
                [
                    'verify' => false,
                    'cookies' => $this->cookieJar,
                    'form_params' =>
                        [
                            'login' => $this->login,
                            'password' => $this->password
                        ]
                ]);

        } catch (\RuntimeException $e) {
//            $e->getMessage();
            // TODO: apply logging later
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * @inheritdoc
     */
    public function isAuth(string $html): bool
    {
        return stripos($html, $this->needle) ? true : false;
    }

    /**
     * @inheritdoc
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPageWithAuth(string $pageUrl): HelperInterface
    {
        $response = $this->client->request(
            'GET',
            $pageUrl,
            [
                'cookies' => $this->cookieJar,
                'verify' => false,
            ]
        );

        $this->html = $response->getBody()->getContents();

        if (!$this->isAuth($this->html)){
            $this->auth();
            $this->getPageWithAuth($pageUrl);
        }
        return $this;
    }

    /**
     * @param int $attemptsAmount
     */
    public function setAttemptsAmount(int $attemptsAmount): void
    {
        $this->attemptsAmount = $attemptsAmount;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }
}
