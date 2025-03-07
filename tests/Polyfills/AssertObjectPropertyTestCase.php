<?php

namespace Yoast\PHPUnitPolyfills\Tests\Polyfills;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Version as PHPUnit_Version;
use stdClass;
use TypeError;
use Yoast\PHPUnitPolyfills\Polyfills\AssertObjectProperty;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectExceptionMessageMatches;
use Yoast\PHPUnitPolyfills\Tests\Polyfills\Fixtures\ObjectWithProperties;

/**
 * Test for the functions polyfilled by the AssertObjectProperty trait.
 *
 * The majority of these tests test the polyfill which kicks in for PHPUnit 9.6.1 < 9.6.11 and PHPUnit 10.0.0 < 10.1.0.
 */
abstract class AssertObjectPropertyTestCase extends TestCase {

	use AssertObjectProperty;
	use ExpectExceptionMessageMatches;

	/**
	 * Check whether native PHPUnit assertions will be used or the polyfill.
	 *
	 * @return bool
	 */
	private function usesNativePHPUnitAssertion() {
		$phpunit_version = PHPUnit_Version::id();
		return ( \version_compare( $phpunit_version, '10.1.0', '>=' )
			|| ( \version_compare( $phpunit_version, '9.6.11', '>=' ) && \version_compare( $phpunit_version, '10.0.0', '<' ) )
		);
	}

	/**
	 * Verify that the assertObjectHasProperty() method throws an error when the $propertyName parameter is not a scalar.
	 *
	 * @dataProvider dataAssertObjectPropertyFailsOnInvalidInputTypePropertyName
	 *
	 * @param mixed $input Non-scalar value.
	 *
	 * @return void
	 */
	#[DataProvider( 'dataAssertObjectPropertyFailsOnInvalidInputTypePropertyName' )]
	public function testAssertObjectHasPropertyFailsOnInvalidInputTypePropertyName( $input ) {
		if ( \is_scalar( $input ) && $this->usesNativePHPUnitAssertion() ) {
			$this->markTestSkipped( 'PHPUnit native implementation relies on strict_types and when not used will accept scalar inputs' );
		}

		$this->expectException( TypeError::class );

		if ( \PHP_VERSION_ID >= 80000 && $this->usesNativePHPUnitAssertion() ) {
			$msg = 'assertObjectHasProperty(): Argument #1 ($propertyName) must be of type string, ';
			$this->expectExceptionMessage( $msg );
		}
		else {
			// PHP 7.
			$pattern = '`^Argument 1 passed to [^\s]*assertObjectHasProperty\(\) must be of (the )?type string, `';
			$this->expectExceptionMessageMatches( $pattern );
		}

		$this->assertObjectHasProperty( $input, new stdClass() );
	}

	/**
	 * Verify that the assertObjectNotHasProperty() method throws an error when the $propertyName parameter is not a scalar.
	 *
	 * @dataProvider dataAssertObjectPropertyFailsOnInvalidInputTypePropertyName
	 *
	 * @param mixed $input Non-scalar value.
	 *
	 * @return void
	 */
	#[DataProvider( 'dataAssertObjectPropertyFailsOnInvalidInputTypePropertyName' )]
	public function testAssertObjectNotHasPropertyFailsOnInvalidInputTypePropertyName( $input ) {
		if ( \is_scalar( $input ) && $this->usesNativePHPUnitAssertion() ) {
			$this->markTestSkipped( 'PHPUnit native implementation relies on strict_types and when not used will accept scalar inputs' );
		}

		$this->expectException( TypeError::class );

		if ( \PHP_VERSION_ID >= 80000 && $this->usesNativePHPUnitAssertion() ) {
			$msg = 'assertObjectNotHasProperty(): Argument #1 ($propertyName) must be of type string, ';
			$this->expectExceptionMessage( $msg );
		}
		else {
			// PHP 7.
			$pattern = '`^Argument 1 passed to [^\s]*assertObjectNotHasProperty\(\) must be of (the )?type string, `';
			$this->expectExceptionMessageMatches( $pattern );
		}

		$this->assertObjectNotHasProperty( $input, new stdClass() );
	}

