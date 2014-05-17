<?php
// src/Van/UserBundle/VanUserBundle.php

namespace Van\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VanUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
