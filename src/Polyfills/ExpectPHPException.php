<?php

namespace Yoast\PHPUnitPolyfills\Polyfills;

use PHPUnit\Framework\Error\Deprecated;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\Error\Notice;
use PHPUnit\Framework\Error\Warning;

/**
 * Polyfill the `TestCase::expectDeprecation*()`, `TestCase::expectNotice*()`,
 * `TestCase::expectWarning*()` and `TestCase::expectError*()` methods
 * as alternative to using `TestCase::expectException*()` for PHP native messages.
 *
 * Introduced in PHPUnit 8.4.0.
 * Use of `TestCase::expectException()` et al for expecting PHP native errors, warnings and notices was
 * soft deprecated in PHPUnit 8.4.0, hard deprecated in PHPUnit 9.0.0 and (will be) removed in PHPUnit 10.0.0.
 *
 * @link https://github.com/sebastianbergmann/phpunit/issues/3775
 * @link https://github.com/sebastianbergmann/phpunit/issues/3776
 * @link https://github.com/sebastianbergmann/phpunit/issues/3777
 */
trait ExpectPHPException {

	/**
	 * Set expectation for receiving a PHP native deprecation notice.
	 *
	 * @return void
	 */
	final public function expectDeprecation() {
		$this->expectException( Deprecated::class );
	}

	/**
	 * Set expectation for the message when receiving a PHP native deprecation notice.
	 *
	 * @param string $message The message to expect.
	 *
	 * @return void
	 */
	final public function expectDeprecationMessage( $message ) {
		$this->expectExceptionMessage( $message );
	}

	/**
	 * Set expectation for the message when receiving a PHP native deprecation notice (regex based).
	 *
	 * @param string $regularExpression A regular expression which must match the message.
	 *
	 * @return void
	 */
	final public function expectDeprecationMessageMatches( $regularExpression ) {
		$this->expectExceptionMessageRegExp( $regularExpression );
	}

	/**
	 * Set expectation for receiving a PHP native notice.
	 *
	 * @return void
	 */
	final public function expectNotice() {
		$this->expectException( Notice::class );
	}

	/**
	 * Set expectation for the message when receiving a PHP native notice.
	 *
	 * @param string $message The message to expect.
	 *
	 * @return void
	 */
	final public function expectNoticeMessage( $message ) {
		$this->expectExceptionMessage( $message );
	}

	/**
	 * Set expectation for the message when receiving a PHP native notice (regex based).
	 *
	 * @param string $regularExpression A regular expression which must match the message.
	 *
	 * @return void
	 */
	final public function expectNoticeMessageMatches( $regularExpression ) {
		$this->expectExceptionMessageRegExp( $regularExpression );
	}

	/**
	 * Set expectation for receiving a PHP native warning.
	 *
	 * @return void
	 */
	final public function expectWarning() {
		$this->expectException( Warning::class );
	}

	/**
	 * Set expectation for the message when receiving a PHP native warning.
	 *
	 * @param string $message The message to expect.
	 *
	 * @return void
	 */
	final public function expectWarningMessage( $message ) {
		$this->expectExceptionMessage( $message );
	}

	/**
	 * Set expectation for the message when receiving a PHP native warning (regex based).
	 *
	 * @param string $regularExpression A regular expression which must match the message.
	 *
	 * @return void
	 */
	final public function expectWarningMessageMatches( $regularExpression ) {
		$this->expectExceptionMessageRegExp( $regularExpression );
	}

	/**
	 * Set expectation for receiving a PHP native error.
	 *
	 * @return void
	 */
	final public function expectError() {
		$this->expectException( Error::class );
	}

	/**
	 * Set expectation for the message when receiving a PHP native error.
	 *
	 * @param string $message The message to expect.
	 *
	 * @return void
	 */
	final public function expectErrorMessage( $message ) {
		$this->expectExceptionMessage( $message );
	}

	/**
	 * Set expectation for the message when receiving a PHP native error (regex based).
	 *
	 * @param string $regularExpression A regular expression which must match the message.
	 *
	 * @return void
	 */
	final public function expectErrorMessageMatches( $regularExpression ) {
		$this->expectExceptionMessageRegExp( $regularExpression );
	}
}
