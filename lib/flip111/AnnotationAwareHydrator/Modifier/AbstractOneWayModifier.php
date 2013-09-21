<?php
namespace flip111\AnnotationAwareHydrator\Modifier;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

abstract class AbstractOneWayModifier implements StrategyInterface {
  abstract public function modify($value);
  
  public function extract($value) {
    return $this->modify($value);
  }
  
  public function hydrate($value) {
     return $this->modify($value);
  }
}