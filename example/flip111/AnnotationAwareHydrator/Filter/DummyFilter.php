<?php
namespace flip111\AnnotationAwareHydrator\Filter;

use Zend\Stdlib\Hydrator\Filter\FilterInterface;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class DummyFilter implements FilterInterface {
  public function filter($value) {
    return true;
  }
}