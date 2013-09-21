<?php
namespace flip111\AnnotationAwareHydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

class DateTimeZoneStrategy implements StrategyInterface {
  public function extract($value) {
    return $value->getName();
  }
  
  public function hydrate($value) {
    return new \DateTimeZone($value);
  }
}