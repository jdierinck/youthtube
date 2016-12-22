<?php

namespace YTBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * WorkshopRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WorkshopRepository extends EntityRepository
{
	public function findFirstId($doorl)
    	{
    	// Using DQL
//         return $this->getEntityManager()
//             ->createQuery(
//                 'SELECT p.id FROM YTBundle:Workshop p WHERE p.continu=:doorl ORDER BY p.id ASC'
//             )
// 			-> setParameter('doorl',$doorl)
// 			-> setMaxResults(1)
//          ->getResult();
    	
    	// Using Doctrine's Query Builder
		$query = $this->createQueryBuilder('p')
							->select('p.id')
							->where('p.continu=:doorl')
							->setParameter('doorl',$doorl)
							->orderBy('p.id','ASC')
							->setMaxResults(1)
							->getQuery();
		$result = $query->getResult();
		return $result;
    	}
    public function findAllIds($doorl)
    {
		$query = $this->createQueryBuilder('p')
							->select('p.id')
							->where('p.continu=:doorl')
							->setParameter('doorl',$doorl)
							->orderBy('p.id','ASC')
							->getQuery();
		$result = $query->getResult();
		return $result;    	
    }
}