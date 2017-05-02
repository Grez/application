<?php

/**
 * Test: ComponentReflection annotation parser.
 */

use Nette\Application\UI\ComponentReflection as Reflection;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


/**
 * @title(value ="Johno's addendum", mode=True,) , out
 * @title
 * @title()
 * @publi - 'c' is missing intentionally
 * @privatex
 * @components(item 1)
 * @persistent(true)
 * @persistent(FALSE)
 * @persistent(null)
 * @author
 *@renderable
 * @secured(role = "admin", level = 2)
 * @Secured\User(loggedIn)
 */
class TestClass {

    /**
     * @title wat
     * @title(pokus,value ="Johno's addendum", mode=True,) , out
     */
    public function actionLogin()
    {
    }
}



$rc = new ReflectionClass('TestClass');

Assert::same(['value ="Johno\'s addendum"', 'mode=True', TRUE, TRUE], Reflection::parseAnnotation($rc, 'title'));
Assert::same(FALSE, Reflection::parseAnnotation($rc, 'public'));
Assert::same(FALSE, Reflection::parseAnnotation($rc, 'private'));
Assert::same(['item 1'], Reflection::parseAnnotation($rc, 'components'));
Assert::same([TRUE, FALSE, NULL], Reflection::parseAnnotation($rc, 'persistent'));
Assert::same([TRUE], Reflection::parseAnnotation($rc, 'renderable'));
Assert::same(['loggedIn'], Reflection::parseAnnotation($rc, 'Secured\User'));
Assert::false(Reflection::parseAnnotation($rc, 'missing'));

$ref = new Nette\Application\UI\ComponentReflection('TestClass');
print_r((array) $ref->getMethod('actionLogin')->getAnnotation('title'));
// Returns:
//   Array
//   (
//       [0] => mode=True
//   )

echo "\n\n";

$rc = new Nette\Reflection\ClassType('TestClass');
print_r((array) $rc->getMethod('actionLogin')->getAnnotation('title'));
// Returns:
//   Array
//   (
//       [0] => pokus
//       [value] => Johno's addendum
//       [mode] => 1
//   )
