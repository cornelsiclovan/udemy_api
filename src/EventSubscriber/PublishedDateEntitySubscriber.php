<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.03.2019
 * Time: 11:25
 */

namespace App\EventSubscriber;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\PublishedDateEntityInterface;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PublishedDateEntitySubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setDatePublished', EventPriorities::PRE_WRITE]
        ];
    }

    public function setDatePublished(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if(!$entity instanceof PublishedDateEntityInterface || Request::METHOD_POST !== $method){
            return;
        }

        $entity->setPublished(new DateTime());
    }
}