<?php
namespace flip111\AnnotationAwareHydrator\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Hydrate {
  public $preFilters;
  public $postFilters;
  public $modifier;
}