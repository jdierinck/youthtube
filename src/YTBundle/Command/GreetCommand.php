<?php

namespace YTBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GreetCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('demo:greet')
            ->setDescription('Greet someone')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
			->addArgument(
				'last_name',
				InputArgument::OPTIONAL,
				'Your last name?'
			)
			->addArgument(
				'names',
				InputArgument::IS_ARRAY,
				'Your last name?'
			)			
            ->addOption(
               'yell',
               'y',
               InputOption::VALUE_NONE,
               'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if ($name) {
            $text = 'Hello '.$name;
        } else {
            $text = 'Hello';
        }
		if ($lastName = $input->getArgument('last_name')) {
			$text .= ' '.$lastName;
		}
		if ($names = $input->getArgument('names')) {
			$text .= ' '.implode(', ',$names);
		}
        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }

        $output->writeln($text);
    }
}