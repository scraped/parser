<?php

namespace Onixcat\Component\Viatec\Connect\Adapters;

use Onixcat\Component\Viatec\Connect\Exception\AuthException;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface HelperInterface
 */
interface HelperInterface
{
    /**
     * Make authorization
     *
     * @return ResponseInterface
     */
    public function auth(): ?ResponseInterface;

    /**
     * Check if auth is successful
     *
     * @throws AuthException
     * @return string
     */
    public function isAuth(string $html): bool ;

    /**
     * Get content from page
     *
     * @return string
     */
    public function getPageWithAuth(string $pageUrl): self ;

    /**
     * Returns html page
     *
     * @return string
     */
    public function getHtml() : string ;

}
