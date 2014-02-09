<?php namespace Softservlet\Notification\Repositories;

use Softservlet\Notification\Notification\NotificationInterface;

interface NotificableRepositoryInterface
{
	/**
	 * @brief return an array of unreaded notification to 
	 * specify user, usually passed within constructor.
	 *
	 * @param int $limit - the limit of notifications which 
	 * will be returned
	 * @param int $offset - the offset of selection
	 * 
	 * @return array $notifications
	 */
	public function getUnreaded($limit, $offset);

	/**
	 * @brief return an array of notifications
	 *
	 * @param int $limit - the limit of notifications which 
	 * will be returned
	 * @param int $offset - the offset of selection
	 * 
	 * @return array $notifications
	 */
	public function get($limit, $offset);


}
