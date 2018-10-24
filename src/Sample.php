<?php
/*
 * http://www.oioubl.info/classes/en/invoice.html
 * http://www.oioubl.net/validator/
 */

namespace NumNum\UBL;

use \DateTime as DateTime;

include '../vendor/autoload.php';

require 'classes/Invoice.php';
require 'Generator.php';


$generator          = new Generator();
$legalMonetaryTotal = new LegalMonetaryTotal();

// adress
$caddress = new Address();
$caddress->setStreetName('Résidence du chateau');
$caddress->setBuildingNumber(5);
$caddress->setCityName('Castle Land');
$caddress->setPostalZone('38760');
$country = new Country();
$country->setIdentificationCode('FR');
$caddress->setCountry($country);

// company
$company  = new Party();
$company->setName('Company Machin');
//$company->setPhysicalLocation($caddress);
$company->setPostalAddress($caddress);

// client
$client = new Party();
$client->setName('My client');
$client->setPostalAddress($caddress);

//product
$item   = new Item();
$item->setName('Product Name');
$item->setDescription('Product Description');

//price
$price= new Price();
$price->setBaseQuantity(1);
$price->setUnitCode('Unit');
$price->setPriceAmount(10);

//line
$invoiceLine = new InvoiceLine();
$invoiceLine->setId(0);
$invoiceLine->setItem($item);
$invoiceLine->setPrice($price);
$invoiceLine->setInvoicedQuantity(1);

$invoiceLines = [$invoiceLine];

// taxe TVA
$TaxScheme    = new TaxScheme();
$TaxScheme->setId(0);
$taxCategory = new TaxCategory();
$taxCategory->setId(0);
$taxCategory->setName('TVA20');
$taxCategory->setPercent(.2);
$taxCategory->setTaxScheme($TaxScheme);

// taxes
$taxTotal    = new TaxTotal();
$taxSubTotal = new TaxSubTotal();
$taxSubTotal->setTaxableAmount(10);
$taxSubTotal->setTaxAmount(2);
$taxSubTotal->setTaxCategory($taxCategory);
$taxTotal->addTaxSubTotal($taxSubTotal);
$taxTotal->setTaxAmount($taxSubTotal->getTaxAmount());


// invoice
$invoice = new Invoice();
$invoice->setId(3);
$invoice->setIssueDate(new DateTime());
$invoice->setInvoiceTypeCode('invoiceTypeCode');
$invoice->setAccountingSupplierParty($company);
$invoice->setAccountingCustomerParty($client);
$invoice->setInvoiceLines($invoiceLines);
$legalMonetaryTotal->setPayableAmount(10+2);
$legalMonetaryTotal->setAllowanceTotalAmount(0);
$invoice->setLegalMonetaryTotal($legalMonetaryTotal);
$invoice->setTaxTotal($taxTotal);

header("Content-type: text/xml");
print($generator->invoice($invoice));
