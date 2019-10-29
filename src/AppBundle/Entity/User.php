<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="indNumber", type="integer", length=5, unique=true)
     */
    protected $indNumber;

    /**
     * @return string
     */
    public function getSurName()
    {
        return $this->surName;
    }

    /**
     * @param string $surName
     */
    public function setSurName($surName)
    {
        $this->surName = $surName;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="surName", type="string", length=40, unique=true)
     */
    protected $surName;

    /**
     * @return integer
     */
    public function getIndNumber()
    {
        return $this->indNumber;
    }

    /**
     * @param integer $indNumber
     */
    public function setIndNumber($indNumber)
    {
        $this->indNumber = $indNumber;
    }

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}