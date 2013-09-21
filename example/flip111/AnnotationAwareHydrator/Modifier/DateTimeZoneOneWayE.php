<?php
namespace flip111\AnnotationAwareHydrator\Modifier;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class DateTimeZoneOneWayE extends AbstractOneWayModifier {
  public function modify($datetimezone) {
    return $datetimezone->getName();
  }
}