	/**
	 * Data provider.
	 *
	 * @return array<string, array<mixed>>
	 */
	public static function dataAssertObjectPropertyFailsOnInvalidInputTypePropertyName() {
		// Only testing closed resource to not leak an open resource.
		$resource = \fopen( __DIR__ . '/Fixtures/test.txt', 'r' );
		\fclose( $resource );

		return [
			'null'            => [ null ],
			'boolean'         => [ true ],
			'integer'         => [ 10 ],
			'float'           => [ 5.34 ],
			'array'           => [ [ 1, 2, 3 ] ],
			'object'          => [ new stdClass() ],
			'closed resource' => [ $resource ],
		];
	}

	/**
	 * Verify that the assertObjectHasProperty() method throws an error when the $object parameter is not an object.
	 *
	 * @dataProvider dataAssertObjectPropertyFailsOnInvalidInputTypeObject
	 *
	 * @param mixed $input Non-object value.
	 *
	 * @return void
	 */
	#[DataProvider( 'dataAssertObjectPropertyFailsOnInvalidInputTypeObject' )]
	public function testAssertObjectHasPropertyFailsOnInvalidInputTypeObject( $input ) {
		$this->expectException( TypeError::class );

		if ( \PHP_VERSION_ID >= 80000 && $this->usesNativePHPUnitAssertion() ) {
			$msg = 'assertObjectHasProperty(): Argument #2 ($object) must be of type object, ';
			$this->expectExceptionMessage( $msg );
		}
		else {
			// PHP 7.
			$pattern = '`^Argument 2 passed to [^\s]*assertObjectHasProperty\(\) must be (of type|an) object, `';
			$this->expectExceptionMessageMatches( $pattern );
		}

		$this->assertObjectHasProperty( 'propertyName', $input );
	}

	/**
	 * Verify that the assertObjectNotHasProperty() method throws an error when the $object parameter is not an object.
	 *
	 * @dataProvider dataAssertObjectPropertyFailsOnInvalidInputTypeObject
	 *
	 * @param mixed $input Non-object value.
	 *
	 * @return void
	 */
	#[DataProvider( 'dataAssertObjectPropertyFailsOnInvalidInputTypeObject' )]
	public function testAssertObjectNotHasPropertyFailsOnInvalidInputTypeObject( $input ) {
		$this->expectException( TypeError::class );

		if ( \PHP_VERSION_ID >= 80000 && $this->usesNativePHPUnitAssertion() ) {
			$msg = 'assertObjectNotHasProperty(): Argument #2 ($object) must be of type object, ';
			$this->expectExceptionMessage( $msg );
		}
		else {
			// PHP 7.
			$pattern = '`^Argument 2 passed to [^\s]*assertObjectNotHasProperty\(\) must be (of type|an) object, `';
			$this->expectExceptionMessageMatches( $pattern );
		}

		static::assertObjectNotHasProperty( 'propertyName', $input );
	}

	/**
	 * Data provider.
	 *
	 * @return array<string, array<mixed>>
	 */
	public static function dataAssertObjectPropertyFailsOnInvalidInputTypeObject() {
		// Only testing closed resource to not leak an open resource.
		$resource = \fopen( __DIR__ . '/Fixtures/test.txt', 'r' );
		\fclose( $resource );

		return [
			'null'            => [ null ],
			'boolean'         => [ true ],
			'integer'         => [ 10 ],
			'float'           => [ 5.34 ],
			'string'          => [ 'text' ],
			'array'           => [ [ 1, 2, 3 ] ],
			'closed resource' => [ $resource ],
		];
	}

	/**
	 * Verify availability and functionality of the assertObjectHasProperty() method.
	 *
	 * @dataProvider dataAssertObjectPropertyDeclaredProps
	 *
	 * @param string $name The property name to look for.
	 *
	 * @return void
	 */
	#[DataProvider( 'dataAssertObjectPropertyDeclaredProps' )]
	public function testAssertObjectHasPropertyPass( $name ) {
		$this->assertObjectHasProperty( $name, new ObjectWithProperties() );
	}

