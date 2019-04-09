<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2019
 * Time: 15:03
 */

namespace App\EventListener;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if(!$user instanceof User){
            return;
        }

        $data['id'] = $user->getId();
        $event->setData($data);
    }
}