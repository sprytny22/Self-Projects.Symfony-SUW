<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 * 
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
     * @ORM\ManyToMany(targetEntity="SubFile", inversedBy="users", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="UserSubFile")
     */
    private $file;

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
     * @ORM\Column(name="surName", type="string", length=40, unique=false)
     */
    protected $surName;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=20, unique=false)
     */
    protected $firstName;

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
        $this->username = $indNumber;
        $this->indNumber = $indNumber;
        $this->setEnabled(false);
    }

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Add file
     *
     * @param \AppBundle\Entity\SubFile $file
     *
     * @return User
     */
    public function addFile(\AppBundle\Entity\SubFile $file)
    {
        $this->file[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \AppBundle\Entity\SubFile $file
     */
    public function removeFile(\AppBundle\Entity\SubFile $file)
    {
        $this->file->removeElement($file);
    }

    /**
     * Get file
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFile()
    {
        return $this->file;
    }

    public function setUsername($username){
        $this->firstName = $username;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }
}
