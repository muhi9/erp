<?php
namespace BaseBundle\Form\DataTransformer;

use BaseBundle\Entity\BaseNoms;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class BasenomTreeTransformer implements DataTransformerInterface
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
     * @param  array $basenomNumber
     * @return basenom|null
     * @throws TransformationFailedException if object (basenom) is not found.
     */
    public function reverseTransform($basenomNumberArray)
    {
       // no basenom number? It's optional, so that's ok
        if (!$basenomNumberArray) {
            return;
        }
        $result = [];
        if(is_array($basenomNumberArray)){
            foreach ($basenomNumberArray as $key => $value) {
                if(!empty($value)){
                    $basenom = $this->entityManager->getRepository(BaseNoms::class)->find($value);
                    if (null === $basenom) {
                        throw new TransformationFailedException(sprintf('An basenom with number "%s" does not exist!',$basenomNumberArray[$key]));
                    }
                    $result[] = $basenom;
                }
            
            }
        }else{
            $basenom = $this->entityManager->getRepository(BaseNoms::class)->find($basenomNumberArray);
            if (null === $basenom) {
                throw new TransformationFailedException(sprintf('An basenom with number "%s" does not exist!',$basenomNumberArray));
            }
            $result = $basenom;
        }
    
        return $result;
    }
}
