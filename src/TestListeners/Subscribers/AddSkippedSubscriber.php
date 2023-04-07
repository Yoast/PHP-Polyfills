<?php

namespace Yoast\PHPUnitPolyfills\TestListeners\Subscribers;

use PHPUnit\Event\Test\Skipped;
use PHPUnit\Event\Test\SkippedSubscriber;

/**
 * Event subscriber.
 *
 * @since 2.0.0
 */
final class AddSkippedSubscriber implements SkippedSubscriber {

	/**
	 * Subscriber constructor.
	 *
	 * @param Handler $handler Instance of the class which composes a "test listener".
	 */
	public function __construct( private readonly Handler $handler ) {}

	/**
	 * Trigger the test listener method equivalent to this event.
	 *
	 * @param Skipped $event The event object.
	 */
	public function notify( Skipped $event ): void {
		$this->handler->addSkippedTest(
			$event->test(),
			$event->throwable(),
			$event->telemetryInfo()->time()
		);
	}
}