	/**
	 * Verify availability and functionality of the assertObjectNotHasProperty() method.
	 *
	 * @dataProvider dataAssertObjectPropertyUnavailableProps
	 *
	 * @param string $name The property name to look for.
	 *
	 * @return void
	 */
	#[DataProvider( 'dataAssertObjectPropertyUnavailableProps' )]
	public function testAssertObjectNotHasPropertyPass( $name ) {
		self::assertObjectNotHasProperty( $name, new ObjectWithProperties() );
	}

	/**
	 * Verify that the assertObjectHasProperty() method throws an error when the property does not exist on the object.
	 *
	 * @dataProvider dataAssertObjectPropertyUnavailableProps
	 *
	 * @param string $name The property name to look for.
	 *
	 * @return void
	 */
	#[DataProvider( 'dataAssertObjectPropertyUnavailableProps' )]
	public function testAssertObjectHasPropertyFails( $name ) {
		$pattern = \sprintf(
			'`^Failed asserting that object of class "[^\s]*ObjectWithProperties" has (?:property|attribute) "%s"\.`',
			\preg_quote( $name, '`' )
		);

		$this->expectException( AssertionFailedError::class );
		$this->expectExceptionMessageMatches( $pattern );

		static::assertObjectHasProperty( $name, new ObjectWithProperties() );
	}

	/**
	 * Verify that the assertObjectNotHasProperty() method throws an error when the property does exist on the object.
	 *
	 * @dataProvider dataAssertObjectPropertyDeclaredProps
	 *
	 * @param string $name The property name to look for.
	 *
	 * @return void
	 */
	#[DataProvider( 'dataAssertObjectPropertyDeclaredProps' )]
	public function testAssertObjectNotHasPropertyFails( $name ) {
		$pattern = \sprintf(
			'`^Failed asserting that object of class "[^\s]*ObjectWithProperties" does not have (?:property|attribute) "%s"\.`',
			\preg_quote( $name, '`' )
		);

		$this->expectException( AssertionFailedError::class );
		$this->expectExceptionMessageMatches( $pattern );

		$this->assertObjectNotHasProperty( $name, new ObjectWithProperties() );
	}

	/**
	 * Data provider.
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataAssertObjectPropertyDeclaredProps() {
		return [
			'declared public property without default'    => [ 'publicNoDefaultValue' ],
			'declared protected property without default' => [ 'protectedNoDefaultValue' ],
			'declared private property without default'   => [ 'privateNoDefaultValue' ],
			'declared public property with default'       => [ 'publicWithDefaultValue' ],
			'declared protected property with default'    => [ 'protectedWithDefaultValue' ],
			'declared private property with default'      => [ 'privateWithDefaultValue' ],
			'unset declared public property'              => [ 'unsetPublic' ],
			'unset declared protected property'           => [ 'unsetProtected' ],
			'unset declared private property'             => [ 'unsetPrivate' ],
		];
	}

	/**
	 * Data provider.
	 *
	 * @return array<string, array<string>>
	 */
	public static function dataAssertObjectPropertyUnavailableProps() {
		return [
			'property which is not declared' => [ 'doesNotExist' ],
		];
	}

	/**
	 * Verify that the assertObjectHasProperty() method fails a test with a custom failure message,
	 * when the custom $message parameter has been passed.
	 *
	 * @return void
	 */
	public function testAssertObjectHasPropertyFailsWithCustomMessage() {
		$pattern = '`^This assertion failed for reason XYZ\s+Failed asserting that object of class `';

		$this->expectException( AssertionFailedError::class );
		$this->expectExceptionMessageMatches( $pattern );

		$this->assertObjectHasProperty( 'doesNotExist', new ObjectWithProperties(), 'This assertion failed for reason XYZ' );
	}

	/**
	 * Verify that the assertObjectNotHasProperty() method fails a test with a custom failure message,
	 * when the custom $message parameter has been passed.
	 *
	 * @return void
	 */
	public function testAssertObjectNotHasPropertyFailsWithCustomMessage() {
		$pattern = '`^This assertion failed for reason XYZ\s+Failed asserting that object of class `';

		$this->expectException( AssertionFailedError::class );
		$this->expectExceptionMessageMatches( $pattern );

		$this->assertObjectNotHasProperty( 'protectedWithDefaultValue', new ObjectWithProperties(), 'This assertion failed for reason XYZ' );
	}
}
