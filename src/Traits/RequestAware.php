<?php

namespace Spear\Silex\Application\Traits;

use Symfony\Component\HttpFoundation\Request;

trait RequestAware
{
    private
        $request;

    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }
}
