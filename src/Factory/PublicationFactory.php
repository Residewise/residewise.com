<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\Asset;
use App\Entity\Publication;
use Carbon\Carbon;
use DateTimeImmutable;

class PublicationFactory
{
    public function create(?Asset $asset, ?DateTimeImmutable $startsAt, bool $isApproved = false): Publication {
        $publication = new Publication();
        $publication->setAsset($asset);
        $publication->setIsApproved($isApproved);
        if ($startsAt) {
            $publication->setStartsAt($startsAt);
        }
        $publication->setEndsAt($this->calculateEndDate($publication));

        return $publication;
    }

    private function calculateEndDate(Publication $publication): DateTimeImmutable
    {
        $startsAt = Carbon::parse($publication->getStartsAt());

        return $startsAt->addDays(30)
            ->toDateTimeImmutable();
    }
}
