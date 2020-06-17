<?php
declare(strict_types=1);

namespace App\Finder;

use App\Resolver\UrlContentResolver;
use Symfony\Component\DomCrawler\Crawler;

final class AnmeldungSlotFinder implements SlotFinder
{
    private UrlContentResolver $contentResolver;

    public function __construct(UrlContentResolver $contentResolver)
    {
        $this->contentResolver = $contentResolver;
    }

    public function isAnySlotAvailable(): bool
    {
        $content = $this->contentResolver->getContents();

        $crawler = new Crawler($content);
        $calendar = $crawler->filter('div.calendar-month-table');
        $availableDays = $calendar->children('table td.buchbar');

        if ($availableDays->count() > 0) {
            return true;
        }

        return false;
    }
}
