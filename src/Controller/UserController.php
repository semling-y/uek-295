<?php

namespace App\Controller;

use App\DTO\CreateUser;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\Annotations\Post;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController
{
    public function __construct(private SerializerInterface $serializer, private UserRepository $repository){

    }

    #[Post('/api/user/register', name: 'api_user_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CreateUser::class, 'json');

        $user = new User();
        $user->setUserName($dto->username);
        $hashedPassword = $passwordHasher->hashPassword($user, $dto->passwort);
        $user->setPassword($hashedPassword);
        if ($dto->is_admin){
            $user->setRoles(["ROLES_ADMIN", "ROLES_USER"]);
        }

        $this->repository->save($user, true);

        return $this->json('User erstellt');

    }

}