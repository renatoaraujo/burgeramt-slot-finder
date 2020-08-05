<?php
declare(strict_types=1);

namespace App\Command;

use App\Finder\SlotFinder;
use App\IFTTT\WebHookEventTrigger;
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

    private WebHookEventTrigger $eventTrigger;

    private int $waitTime;

    public function __construct(
        SlotFinder $slotFinder,
        SMSNotification $smsClient,
        LoggerInterface $logger,
        WebHookEventTrigger $eventTrigger,
        int $waitTime
    ) {
        parent::__construct('Anmeldung Slot Finder');
        $this->slotFinder = $slotFinder;
        $this->smsClient = $smsClient;
        $this->logger = $logger;
        $this->eventTrigger = $eventTrigger;
        $this->waitTime = $waitTime;
    }

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('[%s] Started.', (new \DateTimeImmutable())->format('d/m/Y H:i:s')));

        try {
            if ($this->slotFinder->isAnySlotAvailable()) {

                $this->eventTrigger->trigger();

                $message = 'BOT: Head NOW to https://bit.ly/3fAevG4 for Anmeldung';
                $this->smsClient->send($message);
                $output->writeln($message);

                $this->logger->info('Slot for Anmeldung found!');
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        $output->writeln(sprintf('[%s] Sleeping now, restarting in %d seconds.', (new \DateTimeImmutable())->format('d/m/Y H:i:s'), $this->waitTime));

        sleep($this->waitTime);
        $this->getApplication()->find(self::$defaultName)->run($input, $output);
    }
}
