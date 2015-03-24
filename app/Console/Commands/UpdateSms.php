<?php namespace sms\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateSms extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'sms:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Rencananya Update SMS';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info("SMS telah diupdate!");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			// ['example', InputArgument::REQUIRED, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['sms', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
