<?php namespace sms\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'sms\Console\Commands\Inspire',
		'sms\Console\Commands\UpdateSms',
		'sms\Console\Commands\HttpRequest',
		'LucaDegasperi\OAuth2Server\Console\ClientCreatorCommand',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')->hourly();
		$schedule->command('sms:update');
		$schedule->command('sms:push');
		// $schedule->command('queue:listen');
	}

}
