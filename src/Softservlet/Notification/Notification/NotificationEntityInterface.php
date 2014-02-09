<?php namespace Softservlet\Notification\Notification;

/**
 * @author Vladu Emilian Sorin <vladu@softservlet.com>
 *
 * @version 1.0
 */
interface NotificationEntityInterface 
{
	/**
	 * @brief each entity has a name, usually the class name
	 * of the object
	 * 
	 * @return string
	 */
	public function getName();

	/**
	 * @brief get the entity id if it's defined, null otherwise
	 *
	 * @return int/null
	 */
	public function getId();
}

