<?php

namespace Yoast\PHPUnitPolyfills\Tests\Polyfills;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\RequiresPhp;
use PHPUnit\Framework\ComparisonMethodDoesNotAcceptParameterTypeException;
use PHPUnit\Framework\ComparisonMethodDoesNotDeclareBoolReturnTypeException;
use PHPUnit\Framework\ComparisonMethodDoesNotDeclareExactlyOneParameterException;
use PHPUnit\Framework\ComparisonMethodDoesNotDeclareParameterTypeException;
use PHPUnit\Framework\ComparisonMethodDoesNotExistException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Version as PHPUnit_Version;
use stdClass;
use TypeError;
use Yoast\PHPUnitPolyfills\Exceptions\InvalidComparisonMethodException;
use Yoast\PHPUnitPolyfills\Polyfills\AssertObjectEquals;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectExceptionMessageMatches;
use Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ChildValueObject;
use Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObject;
use Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObjectUnion;
use Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObjectUnionReturnType;

/**
 * Availability test for the function polyfilled by the AssertObjectEquals trait.
 */
abstract class AssertObjectEqualsTestCase extends TestCase {

	use AssertObjectEquals;
	use ExpectExceptionMessageMatches;

	/**
	 * The name of the "comparator method does not comply with requirements" exception as
	 * used by the polyfill.
	 *
	 * @var string
	 */
	private const COMPARATOR_EXCEPTION = InvalidComparisonMethodException::class;

	/**
	 * Verify availability of the assertObjectEquals() method.
	 *
	 * @return void
	 */
	public function testAssertObjectEquals() {
		$expected = new ValueObject( 'test' );
		$actual   = new ValueObject( 'test' );
		$this->assertObjectEquals( $expected, $actual );
	}

