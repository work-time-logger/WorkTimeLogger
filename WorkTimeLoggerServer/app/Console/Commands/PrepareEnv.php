<?php

namespace App\Console\Commands;

use App\Domain\Employee\EmployeeAggregate;
use App\Domain\Employee\Exceptions\CouldNotStopWorking;
use App\Domain\Scanner\ScannerAggregate;
use App\Models\Employee;
use App\Models\Scanner;
use App\Models\IdCard;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PrepareEnv extends Command
{
    const FIRST_EMPLOYEE_UUID  = 'e22d1887-ccbc-471b-b725-6f79b1807e2e';
    const SECOND_EMPLOYEE_UUID = 'f9722ae4-dbdf-4514-af92-25a8f1e89171';
    const THIRD_EMPLOYEE_UUID  = 'b3abf1df-fe0c-4c34-8f65-d31ff9854416';
    const FIRST_CARD_ID        = '686790c6';
    const SECOND_CARD_ID       = '01c1759e';
    const THIRD_CARD_ID        = '0849c760';
    const SCANNER_API_TOKEN    = 'VERY_SECRET_TEST_TOKEN';
    const SCANNER_UUID         = '17bb79ef-824d-4785-b078-f47bac727039';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:prepare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare environment for acceptation testing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ScannerAggregate::retrieve(self::SCANNER_UUID)
            ->createScanner('Test Scanner')
            ->regenerateApiToken(self::SCANNER_API_TOKEN)
            ->enable()
            ->persist();
        
        EmployeeAggregate::retrieve(self::FIRST_EMPLOYEE_UUID)
            ->createEmployee('Janusz', 'Kowalski')
            ->startWork($entry_uuid = Str::uuid(), today()->subDay()->setHour(10))
            ->stopWork($entry_uuid, today()->subDay()->setHour(18))
            ->startWork($entry_uuid = Str::uuid(), today()->subDay()->setHour(21))
            ->stopWork($entry_uuid, today()->setHour(8))
            ->persist();

        $card = new IdCard();
        $card->uuid = Str::uuid();
        $card->rfid_id = self::FIRST_CARD_ID;
        Employee::byUuid(self::FIRST_EMPLOYEE_UUID)->IdCards()->save($card);
        
        EmployeeAggregate::retrieve(self::SECOND_EMPLOYEE_UUID)
            ->createEmployee('Ignacy', 'Macierewicz')
            ->startWork($entry_uuid = Str::uuid(), today()->subDay()->setHour(10))
            ->stopWork($entry_uuid, today()->subDay()->setHour(14))
            ->persist();

        $card = new IdCard();
        $card->uuid = Str::uuid();
        $card->rfid_id = self::SECOND_CARD_ID;
        Employee::byUuid(self::SECOND_EMPLOYEE_UUID)->IdCards()->save($card);
        
        EmployeeAggregate::retrieve(self::THIRD_EMPLOYEE_UUID)
            ->createEmployee('Ewa', 'Marcinkiewicz')
            ->startWork($entry_uuid = Str::uuid(), today()->subDay()->setHour(15))
            ->stopWork($entry_uuid, today()->subDay()->setHour(18))
            ->persist();

        $card = new IdCard();
        $card->uuid = Str::uuid();
        $card->rfid_id = self::THIRD_CARD_ID;
        Employee::byUuid(self::THIRD_EMPLOYEE_UUID)->IdCards()->save($card);
    }
}
