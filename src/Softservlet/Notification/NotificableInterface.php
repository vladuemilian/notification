<?php namespace Softservlet\Notification;

/**
* @author Vladu Emilian Sorin <vladu@softservlet.com>
*
* @version 1.0
*/

/**
 * @brief this interface should be implemented
 * by a entity which will receive the notifications,
 * usually a User object from your application.
 */
interface NotificableInterface
{
	/**
	 * @brief return the unique id of the entity
	 *
	 * @return int
	 */
	public function getId();
}
