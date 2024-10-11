<?php
namespace BaseBundle\Form\DataTransformer;

use BaseBundle\Entity\BaseNoms;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class BasenomTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (basenom) to a string (number).
     *
     * @param  basenom|null $basenom
     * @return string
     */
    public function transform($basenom)
    {
        if (null === $basenom) {
            return null;
        }
        // handle non-entity cases. We set array with ['id' => 1, 'name' => 'Selected name']
        if (is_array($basenom) && isset($basenom['id']))
            return $basenom['id'];
	if (is_int($basenom) || (is_string($basenom) && preg_match('/^[\d]+$/', $basenom))) return $basenom;
        if ($basenom instanceof BaseNoms) {
            if (!$basenom || empty($basenom->getId())) {
                return null;
            }
            return $basenom->getId();
        }
        throw new \Exception("Error converting basenom", 1423);

    }


    /**
     * Transforms a string (number) to an object (basenom).
     *
     * @param  string $basenomNumber
     * @return basenom|null
     * @throws TransformationFailedException if object (basenom) is not found.
     */
    public function reverseTransform($basenomNumber)
    {


        // no basenom number? It's optional, so that's ok
        if (!$basenomNumber) {
            return;
        }

        $basenom = $this->entityManager
            ->getRepository(BaseNoms::class)
            // query for the basenom with this id
            ->find($basenomNumber);

        if (null === $basenom) {
            throw new TransformationFailedException(sprintf(
                'An basenom with number "%s" does not exist!',
                $basenomNumber
            ));
        }
        return $basenom;
    }
}
