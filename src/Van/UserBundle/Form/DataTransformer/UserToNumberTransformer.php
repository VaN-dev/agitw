<?php
// src/Van/UserBundle/Form/DataTransformer/UserToNumberTransformer.php

namespace Van\UserBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Van\UserBundle\Document\User;

class UserToNumberTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (user) to a string (number).
     *
     * @param  User|null $user
     * @return string
     */
    public function transform($user)
    {
        if (null === $user) {
            return "";
        }

        return $user->getId();
    }

    /**
     * Transforms a string (number) to an object (user).
     *
     * @param  string $number
     *
     * @return User|null
     *
     * @throws TransformationFailedException if object (user) is not found.
     */
    public function reverseTransform($number)
    {
        if (!$number) {
            return null;
        }

        $user = $this->om
            ->getRepository('VanUserBundle:User')
            ->findOneBy(array('id' => $number))
        ;

        if (null === $user) {
            throw new TransformationFailedException(sprintf(
                'An user with number "%s" does not exist!',
                $number
            ));
        }

        return $user;
    }
}