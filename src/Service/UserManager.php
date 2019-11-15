<?php

namespace App\Service;

use App\Entity\Avatar;
use App\Entity\User;
use App\Validator\UserValidator;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $avatar = 'data:image/png;base64,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';

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

        $this->initialiseAvatar($user);
    }

    public function initialiseAvatar(User $user)
    {
        if (!$user->getAvatar()) {
            $this->avatarAdd($user, $this->avatar);
        }
    }

    public function avatarAdd(User $user, string $image)
    {
        $UserAvatar=
            !$user->getAvatar()
                ?
                new Avatar()
                :
                $user->getAvatar();

        $UserAvatar->setImage($image);
        $user->setAvatar($UserAvatar);
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

    public function active(User $user)
    {
        $user->setEmailValidated(true);
        $user->setEmailValidatedToken(date_format(new DateTime(), 'Y-m-d H:i:s'));
        $user->setRoles(array_merge($user->getRoles(),['ROLE_USER']));
    }

    public function initialisePasswordForget(User $user)
    {
        $user->setPasswordForgetToken(md5(random_bytes(50)));
    }
}