	/**
	 * Verify behaviour when passing the $method parameter.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsCustomMethodName() {
		$expected = new ValueObject( 'different name' );
		$actual   = new ValueObject( 'different name' );
		$this->assertObjectEquals( $expected, $actual, 'nonDefaultName' );
	}

	/**
	 * Verify behaviour when $expected is a child of $actual.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsExpectedChildOfActual() {
		$expected = new ChildValueObject( 'inheritance' );
		$actual   = new ValueObject( 'inheritance' );
		$this->assertObjectEquals( $expected, $actual );
	}

	/**
	 * Verify behaviour when $actual is a child of $expected.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsActualChildOfExpected() {
		$expected = new ValueObject( 'inheritance' );
		$actual   = new ChildValueObject( 'inheritance' );
		$this->assertObjectEquals( $expected, $actual );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the $expected parameter is not an object.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnExpectedNotObject() {
		$this->expectException( TypeError::class );

		if ( \PHP_VERSION_ID >= 80000
			&& \version_compare( PHPUnit_Version::id(), '9.4.0', '>=' )
		) {
			$msg = 'assertObjectEquals(): Argument #1 ($expected) must be of type object, string given';
			$this->expectExceptionMessage( $msg );
		}
		else {
			// PHP 7 or PHP 8 with the polyfill.
			$pattern = '`^Argument 1 passed to [^\s]*assertObjectEquals\(\) must be an object, string given`';
			$this->expectExceptionMessageMatches( $pattern );
		}

		$actual = new ValueObject( 'test' );
		$this->assertObjectEquals( 'className', $actual );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the $actual parameter is not an object.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnActualNotObject() {
		$this->expectException( TypeError::class );

		if ( \PHP_VERSION_ID >= 80000
			&& \version_compare( PHPUnit_Version::id(), '9.4.0', '>=' )
		) {
			$msg = 'assertObjectEquals(): Argument #2 ($actual) must be of type object, string given';
			$this->expectExceptionMessage( $msg );
		}
		else {
			// PHP 7.
			$pattern = '`^Argument 2 passed to [^\s]*assertObjectEquals\(\) must be an object, string given`';
			$this->expectExceptionMessageMatches( $pattern );
		}

		$expected = new ValueObject( 'test' );
		$this->assertObjectEquals( $expected, 'className' );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the $method parameter is not
	 * juggleable to a string.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnMethodNotJuggleableToString() {
		$this->expectException( TypeError::class );

		if ( \PHP_VERSION_ID >= 80000 ) {
			$msg = 'assertObjectEquals(): Argument #3 ($method) must be of type string, array given';
			$this->expectExceptionMessage( $msg );
		}
		else {
			// PHP 7.
			$pattern = '`^Argument 3 passed to [^\s]*assertObjectEquals\(\) must be of the type string, array given`';
			$this->expectExceptionMessageMatches( $pattern );
		}

		$expected = new ValueObject( 'test' );
		$actual   = new ValueObject( 'test' );
		$this->assertObjectEquals( $expected, $actual, [] );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the $actual object
	 * does not contain a method called $method.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnMethodNotDeclared() {
		$msg = 'Comparison method Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObject::doesNotExist() does not exist.';

		$exception = self::COMPARATOR_EXCEPTION;
		if ( \class_exists( ComparisonMethodDoesNotExistException::class ) ) {
			// PHPUnit > 9.4.0.
			$exception = ComparisonMethodDoesNotExistException::class;
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$expected = new ValueObject( 'test' );
		$actual   = new ValueObject( 'test' );
		$this->assertObjectEquals( $expected, $actual, 'doesNotExist' );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when no return type is declared.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnMissingReturnType() {
		$msg = 'Comparison method Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObject::equalsMissingReturnType() does not declare bool return type.';

		$exception = self::COMPARATOR_EXCEPTION;
		if ( \class_exists( ComparisonMethodDoesNotDeclareBoolReturnTypeException::class ) ) {
			// PHPUnit > 9.4.0.
			$exception = ComparisonMethodDoesNotDeclareBoolReturnTypeException::class;
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$expected = new ValueObject( 100 );
		$actual   = new ValueObject( 100 );
		$this->assertObjectEquals( $expected, $actual, 'equalsMissingReturnType' );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the declared return type in a union, intersection or DNF type.
	 *
	 * @requires PHP 8.0
	 *
	 * @return void
	 */
	#[RequiresPhp( '8.0' )]
	public function testAssertObjectEqualsFailsOnNonNamedTypeReturnType() {
		$msg = 'Comparison method Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObjectUnionReturnType::equalsUnionReturnType() does not declare bool return type.';

		$exception = self::COMPARATOR_EXCEPTION;
		if ( \class_exists( ComparisonMethodDoesNotDeclareBoolReturnTypeException::class ) ) {
			// PHPUnit > 9.4.0.
			$exception = ComparisonMethodDoesNotDeclareBoolReturnTypeException::class;
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$expected = new ValueObjectUnionReturnType( 100 );
		$actual   = new ValueObjectUnionReturnType( 100 );
		$this->assertObjectEquals( $expected, $actual, 'equalsUnionReturnType' );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the declared return type is nullable.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnNullableReturnType() {
		$msg = 'Comparison method Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObject::equalsNullableReturnType() does not declare bool return type.';

		$exception = self::COMPARATOR_EXCEPTION;
		if ( \class_exists( ComparisonMethodDoesNotDeclareBoolReturnTypeException::class ) ) {
			// PHPUnit > 9.4.0.
			$exception = ComparisonMethodDoesNotDeclareBoolReturnTypeException::class;
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$expected = new ValueObject( 100 );
		$actual   = new ValueObject( 100 );
		$this->assertObjectEquals( $expected, $actual, 'equalsNullableReturnType' );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the declared return type is not boolean.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnNonBooleanReturnType() {
		$msg = 'Comparison method Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObject::equalsNonBooleanReturnType() does not declare bool return type.';

		$exception = self::COMPARATOR_EXCEPTION;
		if ( \class_exists( ComparisonMethodDoesNotDeclareBoolReturnTypeException::class ) ) {
			// PHPUnit > 9.4.0.
			$exception = ComparisonMethodDoesNotDeclareBoolReturnTypeException::class;
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$expected = new ValueObject( 100 );
		$actual   = new ValueObject( 100 );
		$this->assertObjectEquals( $expected, $actual, 'equalsNonBooleanReturnType' );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the $method accepts more than one parameter.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnMethodAllowsForMoreParams() {
		$msg = 'Comparison method Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObject::equalsTwoParams() does not declare exactly one parameter.';

		$exception = self::COMPARATOR_EXCEPTION;
		if ( \class_exists( ComparisonMethodDoesNotDeclareExactlyOneParameterException::class ) ) {
			// PHPUnit > 9.4.0.
			$exception = ComparisonMethodDoesNotDeclareExactlyOneParameterException::class;
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$expected = new ValueObject( 'test' );
		$actual   = new ValueObject( 'test' );
		$this->assertObjectEquals( $expected, $actual, 'equalsTwoParams' );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the $method is not a required parameter.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnMethodParamNotRequired() {
		$msg = 'Comparison method Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObject::equalsParamNotRequired() does not declare exactly one parameter.';

		$exception = self::COMPARATOR_EXCEPTION;
		if ( \class_exists( ComparisonMethodDoesNotDeclareExactlyOneParameterException::class ) ) {
			// PHPUnit > 9.4.0.
			$exception = ComparisonMethodDoesNotDeclareExactlyOneParameterException::class;
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$expected = new ValueObject( 'test' );
		$actual   = new ValueObject( 'test' );
		$this->assertObjectEquals( $expected, $actual, 'equalsParamNotRequired' );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the $method parameter
	 * does not have a type declaration.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnMethodParamMissingTypeDeclaration() {
		$msg = 'Parameter of comparison method Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObject::equalsParamNoType() does not have a declared type.';

		$exception = self::COMPARATOR_EXCEPTION;
		if ( \class_exists( ComparisonMethodDoesNotDeclareParameterTypeException::class ) ) {
			// PHPUnit > 9.4.0.
			$exception = ComparisonMethodDoesNotDeclareParameterTypeException::class;
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$expected = new ValueObject( 'test' );
		$actual   = new ValueObject( 'test' );
		$this->assertObjectEquals( $expected, $actual, 'equalsParamNoType' );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the $method parameter
	 * has a PHP 8.0+ union type declaration.
	 *
	 * @requires PHP 8.0
	 *
	 * @return void
	 */
	#[RequiresPhp( '8.0' )]
	public function testAssertObjectEqualsFailsOnMethodParamHasUnionTypeDeclaration() {
		$msg = 'Parameter of comparison method Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObjectUnion::equalsParamUnionType() does not have a declared type.';

		$exception = self::COMPARATOR_EXCEPTION;
		if ( \class_exists( ComparisonMethodDoesNotDeclareParameterTypeException::class ) ) {
			// PHPUnit > 9.4.0.
			$exception = ComparisonMethodDoesNotDeclareParameterTypeException::class;
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$expected = new ValueObjectUnion( 'test' );
		$actual   = new ValueObjectUnion( 'test' );
		$this->assertObjectEquals( $expected, $actual, 'equalsParamUnionType' );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the $method parameter
	 * does not have a class-based type declaration.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnMethodParamNonClassTypeDeclaration() {
		$msg = 'is not an accepted argument type for comparison method Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObject::equalsParamNonClassType().';

		$exception = self::COMPARATOR_EXCEPTION;
		if ( \class_exists( ComparisonMethodDoesNotAcceptParameterTypeException::class ) ) {
			// PHPUnit > 9.4.0.
			$exception = ComparisonMethodDoesNotAcceptParameterTypeException::class;
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$expected = new ValueObject( 'test' );
		$actual   = new ValueObject( 'test' );
		$this->assertObjectEquals( $expected, $actual, 'equalsParamNonClassType' );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when the $method parameter
	 * has a class-based type declaration, but for a class which doesn't exist.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnMethodParamNonExistentClassTypeDeclaration() {
		$msg = 'is not an accepted argument type for comparison method Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObject::equalsParamNonExistentClassType().';

		$exception = self::COMPARATOR_EXCEPTION;
		if ( \class_exists( ComparisonMethodDoesNotAcceptParameterTypeException::class ) ) {
			// PHPUnit > 9.4.0.
			$exception = ComparisonMethodDoesNotAcceptParameterTypeException::class;
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$expected = new ValueObject( 'test' );
		$actual   = new ValueObject( 'test' );
		$this->assertObjectEquals( $expected, $actual, 'equalsParamNonExistentClassType' );
	}

	/**
	 * Verify that the assertObjectEquals() method throws an error when $expected is not
	 * an instance of the type declared for the $method parameter.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsOnMethodParamTypeMismatch() {
		$msg = 'is not an accepted argument type for comparison method Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ValueObject::equals().';

		$exception = self::COMPARATOR_EXCEPTION;
		if ( \class_exists( ComparisonMethodDoesNotAcceptParameterTypeException::class ) ) {
			// PHPUnit > 9.4.0.
			$exception = ComparisonMethodDoesNotAcceptParameterTypeException::class;
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$actual = new ValueObject( 'test' );
		$this->assertObjectEquals( new stdClass(), $actual );
	}

	/**
	 * Verify that the assertObjectEquals() method fails a test when a call to method
	 * determines that the objects are not equal.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsAsNotEqual() {
		$msg = 'Failed asserting that two objects are equal.';

		$this->expectException( AssertionFailedError::class );
		$this->expectExceptionMessage( $msg );

		$expected = new ValueObject( 'test' );
		$actual   = new ValueObject( 'testing... 1..2..3' );
		$this->assertObjectEquals( $expected, $actual );
	}

	/**
	 * Verify that the assertObjectEquals() method fails a test with a custom failure message, when a call
	 * to the method determines that the objects are not equal and the custom $message parameter has been passed.
	 *
	 * @return void
	 */
	public function testAssertObjectEqualsFailsAsNotEqualWithCustomMessage() {
		$pattern = '`^This assertion failed for reason XYZ\s+Failed asserting that two objects are equal\.`';

		$this->expectException( AssertionFailedError::class );
		$this->expectExceptionMessageMatches( $pattern );

		$expected = new ValueObject( 'test' );
		$actual   = new ValueObject( 'testing... 1..2..3' );
		$this->assertObjectEquals( $expected, $actual, 'equals', 'This assertion failed for reason XYZ' );
	}
}
