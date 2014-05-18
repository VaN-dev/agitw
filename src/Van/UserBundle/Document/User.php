<?php
// src/Van/UserBundle/Document/User.php

namespace Van\UserBundle\Document;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany;

/**
 * @MongoDB\Document
 */
class User extends BaseUser
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;
    
    /**
     * @ReferenceMany(targetDocument="Van\WallBundle\Document\Post", mappedBy="from")
     */
    protected $posts;
    
    /**
     * @ReferenceMany(targetDocument="Van\UserBundle\Document\User")
     */
    protected $friends;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }



    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add post
     *
     * @param Van\WallBundle\Document\Post $post
     */
    public function addPost(\Van\WallBundle\Document\Post $post)
    {
        $this->posts[] = $post;
    }

    /**
     * Remove post
     *
     * @param Van\UserBundle\Document\Post $post
     */
    public function removePost(\Van\WallBundle\Document\Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return Doctrine\Common\Collections\Collection $posts
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add friend
     *
     * @param Van\UserBundle\Document\User $friend
     */
    public function addFriend(\Van\UserBundle\Document\User $friend)
    {
        $this->friends[] = $friend;
    }

    /**
     * Remove friend
     *
     * @param Van\UserBundle\Document\User $friend
     */
    public function removeFriend(\Van\UserBundle\Document\User $friend)
    {
        $this->friends->removeElement($friend);
    }

    /**
     * Get friends
     *
     * @return Doctrine\Common\Collections\Collection $friends
     */
    public function getFriends()
    {
        return $this->friends;
    }
    
    /**
     * Is friend with
     *
     * @param Van\UserBundle\Document\User $user
     * @return boolean
     */
    public function isFriendWith(\Van\UserBundle\Document\User $user)
    {
        // echo count($this->friends);
        // echo '<pre>';
        // \Doctrine\Common\Util\Debug::dump($this->friends->toArray());
        // echo '</pre>';
        // die();

        if(in_array($user, $this->friends->toArray())) {
           return true;
        }
        return false;
    }
    
    public function excludeFriends($users) {
        $usersWithoutFriends = array();
        if(isset($users)) {
            foreach($users as $user) {
                if(!in_array($user, $this->friends->toArray())) {
                    $usersWithoutFriends[] = $user;
                }
            }
        }
        return $usersWithoutFriends;
    }
}
