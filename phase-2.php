<?php
error_reporting(E_ERROR);
ini_set('display_errors', 1);
require __DIR__ . '/vendor/autoload.php';

use ZATCA\EGS;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;

const ROOT_PATH = __DIR__;

$line_item = [
    'id' => '1',
    'name' => 'TEST NAME',
    'quantity' => 5,
    'tax_exclusive_price' => 10,
    'VAT_percent' => 0.15,
    'other_taxes' => [
        ['percent_amount' => 1]
    ],
    'discounts' => [
        ['amount' => 2, 'reason' => 'A discount'],
        ['amount' => 2, 'reason' => 'A second discount'],
    ],
];

$egs_unit = [
    'uuid' => '6f4d20e0-6bfe-4a80-9389-7dabe6620f12',
    'custom_id' => 'EGS1-886431145',
    'model' => 'IOS',
    'CRN_number' => '454634645645654',
    'VAT_name' => 'Qr',
    'VAT_number' => '301121971500003',
    'location' => [
        'city' => 'Khobar',
        'city_subdivision' => 'West',
        'street' => 'King Fahahd st',
        'plot_identification' => '0000',
        'building' => '0000',
        'postal_zone' => '31952',
    ],
    'branch_name' => 'My Branch Name',
    'branch_industry' => 'Food',
    'cancelation' => [
        'cancelation_type' => 'INVOICE',
        'canceled_invoice_number' => '',
    ],
];

$invoice = [
    'invoice_counter_number' => 1,
    'invoice_serial_number' => 'EGS1-886431145-1',
    'issue_date' => '2022-03-13',
    'issue_time' => '14:40:40',
    'previous_invoice_hash' => 'NWZlY2ViNjZmZmM4NmYzOGQ5NTI3ODZjNmQ2OTZjNzljMmRiYzIzOWRkNGU5MWI0NjcyOWQ3M2EyN2ZiNTdlOQ==', // AdditionalDocumentReference/PIH
    'line_items' => [
        $line_item,
        $line_item,
        $line_item,
    ],
];

$egs = new EGS($egs_unit);

$egs->production = false;

// New Keys & CSR for the EGS
list($private_key, $csr) = $egs->generateNewKeysAndCSR('Qr');

// Issue a new compliance cert for the EGS
list($request_id, $binary_security_token, $secret) = $egs->issueComplianceCertificate('123345', $csr);

// Sign invoice
list($signed_invoice_string, $invoice_hash, $qr) = $egs->signInvoice($invoice, $egs_unit, $binary_security_token, $private_key);

// Check invoice compliance
// echo($egs->checkInvoiceCompliance($signed_invoice_string, $invoice_hash, $binary_security_token, $secret));
// echo PHP_EOL;

// Generate QR Code
$qrCode = QrCode::create($qr)
    ->setEncoding(new Encoding('UTF-8'))
    ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
    ->setSize(300)
    ->setMargin(10)
    ->setForegroundColor(new Color(0, 0, 0))
    ->setBackgroundColor(new Color(255, 255, 255));

// Save QR Code to file
$writer = new PngWriter();
$logo = Logo::create(__DIR__ . '/assets/logo.png')
    ->setResizeToWidth(50)
    ->setPunchoutBackground(true);

$label = Label::create('Qr Phase-2')
    ->setTextColor(new Color(255, 0, 0));

$result = $writer->write($qrCode, $logo, $label);
$result->saveToFile(__DIR__ . '/assets/phase-2.png');

header('Content-Type: ' . $result->getMimeType());

echo $result->getString();
