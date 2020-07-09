<?php

/*
 * (c) Prezent Internet B.V. <info@prezent.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prezent\Doctrine\TranslatableBundle\EventListener;

use Prezent\Doctrine\Translatable\EventListener\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Inject current locale in the TranslatableListener
 *
 * @see EventSubscriberInterface
 */
class LocaleListener implements EventSubscriberInterface
{
    /**
     * @var TranslatableListener
     */
    private $translatableListener;

    /**
     * Constructor
     *
     * @param TranslatableListener $translatableListener
     */
    public function __construct(TranslatableListener $translatableListener)
    {
        $this->translatableListener = $translatableListener;
    }

    /**
     * Set request locale
     *
     * @param RequestEvent $event
     * @return void
     */
    public function onKernelRequest(KernelEvent $event)
    {
        $this->translatableListener->setCurrentLocale($event->getRequest()->getLocale());
    }

    /**
     * {@inheritdoc}
     */
    static public function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('onKernelRequest', 10)),
        );
    }
}
