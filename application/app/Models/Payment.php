<?php

namespace App\Models;

use \DB as DB;
use \Schema as Schema;

/**
 * Class Payment
 *
 * @todo Avoid automating table columns and instead use migrations when necessary
 *
 * @package App\Models
 *
 */
class Payment extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    public $table = 'payments';

    /**
     * Disable database timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    protected $casts = [
        'total_amount_of_payment_usdollars' => 'float',
    ];

    ////////////////////
    // Public Methods //
    ////////////////////

    /**
     * Records the payment information
     *
     * @param $attributes Attributes as keys and respective data assigned to key
     *
     * @return static
     */
    public static function record($attributes)
    {
        self::checkColumns(array_keys($attributes));

        // Disable Eloquent's built-in guarding temporarily
        self::unguard();

        // Create a new payment instance
        $payment = self::create($attributes);

        // Enable Eloquent's built-in guarding
        self::reguard();

        if($payment->total_amount_of_payment_usdollars >= 10)
        {
            $payment->report();
        }

        // Return the newly created object so it can be used
        return $payment;
    }

    /**
     * Report the payment, ideally this would do something more than setting a flag to true
     *
     * @return $this
     */
    public function report()
    {
        // Set the reported flag to true
        $this->reorg_reported = true;

        // Save the changes
        $this->save();

        // Enable chainloading
        return $this;
    }

    /**
     * Returns an array of columns for the payments table
     *
     * @return mixed
     */
    public static function getColumns()
    {
        return DB::getSchemaBuilder()
            ->getColumnListing(with(new static())->table);
    }

    ////////////////////
    // Helper Methods //
    ////////////////////

    /**
     * Creates a column under the payments table
     *
     * @todo Allow options for data types, indexes, defaults, etc.
     *
     * @param $column Column name to be created
     *
     * @return void
     */
    protected static function addColumn($column)
    {
        Schema::table('payments', function($table) use ($column) {
            $table->string($column)->nullable();
        });
    }

    /**
     * Creates a column under the payments table
     *
     * @todo Detection and decision process for data types, indexes, etc.
     *
     * @param $attributes Attributes to compare against column names
     *
     * @return void
     */
    protected static function checkColumns($attributes)
    {
        // Retrieve database columns
        $columns = Payment::getColumns();

        // Retrieve columns missing from the database
        $missingColumns = array_diff($attributes, $columns);

        // Add missing database columns to the database
        foreach($missingColumns as $missingColumn)
        {
            Payment::addColumn($missingColumn);
        }
    }
}
