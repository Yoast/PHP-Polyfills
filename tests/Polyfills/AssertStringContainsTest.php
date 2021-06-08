<?php

namespace Yoast\PHPUnitPolyfills\Tests\Polyfills;

use PHPUnit\Framework\TestCase;
use Yoast\PHPUnitPolyfills\Polyfills\AssertStringContains;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectException;

/**
 * Availability test for the functions polyfilled by the AssertStringContains trait.
 *
 * @covers \Yoast\PHPUnitPolyfills\Polyfills\AssertStringContains
 */
class AssertStringContainsTest extends TestCase {

	use AssertStringContains;
	use ExpectException; // Needed for PHPUnit < 5.2.0 support.

	/**
	 * Verify availability of the assertStringContainsString() method.
	 *
	 * @return void
	 */
	public function testAssertStringContainsString() {
		$this->assertStringContainsString( 'foo', 'foobar' );
	}

	/**
	 * Verify availability of the assertStringContainsStringIgnoringCase() method.
	 *
	 * @return void
	 */
	public function testAssertStringContainsStringIgnoringCase() {
		self::assertStringContainsStringIgnoringCase( 'Foo', 'foobar' );
	}

	/**
	 * Verify availability of the assertStringNotContainsString() method.
	 *
	 * @return void
	 */
	public function testAssertStringNotContainsString() {
		self::assertStringNotContainsString( 'Foo', 'foobar' );
	}

	/**
	 * Verify availability of the assertStringNotContainsStringIgnoringCase() method.
	 *
	 * @return void
	 */
	public function testAssertStringNotContainsStringIgnoringCase() {
		$this->assertStringNotContainsStringIgnoringCase( 'Baz', 'foobar' );
	}

	/**
	 * Verify that the assertStringContainsString() method does not throw a mb_strpos()
	 * PHP error when passed an empty $needle.
	 *
	 * This was possible due to a bug which existed in PHPUnit < 6.4.2.
	 *
	 * @link https://github.com/Yoast/PHPUnit-Polyfills/issues/17
	 * @link https://github.com/sebastianbergmann/phpunit/pull/2778/
	 *
	 * @dataProvider dataHaystacks
	 *
	 * @param string $haystack Haystack.
	 *
	 * @return void
	 */
	public function testAssertStringContainsStringEmptyNeedle( $haystack ) {
		$this->assertStringContainsString( '', $haystack );
	}

	/**
	 * Verify that the assertStringContainsStringIgnoringCase() method does not throw
	 * a mb_strpos() PHP error when passed an empty $needle.
	 *
	 * This was possible due to a bug which existed in PHPUnit < 6.4.2.
	 *
	 * @link https://github.com/Yoast/PHPUnit-Polyfills/issues/17
	 * @link https://github.com/sebastianbergmann/phpunit/pull/2778/
	 *
	 * @return void
	 */
	public function testAssertStringContainsStringIgnoringCaseEmptyNeedle() {
		self::assertStringContainsStringIgnoringCase( '', 'foobar' );
	}

	/**
	 * Verify that the assertStringNotContainsString() method does not throw a mb_strpos()
	 * PHP error when passed an empty $needle.
	 *
	 * This was possible due to a bug which existed in PHPUnit < 6.4.2.
	 *
	 * @link https://github.com/Yoast/PHPUnit-Polyfills/issues/17
	 * @link https://github.com/sebastianbergmann/phpunit/pull/2778/
	 *
	 * @dataProvider dataHaystacks
	 *
	 * @param string $haystack Haystack.
	 *
	 * @return void
	 */
	public function testAssertStringNotContainsStringEmptyNeedle( $haystack ) {
		$msg       = "Failed asserting that '{$haystack}' does not contain \"\".";
		$exception = 'PHPUnit\Framework\AssertionFailedError';
		if ( \class_exists( 'PHPUnit_Framework_AssertionFailedError' ) ) {
			// PHPUnit < 6.
			$exception = 'PHPUnit_Framework_AssertionFailedError';
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		self::assertStringNotContainsString( '', $haystack );
	}

	/**
	 * Verify that the assertStringNotContainsStringIgnoringCase() method does not throw a mb_strpos()
	 * PHP error when passed an empty $needle.
	 *
	 * This was possible due to a bug which existed in PHPUnit < 6.4.2.
	 *
	 * @link https://github.com/Yoast/PHPUnit-Polyfills/issues/17
	 * @link https://github.com/sebastianbergmann/phpunit/pull/2778/
	 *
	 * @return void
	 */
	public function testAssertStringNotContainsStringIgnoringCaseEmptyNeedle() {
		$msg       = 'Failed asserting that \'text with whitespace\' does not contain "".';
		$exception = 'PHPUnit\Framework\AssertionFailedError';
		if ( \class_exists( 'PHPUnit_Framework_AssertionFailedError' ) ) {
			// PHPUnit < 6.
			$exception = 'PHPUnit_Framework_AssertionFailedError';
		}

		$this->expectException( $exception );
		$this->expectExceptionMessage( $msg );

		$this->assertStringNotContainsStringIgnoringCase( '', 'text with whitespace' );
	}

	/**
	 * Data provider.
	 *
	 * @see testAssertStringContainsStringEmptyNeedle()    For the array format.
	 * @see testAssertStringNotContainsStringEmptyNeedle() For the array format.
	 *
	 * @return array
	 */
	public function dataHaystacks() {
		return [
			'foobar as haystack' => [ 'foobar' ],
			'empty haystack'     => [ '' ],
		];
	}
}
