<?php namespace Softservlet\Notification\Repositories;

use Softservlet\Notification\NotificableInterface;
use Softservlet\Notification\Notification\NotificationEntityInterface;
use Softservlet\Notification\Notification\NotificationInterface;

/**
* @author Vladu Emilian Sorin <vladu@softservlet.com>
*
* @version 1.0
*/

interface NotificationRepositoryInterface
{
	/**
	 * @brief Create a Notification object and return it. 
	 *
	 * @param NotificationEntityInterface $object - the object of which notification will be made
	 * for example, it can be a Photo
	 * @param NotificationEntityInterface $actor - the actor of the notification - for example it
	 * can be a Like object.
	 *
	 * @return NotificationInterface
	 */
	public function create(NotificationEntityInterface $object, NotificationEntityInterface $actor);

	/**
	 * @brief Return a Notification object based on $id
	 *
	 * @param int $id 
	 *
	 * @return NotificationInterface
	 */
	public function find($id);
	
	/**
	 * @brief Attach a Notification to a Notificable object, usually to a user.
	 *
	 * @param NotificableInterface $notificable
	 * @param NotificationInterface $notification
	 *
	 * @return bool 
	 */
	public function attach(NotificableInterface $notificable, NotificationInterface $notification);

	/**
	 * @brief Remove a notification
	 *
	 * @param NotificationInterface $notification
	 * 
	 * @return bool
	 */
	public function remove(NotificationInterface $notification);
}
