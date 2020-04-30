<?php

namespace AppBundle\Repository;

/**
 * DownloadRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DownloadRepository extends \Doctrine\ORM\EntityRepository
{
    public function getNumberOfDownloads() {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(d.id) FROM AppBundle:Download d'
            )
            ->getResult();
    }
}