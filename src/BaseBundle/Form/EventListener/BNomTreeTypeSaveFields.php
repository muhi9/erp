<?php
namespace BaseBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

//DOC source: https://symfony.com/doc/3.4/form/events.html#event-subscribers

class BNomTreeTypeSaveFields implements EventSubscriberInterface
{
    private $em;
    private $request;

    public function __construct(EntityManagerInterface $em, Request $request){
          $this->entityManager = $em;
          $this->request = $request;//->getCurrentRequest();
    }

    public static function getSubscribedEvents()
    {
        return [
            //FormEvents::PRE_SET_DATA => 'onPreSetData',
            //FormEvents::POST_SET_DATA   => 'onPreSubmit',
            FormEvents::PRE_SUBMIT   => 'onPreSubmit',
        ];
    }
/*
    public function onPreSetData(FormEvent $event)
    {
        $user = $event->getData();
        $form = $event->getForm();

        // checks whether the user from the initial data has chosen to
        // display their email or not.
        if (true === $user->isShowEmail()) {
            $form->add('email', EmailType::class);
        }
    }
*/
    // SET Entity data based on keys, because the fields are 'extra_fields' and will not get set automatically
    public function onPreSubmit(FormEvent $event)
    {
        //$user = $event->getData();
        //$form = $event->getForm();

        $dataForm = $event->getData();
        $form = $event->getForm();
        $formData = $form->getData();
        //dump($form->all());exit;
        //dump($form->getConfig()->getDataMapper());exit;
        foreach ($dataForm as $key => $value) {
          if ($form->has($key)) {
            // skip mapped fields
            //echo "HAVE: $key.\n<br>";
            // TODO: implement setting ot ArrayCollections and ManyToMany!
            continue;
          }
          //echo "NOT HAVE: $key\n<br>";
          //dump($dataForm);dump($formData);exit;
          if (null !== $formData && property_exists($formData, $key)) {
            //echo "$key exists. set it  to $value";
            $kn = ucfirst($key);
            $pos = strpos($kn,'_');
            if ($pos)
              $kn = substr($kn,0,$pos) . ucfirst(substr($kn,$pos+1));
            $kn = 'set'.$kn;
            if (!method_exists($formData, $kn))
              throw new \Exception('Error setting data for form. '. get_class($formData)." doesn't have method: ".$kn, 1345);
            $rm = new \ReflectionMethod($formData, $kn);
            //print_r(get_class_methods($rm));
            $rm = $rm->getParameters()[0];
            $rm = $rm->getClass();
            $rmf = null;
            if (null !== $rm && !empty($value)) {
              $rm = $rm->name;
              $rm = '\\'.$rm;
              //echo $rm;exit;
              //$rm = $this->entityManager->getRepository('BaseBundle:BaseNoms')->find($value);
              $rmf = $this->entityManager->getRepository($rm)->find($value);
              //echo "FIND RM: " . $rm . " -> ".$value;exit;
            }
            if ($rm) {
              if ($rmf)
                //echo "$kn: " . $rm."\n";
                $formData->$kn($rmf);
                //print_r($dataForm);
            } else {
              if (!empty($value))
                $formData->$kn($value);
            }
          }
        }
        //exit;
    }
}