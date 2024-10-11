<?php
namespace BaseBundle\Form\DataTransformer;

use BaseBundle\Entity\BaseNoms;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Collections\ArrayCollection;



class BaseNomTagsTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (basenom) to a json_encoded array string - directly usable in form field.
     *
     * @param  basenom|null $basenom
     * @return string
     */
    public function transform($tags)
    {
        if (null === $tags) {
            return null;
        }
        $tagsArray = [];
        foreach ($tags as $obj) {
            $r = [
              'id' => $obj->getId(),
              //'value' => $obj->getName(),
              'value' => $obj->getTreeName(),
              'title' => $obj->getTreeName(),
            ];
            $tagsArray[] = $r;
        }
        //if (sizeof($tagsArray) < 1) return null;
        //echo 'tags';dump($tags->toArray());exit;
        return json_encode($tagsArray);
    }


    /**
     * Transforms a string (json_array[object,object]) to an ArrayCollection.
     *
     * @param  string $tagsString
     * @return array[basenoms]|null
     * @throws TransformationFailedException if object (basenom) is not found.
     */
    public function reverseTransform($tagsString)
    {
        $result = new ArrayCollection();
        // no basenom strings? It's optional, so that's ok
        if (!$tagsString) {
            return $result;
        }
        $tagsArray = json_decode($tagsString, true);
        $jsonError = json_last_error();
        if ($jsonError != JSON_ERROR_NONE) {
            throw new TransformationFailedException("Error (" . $jsonError . ") decoding json: " . $tagsString, $jsonError);
        }
        //print_r($tagsArray);exit;
        $ber = $this->entityManager->getRepository(BaseNoms::class);
        foreach ($tagsArray as $val) {
            $basenom = $ber->find($val['id']);
            if (null === $basenom) {
                throw new TransformationFailedException(sprintf(
                    'An basenom with id "%s" does not exist (%s)!',
                    $val['id'],print_r($val,true)
                ));
            }
            $result->add($basenom);
        }

        return $result;
    }
}
