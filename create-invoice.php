<?php

require_once __DIR__ . '/vendor/autoload.php';

function createInvoice($args = array()) {

    $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf = new \Mpdf\Mpdf( array(
        'fontDir' => array_merge($fontDirs, [
            __DIR__ . '/custom/font/directory',
        ]),
        'fontdata' => $fontData + [
                'freesans' => [
                    'R' => 'FreeSans.ttf'
                ]
            ],
        'default_font' => 'freesans',
        'format' => 'A4-L',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 10,
        'margin_bottom' => 10,
        'margin_header' => 10,
        'margin_footer' => 10
    ) );

    $invoice_number = $args['invoice_number'];
    $invoice_status = $args['invoice_status'];
    $invoice_date = $args['invoice_date'];
    $payment_date = $args['payment_date'];
    $invoice_from = $args['invoice_from'];
    $invoice_to = $args['invoice_to'];
    $payment_type = $args['payment_type'];
    $payment_id = $args['payment_id'];
    $invoice_items = $args['invoice_items'];
    $invoice_total = $args['invoice_total'];
    $invoice_currency_symbol = $args['invoice_currency_symbol'];

    $invoice_from_html = '';

    if ( $invoice_from && is_array( $invoice_from ) ) {
        foreach ( $invoice_from as $key => $item ) {
            $color = '#000';
            if ( $key == 'phone' ) {
                $color = 'deepskyblue';
            }
            $invoice_from_html .= '<p style="color: ' . $color . ';">' . $item . '<p>';
            if ( $key == 'address' ) {
                $invoice_from_html .= '<br>';
            }
        }
    }

    $invoice_to_html = '';

    if ( $invoice_to && is_array( $invoice_to ) ) {
        foreach ( $invoice_to as $key => $item ) {
            $color = '#000';
            if ( $key == 'phone' ) {
                $color = 'deepskyblue';
            }
            $invoice_to_html .= '<p style="color: ' . $color . ';">' . $item . '<p>';
            if ( $key == 'address' ) {
                $invoice_to_html .= '<br>';
            }
        }
    }

    $html = '<html>
                <head>
                    <style>
                        h1 {
                            font-weight: normal;
                        }
                        .invoice-info {
                            width: 100%;
                            margin-top: 15pt;
                        }
                        .invoice-info td {
                            border: 0.1mm solid #000000;
                            padding: 2mm;
                            text-align: left;
                        }
                        .invoice-items {
                            width: 100%;
                            margin-top: 15pt;
                        }
                        .invoice-items th {
                            padding: 2mm;
                            text-align: left;
                            border-top: 0.1mm solid #000000;
                            border-bottom: 0.1mm solid #000000;
                        
                        }
                        .invoice-items td {
                            padding: 4mm 2mm;
                            text-align: left;
                        }
                    </style>
                </head>
                <body>
    ';

    $invoice_items_html = '';

    if ( $invoice_items && is_array( $invoice_items ) ) {
        foreach ( $invoice_items as $invoice_item ) {
            $invoice_items_html .= '
                <tr>
                    <td style="border-left: 0.1mm solid #000000;">' . $invoice_item['product'] . '</td>
                    <td>' . $invoice_item['description'] . '</td>
                    <td>' . $invoice_item['qty'] . '</td>
                    <td>' . $invoice_item['rate'] . $invoice_currency_symbol . '</td>
                    <td style="border-right: 0.1mm solid #000000;">' . $invoice_item['amount'] . $invoice_currency_symbol . '</td>
                </tr>>
            ';
        }
    }

    $invoice_total_html = '
        <tr>
            <td style="border-top: 0.1mm solid #000000;"></td>
            <td style="border-top: 0.1mm solid #000000;"></td>
            <td style="border-top: 0.1mm solid #000000;"></td>
            <td style="border-top: 0.1mm solid #000000;">Total:</td>
            <td style="border-top: 0.1mm solid #000000;">' . $invoice_total . $invoice_currency_symbol . '</td>
        </tr>
    ';



    $html .= '<h1>Invoice #' . $invoice_number . ' ('. $invoice_status . ')</h1>';
    $html .= '<p class="date">Invoice Date: ' . $invoice_date . '</p>';
    $html .= '<p class="date">Payment Date: ' . $payment_date . '</p>';
    $html .= '
        <table class="invoice-info">
            <tbody>
                <tr>
                    <td width="30%" valign="top">
                        <p><b>From</b></p><br>
                        ' . $invoice_from_html . '
                        <p style="color: deepskyblue;">Contact Buyer</p><br><br>
                    </td>
                    <td width="30%" valign="top">
                        <p><b>To</b></p><br>
                        ' . $invoice_to_html . '
                        <p style="color: deepskyblue;">Contact Seller</p><br><br>
                    </td>
                    <td width="40%" valign="top">
                        <p><b>Payment</b></p><br>
                        <p>Type: ' . $payment_type . '</p>
                        <p>ID: ' . $payment_id . '</p>
                    </td>
                </tr>
            </tbody>
        </table>
    ';
    $html .= '
        <table class="invoice-items">
            <thead>
                <tr>
                    <th width="25%" valign="top" style="border-left: 0.1mm solid #000000;">
                        Product/Service
                    </th>
                    <th width="45%" valign="top">
                        Description
                    </th>
                    <th width="10%" valign="top">
                        Qty
                    </th>
                    <th width="10%" valign="top">
                        Rate
                    </th>
                    <th width="10%" valign="top" style="border-right: 0.1mm solid #000000;">
                        Amount
                    </th>
                </tr>
            </thead>
            <tbody>
                ' . $invoice_items_html . '
            </tbody>
            <tfoot>
                ' . $invoice_total_html . '
            </tfoot>
        </table>
    ';






    $html .= '</body>
        </html>
    ';


    $mpdf->SetProtection(array('print'));
    $mpdf->SetWatermarkText( $invoice_status );
    $mpdf->showWatermarkText = true;
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.1;
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->WriteHTML( $html );
    $mpdf->Output();

}