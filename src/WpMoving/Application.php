<?php
namespace WpMoving;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Symfony\Component\Console\Output\OutputInterface;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

use DirectoryIterator;

class Application extends BaseApplication
{

	public function __construct()
	{
		parent::__construct('WpMoving CLI Application', '1.0.0');
	}

	/**
	 * コマンドを動的に生成して設定する
	 * @return [type] [description]
	 */
	public function getDefaultCommands()
	{
		$commands    = parent::getDefaultCommands();
		$dirIterator = new DirectoryIterator(__DIR__ . '/Console/Command');
		foreach ($dirIterator as $key => $file) {
			if ($file->isFile() && $file->getExtension() == 'php') {
				$class           = str_replace('.php', '', $file->getBasename());
				$class_namespace = __NAMESPACE__ . '\\Console\\Command\\' . $class;
				if (class_exists($class_namespace)) {
					$commands[] = new $class_namespace();
				}
			}
		}
		return $commands;
	}

	/**
	 * コマンド実行時にDB接続設定するようにオーバーライド
	 *
	 * @param  Command $command        [description]
	 * @param  InputInterface $input   [description]
	 * @param  OutputInterface $output [description]
	 *
	 * @return [type]                   [description]
	 */
	protected function doRunCommand(Command $command, InputInterface $input, OutputInterface $output)
	{
		//アプリケーションをコマンドに設定
		$command->setApplication($this);
		//データベースの接続設定
		require_once WP_CONFIG;
		define('WP_PREFIX',$table_prefix);
		$settings = array(
			'driver'    => 'mysql',
			'host'      => DB_HOST,
			'database'  => DB_NAME,
			'username'  => DB_USER,
			'password'  => DB_PASSWORD,
			'charset'   => DB_CHARSET,
			'prefix'    => $table_prefix
		);
		$capsule = new Capsule;
		$capsule->addConnection($settings);
		$capsule->setEventDispatcher(new Dispatcher(new Container));
		$capsule->setAsGlobal();
		$capsule->bootEloquent();

		return parent::doRunCommand($command, $input, $output);
	}

}