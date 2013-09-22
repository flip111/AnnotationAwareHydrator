<?php
namespace flip111\AnnotationAwareHydrator\Modifier;

abstract class AbstractOneWayModifier {
  abstract public function modify($value);
  
  public function extract($value, $object) {
    return $this->modify($value);
  }
  
  public function hydrate($value, Array $array) {
     return $this->modify($value);
  }
}