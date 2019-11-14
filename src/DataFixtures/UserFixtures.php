<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\UserManager;
use App\Validator\UserValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class UserFixtures extends Fixture
{
    const KEY = 'user';

    CONST DATA = [
        [
            'username' => 'AppFixtures1',
            'email' => 'AppFixtures1@live.fr',
            'password' => 'u12345678',
            'roles' => ['ROLE_USER'],
        ],
        [
            'username' => 'AppFixtures2',
            'email' => 'AppFixtures2@live.fr',
            'password' => 'u12345678',
            'roles' => ['ROLE_USER'],
        ],
        [
            'username' => 'AppFixtures3',
            'email' => 'AppFixtures3@live.fr',
            'password' => 'u12345678',
            'roles' => ['ROLE_GESTIONNAIRE'],
        ],
        [
            'username' => 'manu',
            'email' => 'emmanuel.sauvage@live.fr',
            'password' => 'u12345678',
            'roles' => ['ROLE_ADMIN'],
        ]
    ];

    /**
     * @var UserManager
     */
    private $userManager;


    /**
     * @var UserValidator
     */
    private $validator;

    public function __construct(UserValidator $validator, UserManager $userManager)
    {
        $this->validator = $validator;
        $this->userManager = $userManager;
    }

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < count(self::DATA); ++$i) {
            $entity = new User();

            $instance = $this->initialise(new User(), self::DATA[$i]);

            $this->checkAndPersist($manager, $instance);

            $this->saveReference($instance, self::KEY,$i);
        }
        $manager->flush();

    }
    private function checkAndPersist(ObjectManager $manager, User $instance)
    {
        $this->userManager->encodePassword($instance);
        $this->userManager->initialiseAvatar($instance);
        if ($this->validator->isValid($instance)) {
            $manager->persist($instance);
        } else {
            var_dump('Validator : ' . $this->validator->getErrors($instance));
        }
    }
    private function initialise(User $instance, $data): User
    {
        $instance->setUsername($data['username'])
            ->setEmail($data['email'])
            ->setPlainPassword($data['password'])
            ->setRoles($data['roles']);

        return $instance;
    }

    private function saveReference(User $instance, string $key, int $indice)
    {
        $this->addReference($key . '-' . $indice, $instance);
    }



}
