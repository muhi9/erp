<?php
// Propagating awareness via ManyToOne associations
// Based on Michaël Perrin's filter 
// @see http://www.michaelperrin.fr/2014/07/25/doctrine-filters/
// And inspred by Steve's idea of using subqueries
// @see http://stackoverflow.com/questions/12354285/how-can-i-implemented-a-doctrine2-filter-based-on-a-relation
namespace BaseBundle\Filter;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use BaseBundle\Annotations\BaseNomsAware as BaseNomsAware;
//define('USER_ENTITY', 'AppBundle\Entity\User');
define('BASE_NOMS_ENTITY', 'BaseBundle\\Entity\\BaseNoms');
define('BASE_NOMS_AWARE', 'BaseBundle\\Annotations\\BaseNomsAware');
class BaseNomsFilter extends SQLFilter
{
    public $reader; // The annotation reader
    public $manager; // The entity manager
    private function getPropertyAndFieldName(
            ClassMetadata $targetEntity,
            BaseNomsAware $baseNomsAware)
    {
        $propertyName = $baseNomsAware->nomType;
        print_r($propertyName);exit;
        if ($propertyName)
        {
            $fieldName =
                $targetEntity->isIdentifier($propertyName) ?
                $targetEntity->getColumnName($propertyName) :
                $targetEntity->getSingleAssociationJoinColumnName($propertyName);
        }
        else
        {
            // Try to get the information via the fieldname,
            // for backwards compatability reasons only
            $fieldName = $baseNomsAware->nomType;
            $propertyName = $targetEntity->getFieldForColumn($fieldName);
        }
        return array($propertyName, $fieldName);
    }
    private function buildBasicQuery($targetTableAlias, $fieldName)
    {
        echo 'fooz';exit;
        try
        {
            // Don't worry, getParameter automatically quotes parameters
            $user_id = $this->getParameter('user_id');
        }
        catch (\InvalidArgumentException $e) {
            // No user id has been defined
            return '';
        }
        // Something went wrong
        if (empty($fieldName) || empty($user_id)) {
            // just to be sure make sure nothing gets returned
            return '0'; // false;
        }
        $query = sprintf(
            '(%1$s.%2$s = %3$s)',
            $targetTableAlias, $fieldName, $user_id
        );
        return $query;
    }
    private function buildQuery(ClassMetadata $targetEntity, $targetTableAlias, BaseNomsAware $baseNomsAware)
    {
        echo 'fooz';exit;
        return '';
        list($propertyName, $fieldName) =
            $this->getPropertyAndFieldName($targetEntity, $baseNomsAware);
        $targetClassName =
            $targetEntity->hasAssociation($propertyName) ?
            $targetEntity->getAssociationTargetClass($propertyName) : null;
        if ($targetClassName == null)
        {
           return $this->buildBasicQuery($targetTableAlias, $fieldName);
        }
        $reflClass = new \ReflectionClass($targetClassName);
        //$baseNomsAware = $this->reader->getClassAnnotation($reflClass, USER_AWARE);
        if (!$baseNomsAware)
        {
            throw new \Exception('Association of BaseNomsAware entity isn\'t BaseNomsAware Exception');
        }
        $classMetaData = $this->manager->getClassMetadata($targetClassName);
        $tableName = $classMetaData->getTableName();
        $identifier = $classMetaData->getSingleIdentifierFieldName();
        $query =
            sprintf('(%s.%s IN (SELECT %s FROM %s WHERE ', $targetTableAlias, $fieldName, $identifier, $tableName) .
            $this->buildQuery($classMetaData, $tableName, $baseNomsAware) . '))';
        return $query;
    }
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$this->reader)
        {
            return '';
        }
        echo "CL: ".$targetEntity->getName().'<br>';
        //exit;
        return '';

        echo 'foo<hr>';
        $caller = debug_backtrace();
        print_r(array_keys($caller[0]));exit;
        print_r($targetEntity->getAssociationMappings());exit;
        //echo $this->getParameter('someshit');
        //var_dump($targetEntity->getReflectionClass());exit;
        $baseNomsAware = $this->reader->getPropertyAnnotation(
                //$targetEntity->getReflectionProperty('type'),
                $targetEntity->getReflectionProperty('type'),
                BASE_NOMS_AWARE
        );
        //var_dump($baseNomsAware);exit;

        list($propertyName, $fieldName) =
            $this->getPropertyAndFieldName($targetEntity, $baseNomsAware);
        $targetClassName =
            $targetEntity->hasAssociation($propertyName) ?
            $targetEntity->getAssociationTargetClass($propertyName) :
        exit;
        //$ae = new \AircraftBundle\Entity\Aircraft();
        //$reflectionClass = new \ReflectionClass($ae);
        //$reflectionClass = new \ReflectionClass('AircraftBundle\Entity\Aircraft');//\AircraftBundle\Entity\Aircraft::class);
        $reflectionClass = new \ReflectionClass(\AircraftBundle\Entity\Aircraft::class);
        $property = $reflectionClass->getProperty('type');
        //var_dump($this->reader->getPropertyAnnotations($property));exit;
        //WORKS YEAAAAA: var_dump($this->reader->getPropertyAnnotation($property, BASE_NOMS_AWARE)->nomType);exit;
        //$reader = new \Doctrine\Common\Annotations\AnnotationReader();

        exit;

        //var_dump($this->getParameter('type'));exit;
        echo "D: ".$targetEntity->getReflectionClass()."<hr>";
        //var_dump($targetEntity->getReflectionProperty('type'));exit;
        //print_r($this->reader->getPropertyAnnotation($targetEntity->getReflectionProperty('type'), 'type'));
        //echo 'lainaaa';exit;
        //return '';
        //print_r($this->manager->getMetadataFactory());exit;
        //echo $targetEntity->getReflectionClass();exit;
        //print_r($this->reader);exit;
        //print_r($this->getParameter('nomType'));exit;
        //print_r($targetEntity);exit;
        //return '';
        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "user aware" (marked with an annotation)
        //$userAware = $this->reader->getClassAnnotation(
        //        $targetEntity->getReflectionClass(),
        //        BASE_NOMS_AWARE
        //);
        //print_r($this->getConnection());exit;
        //echo "TE: ".print_r($targetEntity->getReflectionProperties(),true).'<hr>';
        //echo "BNE: ".BASE_NOMS_AWARE.'<hr>';
        //print_r($this->reader->getPropertyAnnotations($targetEntity->getReflectionProperty('name')));
        //var_dump($this->reader->getPropertyAnnotation($targetEntity->getReflectionProperty('id'),BASE_NOMS_AWARE));exit;
        $baseNomsAware = $this->reader->getPropertyAnnotation(
                //$targetEntity->getReflectionProperty('type'),
                $targetEntity->getProperty('type'),
                BASE_NOMS_AWARE
        );
        //var_dump($baseNomsAware);exit;
        if (!$userAware)
        {
            return '';
        }

        return $this->buildQuery($targetEntity, $targetTableAlias, $userAware);
    }
    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
    }
    // The EntityManager (em) in the parent is private,
    // we have to add it to this class another way :-(
    public function setObjectManager(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
}
