<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.03.2019
 * Time: 14:27
 */

namespace App\Security;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserEnabledChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
       if(!$user instanceof User){
           return;
       }

       if(!$user->getEnabled()){
           throw new DisabledException();
       }
    }

    public function checkPostAuth(UserInterface $user)
    {

    }
}