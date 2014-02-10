<?php namespace Softservlet\Notification\Resolver;

/**
 * @author Vladu Emilian Sorin <vladu@softservlet.com>
 *
 * @version 1.0
 */

interface ResolverInterface
{
	/**
	 * @brief the method should resolve the array
	 * of notifications to a specified notification 
	 * controller.
	 *
	 * @param array $notifications - an array with NotificationInterface objects
	 * @param $param - this argument will be passed to all notifications
	 *
	 * @return array
	 */
	public function resolve($notifications, $param);
}
