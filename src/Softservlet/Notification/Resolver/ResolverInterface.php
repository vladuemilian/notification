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
	 * @return array
	 */
	public function resolve($notifications);
}
