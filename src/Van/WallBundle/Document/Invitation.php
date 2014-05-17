<?php
// src/Van/WallBundle/Document/Invitation.php

namespace Van\WallBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

/**
 * @MongoDB\Document
 */
class Invitation
{
    /**
     * @MongoDB\Id
     */
    protected $id;
    
    /** 
     * @ReferenceOne(targetDocument="\Van\UserBundle\Document\User") 
     */
    protected $from;
    
    /** 
     * @ReferenceOne(targetDocument="\Van\UserBundle\Document\User") 
     */
    protected $to;
    
    /**
     * @MongoDB\Date
     */
    protected $date;
    
    /**
     * @MongoDB\Int
     */
    protected $state;
    
    public function __construct()
    {
        $this->date = new \DateTime();
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
     * Set from
     *
     * @param \Van\UserBundle\Document\User $from
     * @return self
     */
    public function setFrom(\Van\UserBundle\Document\User $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Get from
     *
     * @return \Van\UserBundle\Document\User $from
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set to
     *
     * @param \Van\UserBundle\Document\User $to
     * @return self
     */
    public function setTo(\Van\UserBundle\Document\User $to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Get to
     *
     * @return \Van\UserBundle\Document\User $to
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set date
     *
     * @param date $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set state
     *
     * @param int $state
     * @return self
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Get state
     *
     * @return int $state
     */
    public function getState()
    {
        return $this->state;
    }
}
