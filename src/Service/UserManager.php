<?php

namespace App\Service;

use App\Entity\User;
use App\Validator\UserValidator;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var UserValidator
     */
    private $validator;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(ObjectManager $manager,
                                UserValidator $validator,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function update(User $user): bool
    {
        $this->initialiseUser($user);

        if (!$this->validator->isValid($user)) {
            return false;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return true;
    }

    public function initialiseUser(User $user)
    {
        if (empty($user->getEmailValidatedToken())) {
            $user
                    ->setEmailValidated(false)
                    ->setEmailValidatedToken(md5(random_bytes(50)));
        }

        $this->encodePassword($user);
    }

    public function encodePassword(User $user)
    {
        $plainPassword = $user->getPlainPassword();

        if (!empty($plainPassword)) {
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $plainPassword
                ));
        }
    }

    public function getErrors(User $entity)
    {
        return $this->validator->getErrors($entity);
    }

    public function remove(User $user)
    {
        $this->manager->remove($user);
        $this->manager->flush();
    }
}
