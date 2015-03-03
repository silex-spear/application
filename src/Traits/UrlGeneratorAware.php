<?php

namespace Spear\Silex\Application\Traits

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

trait UrlGeneratorAware
{
    private
        $urlGenerator;

    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;

        return $this;
    }

    private function redirect($route, array $parameters = array())
    {
        return new RedirectResponse(
            $this->urlGenerator->generate($route, $parameters)
        );
    }
}