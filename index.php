<?php

require_once __DIR__ . '/create-invoice.php';

$args = array(
    'invoice_number' => '12345',
    'invoice_status' => 'PAID',
    'invoice_date' => '3/15/17',
    'payment_date' => '4/05/17',
    'invoice_from' => array(
        'name' => 'Jim Smith',
        'email' => 'jsmith@rockslsy',
        'street' => '12 main street',
        'address' => 'State College P',
        'phone' => '814-543-5345'
    ),
    'invoice_to' => array(
        'name' => 'Janine Max',
        'email' => 'jmax@mainprod',
        'street' => '100 Charles Str',
        'address' => 'Miami, FL 7895',
        'phone' => '213-234-4502'
    ),
    'payment_type' => '566541358461',
    'payment_id' => '566541358461',
    'invoice_items' => array(
        array(
            'product' => 'Product',
            'description' => 'ASIN: B0768P7NW8 SKU: 3-android-20-fba',
            'qty' => '1',
            'rate' => '25.00',
            'amount' => '25.00'
        ),
        array(
            'product' => 'Amazon',
            'description' => 'Pick and Pack Fee',
            'qty' => '1',
            'rate' => '7.99',
            'amount' => '7.99'
        )
    ),
    'invoice_total' => '32.99',
    'invoice_currency_symbol' => '$',
);

createInvoice( $args );