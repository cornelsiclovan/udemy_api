<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.03.2019
 * Time: 12:41
 */

namespace App\EventSubscriber;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Exception\EmptyBodyException;
use function in_array;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use function var_dump;

class EmptyBodySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['handleEmptyBody', EventPriorities::POST_DESERIALIZE]
        ];
    }

    public function handleEmptyBody(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $method = $request->getMethod();
        $route = $request->get('_route');

        if(!in_array($method, [Request::METHOD_POST, Request::METHOD_PUT]) ||
            in_array($request->getContent(), ['html', 'form']) ||
            substr($route, 0,3) !== 'api'){
            return;
        }



        $data = $event->getRequest()->get('data');

       // var_dump($request); die();

        if(null === $data){
            throw new EmptyBodyException();
        }
    }
}