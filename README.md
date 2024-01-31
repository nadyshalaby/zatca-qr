<div align="center">
  <h1>
  ZATCA-QR
  <br/>
  </h1>
  <p>
    An implementation of Saudi Arabia ZATCA's E-Invoicing requirements, processes, and standards in TypeScript. <br/>
  </p>
  Read the <a href="/docs">documentation PDFs</a> or <a href="https://zatca.gov.sa/en/E-Invoicing/SystemsDevelopers/Pages/TechnicalRequirementsSpec.aspx">Systems Developers</a> for more details.
  <br/>
  <br/>
  <p>

[![GitHub license](https://badgen.net/github/license/wes4m/zatca-xml-js?v=0.1.0)](https://github.com/wes4m/zatca-xml-js/blob/main/LICENSE)
<a href="https://github.com/nadyshalaby">
<img src="https://img.shields.io/badge/maintainer-nadyshalaby-blue"/>
</a>
  </p>
</div>

# Dependencies

If you plan on using the built in `EGS` module to generate keys, and CSR. The `EGS` module in the package is dependent
on <a href="https://www.openssl.org">OpenSSL</a> being installed in the system it's running on. It's being used to
generate an `ECDSA` key pair using the `secp256k1` curve. also to generate and sign a CSR.

All other parts of the package will work fine without `OpenSSL`. (meaning it supports react-native and other frameworks)

# Supports

All tha main futures required to on-board a new EGS. Create, sign, and report a simplified tax invoice are currently
supported.

- EGS (E-Invoice Generation System).
  - Creation/on-boarding (Compliance and Production x.509 CSIDs).
  - Cryptographic stamps generation.
- Simplified Tax Invoice.
  - Creation.
  - Signing.
  - Compliance checking.
  - Reporting.

# Installation

1. Download the package from github
2. Run `composer update` or `composer install` to install dependencies.
3. Run a local server to view the examples using `php -S localhost:8000`
4. Open http://localhost:8000/phase-1.php in your browser.
5. Open http://localhost:8000/phase-2.php in your browser.
6. Download the ZATCA QR Reader App from Google Play Store. <a href="https://play.google.com/store/apps/details?id=com.posbankbh.einvoiceqrreader&pcampaignid=web_share">**Zatca QR ReaderApp**</a>
7. Scan the QR code generated in the example.

# Usage

View full example at <a href="/phase-1.php">phase-1.php</a> and <a href="/phase-2.php">phase-2.php</a>.

```php
// New Invoice and EGS Unit
$egs = new \ZATCA\EGS($egs_unit);

$egs->production = false;

// New Keys & CSR for the EGS
list($private_key, $csr) = $egs->generateNewKeysAndCSR('solution_name');

// Issue a new compliance cert for the EGS
list($request_id, $binary_security_token, $secret) = $egs->issueComplianceCertificate('123345', $csr);

// Sign invoice
list($signed_invoice_string, $invoice_hash, $qr) = $egs->signInvoice($invoice, $egs_unit, $binary_security_token, $private_key);

// Check invoice compliance
echo($egs->checkInvoiceCompliance($signed_invoice_string, $invoice_hash, $binary_security_token, $secret));
echo PHP_EOL;
```

# Implementation

- General implementation (<a href="/docs/20220624_ZATCA_Electronic_Invoice_XML_Implementation_Standard_vF.pdf">More
  details</a>)
  - KSA Rules & Business
  - UBL 2.1 Spec
  - ISO EN16931
  - UN/CEFACT Code List 1001
  - ISO 3166
  - ISO 4217:2015
  - UN/CEFACT Code List 5305, D.16B
- Security standards (<a href="/docs/20220624_ZATCA_Electronic_Invoice_Security_Features_Implementation_Standards.pdf">
  More details</a>)
  - NCA National Cryptographic Standards (NCS - 1 : 2020)
  - NCDC Digital Signing Policy (Version 1.1: 2020)
  - ETSI EN 319 102-1
  - ETSI EN 319 132-1
  - ETSI EN 319 142-1
  - W3C XML-Signature Syntax and Processing
  - ETSI EN 319 122-1
  - IETF RFC 5035 (2007)
  - RFC 5280
  - ISO 32000-1
  - IETF RFC 5652 (2009)
  - RFP6749
  - NIST SP 56A

# Notice of Non-Affiliation and Disclaimer

`zatca-qr` is not affiliated, associated, authorized, endorsed by, or in any way officially connected with ZATCA (
Zakat, Tax and Customs Authority), or any of its subsidiaries or its affiliates. The official ZATCA website can be found
at https://zatca.gov.sa.

# Contribution

All contributions are appreciated.

I'm not planning on supporting `Tax Invoices` (Not simplified ones). If any one wants to tackle that part.
