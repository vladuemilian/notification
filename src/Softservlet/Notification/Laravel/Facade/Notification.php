<?php namespace Softservlet\Notification\Laravel\Facade;

use Illuminate\Support\Facades\Facade;

class Notification extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'Softservlet\Notification\Repositories\NotificationRepositoryInterface'; }

}
