<?php

namespace Spear\Silex\Application;

use Puzzle\Configuration;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Spear\Silex\Application\Traits;
use Silex\Provider\SecurityServiceProvider;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractApplication extends \Silex\Application
{
    use
        Traits\PathManipulation;

    public function __construct(Configuration $configuration, $rootDir)
    {
        parent::__construct();

        $this->loadConfiguration($configuration);
        $this->enableDebug();
        $this->initializePaths($rootDir);
        $this->initializeSecurity();

        $this->register(new ServiceControllerServiceProvider());
        $this->registerProviders();
        $this->initializeUrlGeneratorProvider();

        $this->initializeServices();

        $this->mountControllerProviders();
    }

    private function loadConfiguration($configuration)
    {
        $this['configuration'] = $configuration;
    }

    private function initializePaths($rootDir)
    {
        $this['root.path'] = $this->enforceEndingSlash($rootDir);
        $this['documentRoot.path'] = $this['root.path'] . 'www' . DIRECTORY_SEPARATOR;
        $this['var.path'] = $this['root.path'] . $this->removeWrappingSlashes($this['configuration']->readRequired('app/var.path')) . DIRECTORY_SEPARATOR;
    }

    private function initializeSecurity()
    {
        $this->register(new SecurityServiceProvider());
        $this['security.firewalls'] = array(
            'admin' => array(
                'pattern' => '^/admin',
                'form' => array('login_path' => '/user/login', 'check_path' => '/admin/login_check'),
                'users' => array(
                    'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
                ),
            ),
        );

        $this->get('/user/login', function(Request $request){
            return $this['twig']->render('user/login_form.html.twig', array(
                    'error'         => $this['security.last_error']($request),
                    'last_username' => $this['session']->get('_security.last_username'),
            ));
        })->bind('user_login');
    }

    private function enableDebug()
    {
        $this['debug'] = $this['configuration']->read('app/debug', false);
    }

    private function initializeUrlGeneratorProvider()
    {
        $this->register(new UrlGeneratorServiceProvider());
    }

    protected function registerProviders()
    {
    }

    protected function initializeServices()
    {
    }

    abstract protected function mountControllerProviders();
}