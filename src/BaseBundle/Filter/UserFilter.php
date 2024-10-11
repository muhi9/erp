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
use BaseBundle\Annotations\UserAware as UserAware;
define('USER_ENTITY', 'UsersBundle\Entity\User');
define('USER_AWARE', 'BaseBundle\\Annotations\\Userware');
class UserFilter extends SQLFilter
{
    public $reader; // The annotation reader
    public $manager; // The entity manager
    private function getPropertyAndFieldName(
            ClassMetadata $targetEntity,
            UserAware $userAware)
    {
        $propertyName = $userAware->userPropertyName;
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
            $fieldName = $userAware->userFieldName;
            $propertyName = $targetEntity->getFieldForColumn($fieldName);
        }
        return array($propertyName, $fieldName);
    }
    private function buildBasicQuery($targetTableAlias, $fieldName)
    {
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
    private function buildQuery(ClassMetadata $targetEntity, $targetTableAlias, UserAware $userAware)
    {
        list($propertyName, $fieldName) =
            $this->getPropertyAndFieldName($targetEntity, $userAware);
        $targetClassName =
            $targetEntity->hasAssociation($propertyName) ?
            $targetEntity->getAssociationTargetClass($propertyName) :
            USER_ENTITY;
        if (USER_ENTITY === $targetClassName)
        {
           return $this->buildBasicQuery($targetTableAlias, $fieldName);
        }
        $reflClass = new \ReflectionClass($targetClassName);
        $userAware = $this->reader->getClassAnnotation($reflClass, USER_AWARE);
        if (!$userAware)
        {
            throw new \Exception('Association of UserAware entity isn\'t UserAware Exception');
        }
        $classMetaData = $this->manager->getClassMetadata($targetClassName);
        $tableName = $classMetaData->getTableName();
        $identifier = $classMetaData->getSingleIdentifierFieldName();
        $query =
            sprintf('(%s.%s IN (SELECT %s FROM %s WHERE ', $targetTableAlias, $fieldName, $identifier, $tableName) .
            $this->buildQuery($classMetaData, $tableName, $userAware) . '))';
        return $query;
    }
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$this->reader)
        {
            return '';
        }
        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "user aware" (marked with an annotation)
        $userAware = $this->reader->getClassAnnotation(
                $targetEntity->getReflectionClass(),
                USER_AWARE
        );
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