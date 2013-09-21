<?php
namespace flip111\AnnotationAwareHydrator\Modifier;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class DateTimeZoneTwoWay extends AbstractTwoWayModifier {
  public function extract($datetimezone) {
    return $datetimezone->getName();
  }
  
  public function hydrate($timezonestring) {
    return new \DateTimeZone($timezonestring);
  }
}