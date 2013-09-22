<?php
namespace flip111\AnnotationAwareHydrator\Modifier;

abstract class AbstractTwoWayModifier {
  abstract public function extract($value, $object);
  
  abstract public function hydrate($value, Array $array);
}