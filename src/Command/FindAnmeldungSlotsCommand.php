<?php
declare(strict_types=1);

namespace App\Command;

use App\Finder\SlotFinder;
use App\Notification\SMSNotification;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FindAnmeldungSlotsCommand extends Command
{
    protected static $defaultName = 'app:find-anmeldung-slots';

    private SlotFinder $slotFinder;

    private SMSNotification $smsClient;

    public function __construct(SlotFinder $slotFinder, SMSNotification $smsClient)
    {
        parent::__construct('Anmeldung Slot Finder');
        $this->slotFinder = $slotFinder;
        $this->smsClient = $smsClient;
    }

    protected function configure()
    {}

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            if ($this->slotFinder->isAnySlotAvailable()) {
                $message = 'Slot for Anmeldung found, please head NOW to https://service.berlin.de/terminvereinbarung/termin/day/';
                $this->smsClient->send($message);
                $output->writeln($message);
                return Command::SUCCESS;
            }

            $output->writeln("Nothing found, terminating...");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return Command::FAILURE;
        }
    }
}
