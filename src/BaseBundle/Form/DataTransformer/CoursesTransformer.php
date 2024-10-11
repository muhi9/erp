<?php
namespace BaseBundle\Form\DataTransformer;

use BaseBundle\Entity\BaseNoms;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CoursesTransformer implements DataTransformerInterface
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
        return $basenom;
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
        $result = [];
        foreach ($basenomNumber as $key => $value) {
            $basenom = $this->entityManager
            ->getRepository(BaseNoms::class)
            // query for the basenom with this id
            ->find($value);

            if (null === $basenom) {
                throw new TransformationFailedException(sprintf(
                    'An basenom with number "%s" does not exist!',
                    $basenomNumber
                ));
            }
            $result[] = $basenom;
        }
        
        return $result;
    }
}
