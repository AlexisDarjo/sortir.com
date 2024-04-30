<?php

// src/EventSubscriber/RedirectToLoginSubscriber.php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class RedirectToLoginSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // Vérifie si l'utilisateur n'est pas connecté et tente d'accéder à une page sécurisée
        if (!$this->security->getUser() && $request->attributes->get('_route') !== 'app_login') {
            // Redirige vers la page de connexion
            $event->setResponse(new RedirectResponse($request->getBaseUrl() . '/login'));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
