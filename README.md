#Annotation Aware Hydrator
A hydrator/extract for php objects to arrays that uses filters and modifiers through annotations.

##What?
Converting arrays to objects (hydrating) and objects to arrays (extracting).
While selecting only the properties/values you want, ignoring the rest.

##Why?
Because it's nice to keep this information with the object instead of writing that logic somewhere in a service. Like Doctrine uses @Column to indicate a property has to be written to the database. This library uses @Extract and @Hydrate to indicate you want the property hydrated or extracted.

##How?

Add the annotations to your object properties.

```php
<?php
namespace My\Nice\Name\space;

use flip111\AnnotationAwareHydrator\Annotation\Hydrate;
use flip111\AnnotationAwareHydrator\Annotation\Extract;

class MyObject {
	/**
	 * @Extract
	 */
	public $prop1;

	/**
	 * @Hydrate
	 */
	public $prop2;
}
```

###Extracting
```php
<?php
namespace My\Nice\Name\space;

use flip111\AnnotationAwareHydrator\Hydrator\AnnotationAwareHydrator;

$hydrator = new AnnotationAwareHydrator();

$original = new MyObject;
$original->prop1 = 'First';
$original->prop2 = 'Second';

$array = $hydrator->extract($original);

var_dump($array);
```
Output:
```
array (size=1)
  'prop1' => string 'First' (length=5)
```

###Hydrating
```php
<?php
namespace My\Nice\Name\space;

use flip111\AnnotationAwareHydrator\Hydrator\AnnotationAwareHydrator;

$hydrator = new AnnotationAwareHydrator();

$array2 = [
	'prop1' => 'First',
	'prop2' => 'Second'
];

// You can also pass in the $original instead of new MyObject
// this is just for reading the annotations.
$object = $hydrator->hydrate($array2, new MyObject);

var_dump($object);
```

Output:
```
object(My\Nice\Name\space\MyObject)[983]
  public 'prop1' => null
  public 'prop2' => string 'Second' (length=6)
```

##Features

###1. Uses reflection so you can use protected and private properties.

###2. Allows you to modify the value while hydrating/extracting
Example object:
```php
<?php
namespace My\Nice\Name\space;

use flip111\AnnotationAwareHydrator\Annotation\Extract;
use My\Nice\Name\space\Modifier\PastryModifier;

class MyObject {
	/**
	 * @Extract(modifier=@PastryModifier)
	 */
	public $prop1;
}
```

You will have to write your own modifier, which is very easy. There are two kinds of modifiers. One that defines both hydrating and extracting, useful for back and forth conversions (see AbstractTwoWayModifier in the example folder). And one that only defines on modifying action, of which there will be an example below.
```php
<?php
namespace My\Nice\Name\space\annotation;

use flip111\AnnotationAwareHydrator\Modifier\AbstractOneWayModifier;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class PastryModifier extends AbstractOneWayModifier {
  public function modify($value) {
    return str_replace('cake', 'cookie', $value);
  }
}
```

Example:
```php
<?php
$object->prop1 = 'Peter Griffin ate a cake.';
$array = $hydrator->extract($object);
```
Result:
```
$array == [
	'prop1' => 'Peter Griffin ate a cookie.'
]
```

###3. Filter out values.
The preFilters value is used for applying filters before the modifier. postFilters for after. This is especially useful when changing data type.
Example object:
```php
<?php
namespace My\Nice\Name\space;

use flip111\AnnotationAwareHydrator\Annotation\Extract;
use My\Nice\Name\space\Modifier\PastryModifier;
use My\Nice\Name\space\Filter\PeterFilter;

class MyObject {
	/**
	 * @Extract(preFilters={@PeterFilter}, modifier=@PastryModifier)
	 */
	public $prop1;
	
	/**
	 * @Extract(preFilters={@PeterFilter}, modifier=@PastryModifier)
	 */
	public $prop2;
}
```

```php
<?php
namespace My\Nice\Name\space\Filter;

use Zend\Stdlib\Hydrator\Filter\FilterInterface;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class DummyFilter implements FilterInterface {
  public function filter($value) {
    return ! strpos($value, 'Peter'); // Filter out all Peter's !!
  }
}
```

Example:
```php
<?php
$object->prop1 = 'Peter Griffin ate a cake.';
$object->prop2 = 'And Stewie ate a cake too.';
$array = $hydrator->extract($object);
```
Result:
```
$array == [
	'prop2' => 'And Stewie ate a cookie too.'
]
```