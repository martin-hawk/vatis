<?php
require_once ('db.php');
require_once ('functions.php');
session_start();

page_protect($conn, '1', $_SESSION['user_company']);

if (!empty($_SESSION['date']) && !empty($_SESSION['productCode'][0])) {

    // save the customer information
    // set variables
    $contractorIndicator = false;
    $counterIndicator = false;
    $nameIndicator = false;
    $codeIndicator = false;
    $streetIndicator = false;
    $houseIndicator = false;
    $cityIndicator = false;
    $countryIndicator = false;
    unset($_SESSION['info']); // better unset

    // set SESSION variables
    $_SESSION['group'] = 'VIEP';
    $resultIDs = $conn->query("SELECT * FROM classifier_contractor_group WHERE CompanyCode='" .
        $_SESSION['user_company'] . "' AND GroupID='" . $_SESSION['group'] . "'");
    $resultID = $resultIDs->fetch_assoc();
    if ($resultID['CurrentNumber'] === null) {
        $ID = $resultID['RangeFrom'];
    } else {
        $ID = $resultID['CurrentNumber'] + 1;
    }

    $_SESSION['code'] = $conn->real_escape_string($_SESSION["customer_code"]);
    $_SESSION['vatCode'] = $conn->real_escape_string($_SESSION["customer_vat"]);
    $_SESSION['name'] = $conn->real_escape_string($_SESSION["customer_name"]);
    $_SESSION['street'] = $conn->real_escape_string($_SESSION['customer_street']);
    $_SESSION['house'] = $conn->real_escape_string($_SESSION['customer_house']);
    $_SESSION['flat'] = $conn->real_escape_string($_SESSION['customer_flat']);
    $_SESSION['postalCode'] = $conn->real_escape_string($_SESSION["customer_postalCode"]);
    $_SESSION['city'] = $conn->real_escape_string($_SESSION['customer_city']);
    $_SESSION['district'] = $conn->real_escape_string($_SESSION["customer_district"]);
    $_SESSION['country'] = $conn->real_escape_string($_SESSION["customer_country"]);
    $_SESSION['isLegal'] = 0;
    $_SESSION['isLT'] = 0;
    $_SESSION['isEU'] = 0;

    // check mandatory fields and set message to the user
    if (empty($_SESSION['name'])) {
        $_SESSION['info'] = "Nurodykite pavadinimą<br>";
    } else {
        $nameIndicator = true;
    }

    if (empty($_SESSION['code'])) {
        $_SESSION['info'] .= "Nurodykite įmonės kodą<br>";
    } else {
        $codeIndicator = true;
    }

    if (empty($_SESSION['street'])) {
        $_SESSION['info'] .= "Nurodykite įmonės gatvę<br>";
    } else {
        $streetIndicator = true;
    }

    if (empty($_SESSION['house'])) {
        $_SESSION['info'] .= "Nurodykite įmonės namo numerį<br>";
    } else {
        $houseIndicator = true;
    }

    if (empty($_SESSION['city'])) {
        $_SESSION['info'] .= "Nurodykite įmonės miestą<br>";
    } else {
        $cityIndicator = true;
    }

    if (empty($_SESSION['country'])) {
        $_SESSION['info'] .= "Nurodykite įmonės šalį<br>";
    } else {
        $countryIndicator = true;
    }

    // check before insert
    if ($nameIndicator == true && $codeIndicator == true && $streetIndicator == true &&
        $houseIndicator == true && $cityIndicator == true && $countryIndicator == true) {
        $insertContractor = "INSERT INTO `classifier_contractor` (`CompanyCode`, `ID`, `Code`, `VATCode`, `Name`, `Street`, `House`, `Flat`, `PostalCode`, `City`, `District`, `Country`, `IsLegal`, `IsLT`, `IsEU`, `ContractorsGroup`)
        VALUES ('{$_SESSION['user_company']}', '$ID', '{$_SESSION['code']}', '{$_SESSION['vatCode']}', '{$_SESSION['name']}', '{$_SESSION['street']}', '{$_SESSION['house']}', '{$_SESSION['flat']}', '{$_SESSION['postalCode']}', '{$_SESSION['city']}', '{$_SESSION['district']}', '{$_SESSION['country']}', '{$_SESSION['isLegal']}', '{$_SESSION['isLT']}', '{$_SESSION['isEU']}', '{$_SESSION['group']}')";

        if ($conn->query($insertContractor) === true) {
            $contractorIndicator = true;
        }

        if ($contractorIndicator === true) {
            // check if counter ID in range
            $checkCounter = "SELECT * FROM classifier_contractor_group WHERE CompanyCode='{$_SESSION['user_company']}' AND GroupID='{$_SESSION['group']}'";
            $counterResult = $conn->query($checkCounter);
            $counter = $counterResult->fetch_assoc();

            if ($counter['RangeFrom'] <= $ID && $counter['RangeTo'] >= $ID) {

                // update counter
                $updateCounter = "UPDATE classifier_contractor_group SET CurrentNumber='$ID' WHERE CompanyCode='{$_SESSION['user_company']}' AND GroupID='{$_SESSION['group']}'";
                if ($conn->query($updateCounter) === true) {
                    $counterIndicator = true;
                }

            }
        } else {
            $_SESSION['info'] = "Klientų numeracijos riba pasiekta";
        }
        // error message

        // display success or error message on save
        if ($contractorIndicator === true && $counterIndicator === true) {
            $_SESSION['info'] = "Klientas nr. " . $ID . " sėkmingai sukurtas";
            $_SESSION['customer_id'] = $ID;
        } else {
            $_SESSION['info'] = "Klaida! " . $con->error;
        }
    }


    // save invoice data
    $date = strtotime($_SESSION['date']);
    $year = date("Y", $date);
    $month = date("m", $date);

    // reserve the SystemID
    $entryresult = $conn->query("SELECT * FROM classifier_counter WHERE CompanyCode='" .
        $_SESSION['user_company'] . "' AND DocumentType='SD'");
    $entry = $entryresult->fetch_assoc();
    if ($entry['CurrentNumber'] === null) {
        $systemID = $entry['RangeFrom'];
    } else
        $systemID = $entry['CurrentNumber'] + 1;
    $invoiceSeries = $entry['Prefix'];

    $insert_header = "INSERT INTO document_sales_header (CompanyCode, Year, SystemID, Period, DocumentType, InvoiceSeries, DocumentNo, DocumentDate, PostingDate, DueDate, BankID, Customer, CurrencyDate, CurrencyCode, CurrencyRate, DocumentTotalNet, LocalTotalNet, DocumentTotalVAT, LocalTotalVAT, DocumentTotalGross, LocalTotalGross, Username, VATDate) VALUES ('{$_SESSION['user_company']}', '$year', '$systemID', '$month', 'SD', '$invoiceSeries', '{$_SESSION['invoiceNo']}', '{$_SESSION['date']}', '{$_SESSION['date']}', '{$_SESSION['due_date']}', '{$_SESSION['bankID']}', '{$_SESSION['customer_id']}', '{$_SESSION['date']}', '{$_SESSION['currency']}', '{$_SESSION['exchangeRate']}', '{$_SESSION['documentNetTotal']}', '{$_SESSION['localNetTotal']}', '{$_SESSION['documentVATTotal']}', '{$_SESSION['localVATTotal']}', '{$_SESSION['documentGrossTotal']}', '{$_SESSION['localGrossTotal']}', '{$_SESSION['user_id']}', '{$_SESSION['date']}')"; // {$_SESSION['headerComment']}

    $header_indicator = false;
    $items_indicator = false;
    $counter_indicator = false;
    if ($conn->query($insert_header) === true)
        $header_indicator = true;

    if ($header_indicator == true) {
        // get all values from SESSION array
        for ($n = 0; $n <= $_SESSION['count']; $n++) {
            $lineID = $n + 1;
            $line_item[$n] = "('{$_SESSION['user_company']}', '$year', '$systemID', '$lineID', 'K', '{$_SESSION['productCode'][$n]}', '{$_SESSION['quantity'][$n]}', '{$_SESSION['documentPrice'][$n]}', '{$_SESSION['localPrice'][$n]}', '{$_SESSION['vatRate']}', '{$_SESSION['documentVAT'][$n]}', '{$_SESSION['localVAT'][$n]}', '{$_SESSION['documentNet'][$n]}', '{$_SESSION['localNet'][$n]}', '{$_SESSION['documentGross'][$n]}', '{$_SESSION['localGross'][$n]}')"; // {$_SESSION['lineComment'][$n]}
        }

        // append the query with each set
        $insert_items = "INSERT INTO document_sales_items (CompanyCode, Year, SystemID, LineID, DebitCreditID, ProductCode, Quantity, DocumentPrice, LocalPrice, VATRate, DocumentVAT, LocalVAT, DocumentNet, LocalNet, DocumentGross, LocalGross) VALUES "; // Pridėti Comment gale

        for ($n = 0; $n <= $_SESSION['count']; $n++) {
            if ($n == 0)
                $insert_items .= $line_item[$n];
            if ($n > 0 && n <= $_SESSION['count'])
                $insert_items .= ", " . $line_item[$n];
        }

        // excute the query
        if ($conn->query($insert_items) === true) {
            $items_indicator = true;
        }
    }

    if ($items_indicator === true) {
        // update counter
        $update_counter = "UPDATE classifier_counter SET CurrentNumber='$systemID' WHERE CompanyCode='{$_SESSION['user_company']}' AND DocumentType='SD'"; // padaryti tikrinimą, ar į Range telpa
        if ($conn->query($update_counter) === true) {
            $counter_indicator = true;
        }
    }

    if (($header_indicator === true) && ($items_indicator === true) && ($counter_indicator
        === true)) {
        $_SESSION['info'] = "PVM sąskaita faktūra Nr. " . $invoiceSeries . sprintf('%05d',
            $_SESSION['invoiceNo']) . " sėkmingai išsaugota";
        $_SESSION['isSaved'] = true;
    } else {
        $_SESSION['info'] = "Klaida! " . $conn->error;
    }
} else
    $_SESSION['info'] = 'Nurodykite privalomą informaciją';

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>