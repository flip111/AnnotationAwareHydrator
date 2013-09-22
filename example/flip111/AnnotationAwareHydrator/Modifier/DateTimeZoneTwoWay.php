<?php
namespace flip111\AnnotationAwareHydrator\Modifier;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class DateTimeZoneTwoWay extends AbstractTwoWayModifier {
  public function extract($datetimezone, $object) {
    return $datetimezone->getName();
  }
  
  public function hydrate($timezonestring, Array $array) {
    return new \DateTimeZone($timezonestring);
  }
}