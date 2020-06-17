<?php
declare(strict_types=1);

namespace App\Command;

use App\Finder\SlotFinder;
use App\Notification\SMSNotification;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FindAnmeldungSlotsCommand extends Command
{
    protected static $defaultName = 'app:find-anmeldung-slots';

    private SlotFinder $slotFinder;

    private SMSNotification $smsClient;

    private LoggerInterface $logger;

    public function __construct(SlotFinder $slotFinder, SMSNotification $smsClient, LoggerInterface $logger)
    {
        parent::__construct('Anmeldung Slot Finder');
        $this->slotFinder = $slotFinder;
        $this->smsClient = $smsClient;
        $this->logger = $logger;
    }

    protected function configure()
    {}

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info(
            sprintf("Starting execution at %s", (new \DateTimeImmutable())->format('d/m/Y H:i:s'))
        );

        try {
            if ($this->slotFinder->isAnySlotAvailable()) {
                $message = 'Slot for Anmeldung found, please head NOW to https://service.berlin.de/terminvereinbarung/termin/day/';
                $this->smsClient->send($message);
                $output->writeln($message);
                return Command::SUCCESS;
            }

            $output->writeln("Nothing found, terminating...");

            $this->logger->info(
                sprintf("Terminated at %s", (new \DateTimeImmutable())->format('d/m/Y H:i:s'))
            );
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
