<?php

namespace Onixcat\Bundle\ViatecParserBundle\Entity;

/**
 * ParserViatecSettings
 */
class ParserViatecSettings
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $viatecLogin;

    /**
     * @var string
     */
    private $viatecPassword;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set viatecLogin.
     *
     * @param string $viatecLogin
     *
     * @return ParserViatecSettings
     */
    public function setViatecLogin($viatecLogin)
    {
        $this->viatecLogin = $viatecLogin;

        return $this;
    }

    /**
     * Get viatecLogin.
     *
     * @return string
     */
    public function getViatecLogin()
    {
        return $this->viatecLogin;
    }

    /**
     * Set viatecPassword.
     *
     * @param string $viatecPassword
     *
     * @return ParserViatecSettings
     */
    public function setViatecPassword($viatecPassword)
    {
        $this->viatecPassword = $viatecPassword;

        return $this;
    }

    /**
     * Get viatecPassword.
     *
     * @return string
     */
    public function getViatecPassword()
    {
        return $this->viatecPassword;
    }
}
