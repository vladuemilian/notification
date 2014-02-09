<?php namespace Softservlet\Notification\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Softservlet\Notification\Notification\Notification;
use Softservlet\Notification\Notification\NotificationEntity;
use Softservlet\Notification\Notifier\Notifier;
use Softservlet\Notification\Repositories\NotificableRepository;

class NotificationServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('softservlet/notification');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('Softservlet\Notification\Resolver\ResolverInterface', 'Softservlet\Notification\Resolver\Resolver');
		$this->app->bind('Softservlet\Notification\Repositories\NotificationRepositoryInterface', 'Softservlet\Notification\Repositories\NotificationRepository');
		$this->app->bind('Softservlet\Notification\Repositories\NotificableRepositoryInterface', 
		function($app, $user)
		{
			$repo =	$this->app->make('Softservlet\Notification\Repositories\NotificationRepositoryInterface');
			return new NotificableRepository($user, $repo);
		});

		$this->app->bind('Softservlet\Notification\Notification\NotificationEntityInterface',
		function($app, $params)
		{
			if(count($params)==2)
			{
				return new NotificationEntity($params[0], $params[1]);
			}
			else
			{
				return new NotificationEntity($params[0]);
			}
		});

		$this->app->bind('Softservlet\Notification\Notification\NotificationInterface', 
		function($app, $params)
		{
			return new Notification($params[0], $params[1], $params[2]);
		});

		$this->app->bind('Softservlet\Notification\Notifier\NotifierInterface',
		function($app, $params)
		{
			return new Notifier($params[0]);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
