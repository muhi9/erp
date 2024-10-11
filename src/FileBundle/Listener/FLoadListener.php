<?php
namespace FileBundle\Listener;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use FileBundle\Entity\File;

class FLoadListener
{
    protected $kernel;

    /**
     * Constructor
     *
     * @param KernelInterface $kernel A kernel instance
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * On Post Load
     * This method will be trigerred once an entity gets loaded
     *
     * @param LifecycleEventArgs $args Doctrine event
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!($entity instanceof File)) {
            return;
        }

        $entity->setProjectDir($this->kernel->getProjectDir());
    }
}