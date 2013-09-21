<?php
namespace flip111\AnnotationAwareHydrator\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Extract { // ExtractAnnotation
  public $preFilters;
  public $postFilters;
  public $modifier;
}