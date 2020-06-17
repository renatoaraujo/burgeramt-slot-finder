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
                $message = 'Slot for Anmeldung found! Please head NOW to https://bit.ly/3fAevG4 and remember to open this page in clear/anonymous tab because of the session.';
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
