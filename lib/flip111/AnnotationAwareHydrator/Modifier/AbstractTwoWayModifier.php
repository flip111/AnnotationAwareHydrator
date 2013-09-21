<?php
namespace flip111\AnnotationAwareHydrator\Modifier;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

abstract class AbstractTwoWayModifier implements StrategyInterface {
  abstract public function extract($value);
  
  abstract public function hydrate($value);
}