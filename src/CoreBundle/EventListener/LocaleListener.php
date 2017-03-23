<?php

namespace CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class LocaleListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (($locale = $request->get('_locale', null)) !== null) {
            $request->setLocale($locale);
        }
    }
}
