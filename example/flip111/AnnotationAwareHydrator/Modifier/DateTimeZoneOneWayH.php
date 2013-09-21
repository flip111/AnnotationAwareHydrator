<?php
namespace flip111\AnnotationAwareHydrator\Modifier;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class DateTimeZoneOneWayH extends AbstractOneWayModifier {
  public function modify($timezonestring) {
    return new \DateTimeZone($timezonestring);
  }
}