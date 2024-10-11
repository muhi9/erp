<?php
namespace BaseBundle\Listener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use UsersBundle\Service\UserService;
class SessionIdleListener
{

    protected $session;
    protected $securityToken;
    protected $router;
    protected $sessionSettings;

    public function __construct(SessionInterface $session, TokenStorageInterface $securityToken, RouterInterface $router, UserService $sessionSettings)
    {
        $this->session = $session;
        $this->securityToken = $securityToken;
        $this->router = $router;
        //$this->sessionSettings = $sessionSettings->getSessionSettings();
        $this->sessionSettings = $sessionSettings;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {

        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType()) {

            return;
        }
        $sessionSettings = $this->sessionSettings->getSessionSettings();
        if (!empty($sessionSettings) && $this->securityToken->getToken() instanceof UsernamePasswordToken) {
         
            
            $this->session->start();
            if(!$this->session->get('lastUsed')) {
                $this->session->set('lastUsed', time());
            }
            
            $lastUsed = $this->session->get('lastUsed');
            $lapse = time() - $lastUsed;
            if ($lapse > ($sessionSettings['max_life']+$sessionSettings['countdown'])) {
                $this->securityToken->setToken(null);
                //$this->session->getFlashBag()->set('info', 'You have been logged out due to inactivity.');
                $this->session->remove('lastUsed');
                // Change the route if you are not using FOSUserBundle.
                //if($this->router->getContext()->getPathInfo() != $this->router->generate('fos_user_security_login')){
                $event->setResponse(new RedirectResponse($this->router->generate('fos_user_security_login')));
                //}
            }else{
                if($this->router->getContext()->getPathInfo() != '/session-check') {
                    //$this->session->set('lastUsed',(time()+($sessionSettings['max_life']+$sessionSettings['countdown'])));
                    $this->session->set('lastUsed', time());    
                }
                
            }

            
        }
    }
}