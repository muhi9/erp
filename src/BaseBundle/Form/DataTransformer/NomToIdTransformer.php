<?php
namespace BaseBundle\Form\DataTransformer;

use BaseBundle\Entity\BaseNoms;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NomToIdTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (nom) to a string (number).
     *
     * @param  BaseNom|null $nom
     * @return string
     */
    public function transform($nom)
    {
        if (null === $nom) {
            return '';
        }

        return $nom->getId();
    }

    /**
     * Transforms a string (number) to an object (nom).
     *
     * @param  string $basenomId
     * @return BaseNom|null
     * @throws TransformationFailedException if object (nom)) is not found.
     */
    public function reverseTransform($id)
    {
        // no number? It's optional, so that's ok
        if (!$id) {
            return;
        }

        $nom = $this->entityManager
            ->getRepository(BaseNoms::class)
            // query for the issue with this id
            ->find($id)
        ;

        if (null === $nom) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An BaseNom with id "%s" does not exist!',
                $id
            ));
        }

        return $nom;
    }
}