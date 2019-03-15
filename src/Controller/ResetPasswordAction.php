<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.03.2019
 * Time: 11:24
 */

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordAction
{
    /** @var  ValidatorInterface */
    private $validator;

    /** @var  UserPasswordEncoderInterface */
    private $userPasswordEncoder;

    /** @var  EntityManagerInterface */
    private $entityManager;

    /** @var  JWTTokenManagerInterface */
    private $tokenManager;

    public function __construct(
        ValidatorInterface $validator,
        UserPasswordEncoderInterface $userPasswordEncoder,
        EntityManagerInterface $entityManager,
        JWTTokenManagerInterface $tokenManager
    )
    {
        $this->validator = $validator;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
        $this->tokenManager = $tokenManager;
    }

    public function __invoke(User $data)
    {
        //$reset = new ResetPasswordAction();
        //$reset();

////        var_dump(
//            $data->getNewPassword(),
//            $data->getNewRetypedPassword(),
//            $data->getOldPassword(),
//            $data->getRetypedPassword()
//        );
//        die;

        $this->validator->validate($data);

        $data->setPassword(
            $this->userPasswordEncoder->encodePassword(
                $data,
                $data->getNewPassword()
            )
        );

        // After password change, old tokens are still valid
        $data->setPasswordChangeDate(time());


        $this->entityManager->flush();

        $token = $this->tokenManager->create($data);

        return new JsonResponse(['token' => $token]);

        // return $data;

        // Validator is only called after we return the data from this action!
        // Only here it checks for the current password, but we've modified it!

        // Entity is persisted automatically, only if validation pass
    }
}