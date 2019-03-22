<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.03.2019
 * Time: 15:42
 */
namespace App\Controller;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController as BaseAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserAdminController extends BaseAdminController
{
    /** @var  UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**@param  User $entity */
    protected function persistEntity($entity)
    {
        $this->encodeUserPassword($entity);
        parent::persistEntity($entity);
    }


    /**@param User $entity */
    protected function updateEntity($entity)
    {
        $this->encodeUserPassword($entity);
        parent::updateEntity($entity);
    }

    /**
     * @param User $entity
     */
    protected function encodeUserPassword($entity): void
    {
        $entity->setPassword($this->passwordEncoder->encodePassword($entity, $entity->getPassword()));
    }
}
