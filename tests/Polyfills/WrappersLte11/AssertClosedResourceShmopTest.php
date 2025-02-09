<?php

namespace Yoast\PHPUnitPolyfills\Tests\Polyfills\WrappersLte11;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhp;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\Attributes\RequiresPhpunit;
use Yoast\PHPUnitPolyfills\Helpers\ResourceHelper;
use Yoast\PHPUnitPolyfills\Polyfills\AssertClosedResource;
use Yoast\PHPUnitPolyfills\Tests\Polyfills\AssertClosedResourceShmopTestCase;

/**
 * Functionality test for the functions polyfilled by the AssertClosedResource trait.
 *
 * Note: the return value of the Shmop functions has changed in PHP 8.0 from `resource`
 * to `Shmop` (object), which is why the tests will be skipped on PHP >= 8.0.
 *
 * @covers \Yoast\PHPUnitPolyfills\Helpers\ResourceHelper
 * @covers \Yoast\PHPUnitPolyfills\Polyfills\AssertClosedResource
 *
 * @requires extension shmop
 * @requires PHP < 8.0
 * @requires PHPUnit < 12
 */
#[CoversClass( AssertClosedResource::class )]
#[CoversClass( ResourceHelper::class )]
#[RequiresPhp( '< 8.0' )]
#[RequiresPhpExtension( 'shmop' )]
#[RequiresPhpunit( '< 12' )]
final class AssertClosedResourceShmopTest extends AssertClosedResourceShmopTestCase {}
