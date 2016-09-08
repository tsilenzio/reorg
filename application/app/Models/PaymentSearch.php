<?php

namespace App\Models;

use \DB as DB;
use \Schema as Schema;

class PaymentSearch extends Payment
{
    /**
     * Database table name
     *
     * @var string
     */
    public $table = 'payments_fts';

    ////////////////////
    // Public Methods //
    ////////////////////

    /**
     * Destroys the optimized table, recreates it and than populates it with existing data
     *
     * @todo Find a better process for this, it has a major drawback / flaw
     */
    public static function updateTable()
    {
        self::createVirtualTable();
        self::fillVirtualTable();
    }

    /**
     * Returns a filtered search query
     *
     * @param $phrase
     *
     * @return mixed
     */
    public static function search($phrase)
    {
        // Start building an object for pagination
        $query = PaymentSearch::query()
            ->orderBy('id', 'asc');

        // Apply filtration query if a phrase has been supplied
        if(is_string($phrase) && strlen($phrase) > 0)
        {
            $query->whereRaw("payments_fts MATCH '*$phrase*'");
        }

        return $query;
    }

    ////////////////////
    // Helper Methods //
    ////////////////////

    /**
     * Drops and recreates the faster dynamic table for searching capabilities
     *
     * @todo Find a better process for this, it has a major drawback / flaw
     */
    protected static function createVirtualTable()
    {
        // Retrieve database columns
        $columns = ['id', 'record_id', 'reorg_reported', 'total_amount_of_payment_usdollars'];

        // Retrieve columns that were added on]
        $addedColumns = array_diff(Payment::getColumns(), $columns);

        Schema::dropIfExists('payments_fts');

        $results = DB::statement('
            create virtual table "payments_fts" using fts4 (
                "id" integer not null primary key autoincrement,
                "record_id" integer not null,
                "reorg_reported" tinyint(1) not null default \'false\',
                "total_amount_of_payment_usdollars" numeric not null,"' .
            implode($addedColumns, '","') .
            '")');
    }

    /**
     * Copy the data from the dynamically expanding table and fill in the virtual table
     *
     * @todo Find a better process for this, it has a major drawback / flaw
     */
    protected static function fillVirtualTable()
    {
        Payment::chunk(1000, function ($payments) {
            foreach ($payments as $payment) {
                // Prepare data for use
                $attributes = $payment->toArray();

                // Disable Eloquent's built-in guarding temporarily
                self::unguard();

                // Create a new payment instance
                self::create($attributes);

                // Enable Eloquent's built-in guarding
                self::reguard();
            }
        });
    }

    //////////////
    // Mutators //
    //////////////

    /**
     * Set the reported value
     *
     * @param  string  $value
     * @return void
     */
    public function setReorgReportedAttribute($value)
    {
        $this->attributes['reorg_reported'] = $value === "1" ? true : false;
    }

    ///////////////
    // Accessors //
    ///////////////

    /**
     * Get the payment amount
     *
     * @param  string  $value
     * @return void
     */
    public function getTotalAmountOfPaymentUsdollarsAttribute($value)
    {
        return number_format($value, 2, '.', '');
    }

    /**
     * Get the reported value
     *
     * @param  string  $value
     * @return void
     */
    public function getReorgReportedAttribute($value)
    {
        return $value === "1" ? 'true' : 'false';
    }
}
