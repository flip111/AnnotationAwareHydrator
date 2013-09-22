#Annotation Aware Hydrator
A hydrator/extract for php objects to arrays that uses filters and modifiers through annotations.

##What?
Converting arrays to objects (hydrating) and objects to arrays (extracting).
While selecting only the properties/values you want, ignoring the rest.

##Why?
Because it's nice to keep this information with the object instead of writing that logic somewhere in a service. Like Doctrine uses @Column to indicate a property has to be written to the database. This library uses @Extract and @Hydrate to indicate you want the property hydrated or extracted.

##How?

###Simple
Add the annotations to your object properties.

```
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

Extracting
```
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

Hydrating
```
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