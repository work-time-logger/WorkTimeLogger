<?php

namespace App\Domain\Scanner\Projectors;

use App\Domain\Scanner\Events\ApiTokenRegenerated;
use App\Domain\Scanner\Events\ScannerCreated;
use App\Domain\Scanner\Events\ScannerDisabled;
use App\Domain\Scanner\Events\ScannerEnabled;
use App\Models\Scanner;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

final class ScannersProjector implements Projector
{
    use ProjectsEvents;

    public function onScannerCreated(ScannerCreated $event, string $aggregateUuid)
    {
        $scanner = new Scanner;
        $scanner->uuid = $aggregateUuid;
        $scanner->name = $event->name;
        $scanner->is_active = false;
        $scanner->api_token = null;
        $scanner->save();
    }

    public function onScannerDisabled(ScannerDisabled $event, string $aggregateUuid)
    {
        $scanner = Scanner::byUuid($aggregateUuid);
        $scanner->is_active = false;
        $scanner->save();
    }

    public function onScannerEnabled(ScannerEnabled $event, string $aggregateUuid)
    {
        $scanner = Scanner::byUuid($aggregateUuid);
        $scanner->is_active = true;
        $scanner->save();
    }

    public function onApiTokenRegenerated(ApiTokenRegenerated $event, string $aggregateUuid)
    {
        $scanner = Scanner::byUuid($aggregateUuid);
        $scanner->api_token = $event->api_token;
        $scanner->save();
    }

    public function onStartingEventReplay()
    {
        Scanner::truncate();
    }
}
