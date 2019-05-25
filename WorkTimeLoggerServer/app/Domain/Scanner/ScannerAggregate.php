<?php

namespace App\Domain\Scanner;

use App\Domain\Employee\Exceptions\CouldNotStartWorking;
use App\Domain\Scanner\Events\ApiTokenRegenerated;
use App\Domain\Scanner\Events\ScannerCreated;
use App\Domain\Scanner\Events\ScannerDisabled;
use App\Domain\Scanner\Events\ScannerEnabled;
use App\Domain\Scanner\Exceptions\ScannerAlreadyExists;
use App\Domain\Scanner\Exceptions\ScannerDoesntExistException;
use Spatie\EventProjector\AggregateRoot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\EventProjector\ShouldBeStored;

/**
 * @method static ScannerAggregate retrieve(string $uuid)
 * @method ScannerAggregate recordThat(ShouldBeStored $domainEvent)
 * @method ScannerAggregate persist()
 */
final class ScannerAggregate extends AggregateRoot
{
    /**
     * @var bool
     */
    private $created = false;
    
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var string
     */
    private $api_token;

    /**
     * @var bool
     */
    private $enabled = false;

    public function createScanner(string $name)
    {
        if($this->created)
            throw new ScannerAlreadyExists();
        
        return $this->recordThat(new ScannerCreated($name));
    }

    public function regenerateApiToken(string $key = null)
    {
        if(!$this->created)
            throw new ScannerDoesntExistException();
        
        return $this->recordThat(new ApiTokenRegenerated($key ?? Str::random(32)));
    }

    public function enable()
    {
        if(!$this->created)
            throw new ScannerDoesntExistException();
        
        return $this->recordThat(new ScannerEnabled());
    }

    public function disable()
    {
        if(!$this->created)
            throw new ScannerDoesntExistException();
        
        return $this->recordThat(new ScannerDisabled());
    }

    protected function applyScannerCreated(ScannerCreated $event)
    {
        $this->created = true;
        $this->name = $event->name;
    }

    protected function applyApiTokenRegenerated(ApiTokenRegenerated $event)
    {
        $this->api_token = $event->api_token;
    }

    protected function applyScannerEnabled(ScannerEnabled $event)
    {
        $this->enabled = true;
    }

    protected function applyScannerDisabled(ScannerDisabled $event)
    {
        $this->enabled = false;
    }
}
