<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.03.2019
 * Time: 13:02
 */

namespace App\Security;


use App\Exception\InvalidConfirmationTokenException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class UserConfirmationService
{
    /** @var  UserRepository */
    private $userRepository;

    /** @var  EntityManagerInterface */
    private $entityManager;

    /** @var  LoggerInterface */
    private $logger;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    public function confirmUser(string $confirmationToken)
    {
        $this->logger->debug('Fetching user by confirmation token');
        $user = $this->userRepository->findOneBy(
            ['confirmationToken' => $confirmationToken]
        );

        // User was NOT found by confirmation token
        if(!$user){
            $this->logger->debug('User by confirmation token was not found');
            throw new InvalidConfirmationTokenException();
        }

        $user->setEnabled(true);
        $user->setConfirmationToken(null);
        $this->entityManager->flush();

        $this->logger->debug("Confirmed user by confirmation token");
    }
}