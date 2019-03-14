<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.03.2019
 * Time: 12:46
 */

namespace App\Entity;
use Symfony\Component\Security\Core\User\UserInterface;

interface AuthoredEntityInterface
{
    public function setAuthor(UserInterface $user): AuthoredEntityInterface;
}