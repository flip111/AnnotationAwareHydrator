<?php
namespace flip111\AnnotationAwareHydrator\Object;

use flip111\AnnotationAwareHydrator\Annotation\Hydrate;
use flip111\AnnotationAwareHydrator\Annotation\Extract;
use flip111\AnnotationAwareHydrator\Filter\DummyFilter;
use flip111\AnnotationAwareHydrator\Modifier\DateTimeZoneTwoWay;
use flip111\AnnotationAwareHydrator\Modifier\DateTimeZoneOneWayE;
use flip111\AnnotationAwareHydrator\Modifier\DateTimeZoneOneWayH;

/**
 * @author Me
 */
class Test {
  /**
   * @Extract(preFilters={@DummyFilter})
   * @Hydrate
   */
  protected $name;
  
  /**
   * @Extract(modifier=@DateTimeZoneTwoWay)
   * @Hydrate(modifier=@DateTimeZoneTwoWay)
   */
  protected $datetimezone1;
  
  /**
   * @Extract(modifier=@DateTimeZoneOneWayE)
   * @Hydrate(modifier=@DateTimeZoneOneWayH)
   */
  protected $datetimezone2;
  
  public function __construct() {
    $this->datetimezone1 =  new \DateTimeZone('Europe/Amsterdam');
    $this->datetimezone2 =  new \DateTimeZone('Asia/Seoul');
  }
  
  public function setName($name) {
    $this->name = $name;
    return $this;
  }
  
  public function getName() {
    return $this->name;
  }
  
  public function setTimeZone1($datetimezone) {
    $this->datetimezone1 = $datetimezone;
    return $this;
  }
  
  public function getTimeZone1() {
    return $this->datetimezone1;
  }
  
  public function setTimeZone2($datetimezone) {
    $this->datetimezone2 = $datetimezone;
    return $this;
  }
  
  public function getTimeZone2() {
    return $this->datetimezone2;
  }
}