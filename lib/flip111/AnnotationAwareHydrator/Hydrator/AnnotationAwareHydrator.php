<?php
namespace flip111\AnnotationAwareHydrator\Hydrator;

use ArrayObject;
use ReflectionClass;
use Doctrine\Common\Annotations\AnnotationReader;
use flip111\AnnotationAwareHydrator\Annotation\Extract;
use flip111\AnnotationAwareHydrator\Annotation\Hydrate;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Copied most of this stuff from AbstractHydrator.php and Reflection.php
 * 
 * To-Do:
 * 1) Integrate with https://github.com/Ocramius/GeneratedHydrator
 * 2) Wait for Zend Framework 3 to have better FilterInterfaces, see https://github.com/zendframework/zf2/issues/5099
 * 3) Use annotation caching
 *    - Using the native Doctrine implemtation (prefered)
 *    - possibly use a technique like https://github.com/zendframework/zf2/blob/master/library/Zend/Stdlib/Hydrator/Filter/OptionalParametersFilter.php#L28
 * 4) Check if the original object can be extended and the AnnotationAwareHydrator still works
 */
class AnnotationAwareHydrator implements HydratorInterface {
    /**
     * The list with strategies that this hydrator has.
     * 
     * @var ArrayObject
     */
    protected $strategies;
  
    /**
     * Simple in-memory array cache of ReflectionProperties used.
     * 
     * @var array
     */
    protected static $reflProperties = array();
    
    /**
     * Not sure if i'm doing a good job with implementing this static !!
     * 
     * @var type 
     */
    protected static $annotationReader;
    
    /**
     * http://shellrock.files.wordpress.com/2009/11/ist2_5339150-cartoon-construction-worker.jpg
     */
    public function __construct() {
      $this->strategies = new ArrayObject();
      
      if (! (static::$annotationReader instanceof AnnotationReader)) {
        static::$annotationReader = new AnnotationReader();
      }
      
      // Possible cache the annotations themself ? with Doctrine cache or
      // C:\xampp\htdocs\Timey23\vendor\zendframework\zend-stdlib\Zend\Stdlib\Hydrator\Filter\OptionalParametersFilter.php
      // like properties cache ...
    }
    
    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
echo 'Hydrator -> Extract<br />';
        $result = array();
        foreach (self::getReflProperties($object) as $property) {
            $annotations = static::$annotationReader->getPropertyAnnotations($property);
            $propertyName = $property->getName();
            $value = $property->getValue($object);
            printf('Property: '.$propertyName."<br />\n");
            $skip = true;
            foreach ($annotations as $a) {
              if ($a instanceof Extract) {
                var_dump($a);
                $preFilters = $a->preFilters;
                $postFilters = $a->postFilters;
                $strategy = $a->modifier;
                echo 'Strategy:';
                var_dump($strategy);
                $skip = false;
                break; // Only allow the first Extract annotation
              }
            }
            if ($skip) continue;
            
            // Applying PreFilters
            if (isset($preFilters)) {
              foreach ($preFilters as $filter) {
                if ($filter->filter($value) !== true) continue 2;
              }
            }
            
            // Applying Conversion by using Strategy
            if (isset($strategy)) {
              $value = $strategy->extract($value);
            }

            // Applying PostFilters
            if (isset($postFilters)) {
              foreach ($postFilters as $filter) {
                if ($filter->filter($value) !== true) continue 2;
              }
            }
            
            $result[$propertyName] = $value;
        }

        return $result;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
echo 'Hydrator -> Hydrate<br />';
        $reflProperties = self::getReflProperties($object);
        foreach ($data as $key => $value) {
            if (isset($reflProperties[$key])) {
                $annotations = static::$annotationReader->getPropertyAnnotations($reflProperties[$key]);
              
                $skip = true;
                foreach ($annotations as $a) {
                  if ($a instanceof Hydrate) {
                    $preFilters = $a->preFilters;
                    $postFilters = $a->postFilters;
                    $strategy = $a->modifier;
                    $skip = false;
                    break; // Only allow the first Hydrate annotation
                  }
                }
                if ($skip) continue;

                // Applying PreFilters
                if (isset($preFilters)) {
                  foreach ($preFilters as $filter) {
                    if ($filter->filter($value) !== true) continue 2;
                  }
                }

                // Applying Conversion by using Strategy
                if (isset($strategy)) {
                  $value = $strategy->hydrate($value);
                }

                // Applying PostFilters
                if (isset($postFilters)) {
                  foreach ($postFilters as $filter) {
                    if ($filter->filter($value) !== true) continue 2;
                  }
                }
                
                $reflProperties[$key]->setValue($object, $value);
            }
        }
        return $object;
    }
    
    /**
     * Get a reflection properties from in-memory cache and lazy-load if
     * class has not been loaded.
     *
     * @param  string|object $input
     * @throws Exception\InvalidArgumentException
     * @return array
     */
    protected static function getReflProperties($input)
    {
        if (is_object($input)) {
            $input = get_class($input);
        } elseif (!is_string($input)) {
            throw new Exception\InvalidArgumentException('Input must be a string or an object.');
        }

        if (isset(static::$reflProperties[$input])) {
            return static::$reflProperties[$input];
        }

        static::$reflProperties[$input] = array();
        $reflClass                      = new ReflectionClass($input);
        $reflProperties                 = $reflClass->getProperties();

        foreach ($reflProperties as $property) {
            $property->setAccessible(true);
            static::$reflProperties[$input][$property->getName()] = $property;
        }

        return static::$reflProperties[$input];
    }
}