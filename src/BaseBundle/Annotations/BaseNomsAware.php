<?php
// based on gist: https://gist.github.com/technetium/0c62164400a411e9ffc3713260448b25
// which is based on http://blog.michaelperrin.fr/2014/12/05/doctrine-filters/
namespace BaseBundle\Annotations;
use Doctrine\Common\Annotations\Annotation;
/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class BaseNomsAware
{
    /** 
     * @Required
     * @var string
     */
    public $nomType;
}