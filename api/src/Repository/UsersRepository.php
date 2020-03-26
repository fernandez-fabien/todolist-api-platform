<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository// implements PasswordUpgraderInterface
{
    /** EntityManager $manager */
    private $manager;

    /** UserPasswordEncoderInterface $encoder */
    private $encoder;

    /**
     * UsersRepository constructor.
     * @param ManagerRegistry $registry
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($registry, Users::class);

        $this->manager = $registry->getManager();
        $this->encoder = $encoder;
    }

    /**
     * Create a new user
     * @param $data
     * @return Users
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createNewUser($data)
    {
        $user = new Users();
        $user->setEmail($data['email'])
            ->setName($data['name'])
            ->setPassword($this->encoder->encodePassword($user, $data['password']));

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}
