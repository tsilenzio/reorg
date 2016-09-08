<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\PaymentSearch;
use Socrata;

class ImportPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the payment data';

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
        // Retrieve most recently added payment
        $last_payment = Payment::orderBy('record_id', 'desc')->first();

        // Assign the last record_id incremented used or 0 if none
        $last_id = isset($payment['record_id']) ? ++$last_payment['record_id'] : 0;

        // Fetch the 1000 new records
        $socrata = new Socrata('https://openpaymentsdata.cms.gov/');
        $params = array('$where' => "record_id > '$last_id'", '$order' => 'record_id ASC', '$limit' => '1000');
        $records = $socrata->get('resource/tf25-5jad.json', $params);

        // Notify that we are up-to-date
        if(is_array($records) && count($records) === 0)
        {
            die('No new records to import');
        }

        // Loop over all records returned and record them
        foreach($records as $attributes)
        {
            $payment = Payment::record($attributes);

            echo('Recorded payment: ' . $payment->record_id . "\n");
        }

        PaymentSearch::updateTable();

        die('Recorded ' . count($records) . " new payments\n");
    }
}
