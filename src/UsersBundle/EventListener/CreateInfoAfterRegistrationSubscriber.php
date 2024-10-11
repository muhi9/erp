<?php
namespace UsersBundle\EventListener;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use UsersBundle\Entity\UserPersonalInfo;
use UsersBundle\Entity\UserContact;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class CreateInfoAfterRegistrationSubscriber implements EventSubscriberInterface
{
    private $container;

   // public function __construct(RouterInterface $router, EntityManagerInterface $em)
    public function __construct(ContainerInterface $container)
    {
        //$this->router = $router;
        $this->container = $container;

    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $router = $this->container->get('router');
        $url = $router->generate('fos_user_registration_confirmed');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }
    public function onRegistrationComplete(FilterUserResponseEvent $user)
    {

        $userInfo = $user->getUser();
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $positions = $entityManager->getRepository(\BaseBundle\Entity\BaseNoms::class)->findBy(['type'=>'profile.position']);        
            $user = $entityManager->getRepository(UserPersonalInfo::class)->findBy(['user'=>$userInfo]);
            if(empty($user)){
                $info = new UserPersonalInfo();
                $info->setUser($userInfo);
               
                if(!empty($positions)) {
                    foreach ($positions as $key => $type) {
                        if(in_array($type->getBnomKey(), ['ROLE_USER'])){
                              $info->addPosition($type);
                    
                        }
                    }
                }
                
                $entityManager->persist($info);
                $entityManager->flush();    
            }   
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationComplete'
        ];
    }
}
