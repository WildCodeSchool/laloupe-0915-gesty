<?php



namespace Gesty\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GestyUserBundle extends Bundle
{
    public function getParent()
    {
    return 'FOSUserBundle';
    }
}