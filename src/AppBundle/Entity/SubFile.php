<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubFile
 *
 * @ORM\Table(name="sub_file")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubFileRepository")
 */
class SubFile
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="brochureFileName", type="string", length=255, unique=true)
     */

    private $brochureFileName;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set brochureFileName
     *
     * @param string $brochureFileName
     *
     * @return SubFile
     */
    public function setBrochureFileName($brochureFileName)
    {
        $this->brochureFileName = $brochureFileName;

        return $this;
    }

    /**
     * Get brochureFileName
     *
     * @return string
     */
    public function getBrochureFileName()
    {
        return $this->brochureFileName;
    }
}

