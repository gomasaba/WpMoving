<?php
namespace WpMoving\Console\Command;

use WpMoving\Model\Wp\Post;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DateTime;

class CrawlerImage extends Command
{

	protected function configure()
	{
		$this->setName('crawlerimage')
			->setDescription('画像を収集します');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->input  = $input;
		$this->output = $output;
		$this->output->writeln('<info>画像を収集します</info>');

		$posts        = Post::where('post_status','=','publish')
							->where('post_type','=','post')
							->get();
		foreach ($posts as $post) {
			sleep(0.5);
			$this->crawl($post);
		}

		$this->output->writeln('<info>終了します</info>');
	}

	public function crawl($post)
	{
		$this->output->writeln('<comment>' . $post->ID . 'を処理します</comment>');

		$body = $post->post_content;
		preg_match_all("%<img .*?src=['\"](.*?)['\"]%s", $post->post_content, $matches);
		if(!empty($matches[0])){
			foreach ($matches[1] as $url) {
				if(preg_match('/^http/',$url)){
					$DateTime = new DateTime($post->post_date);
					$pathinfo = pathinfo($url);
					$to_dir   = IMAGE_DIR . DS . $DateTime->format('Y') . DS . $DateTime->format('m');
					if (!is_dir($to_dir)) {
						mkdir($to_dir, 0775, true);
					}
					$image = file_get_contents($url);
					file_put_contents($to_dir . DS . $pathinfo['basename'], $image);
					$to_dir_relative = DS . str_replace(DOC_ROOT, '', $to_dir);
					$body            = str_replace($url, $to_dir_relative . DS . $pathinfo['basename'], $body);
				}
			}
			$post->post_content = $body;
			$post->save();
		}
	}

}