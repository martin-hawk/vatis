<?php
require_once ('db.php');
require_once ('functions.php');
require('tfpdf/tfpdf.php');
if (session_status() == PHP_SESSION_NONE) session_start();
page_protect($conn, '1', $_SESSION['user_company']);

if (isset($_POST['language'])) $_SESSION['language'] = $_POST['language'];
if (isset($_SESSION['isSaved']) && $_SESSION['isSaved'] === true) {
	
// PDF formavimas
class PDF extends tFPDF
{
// Page header
function Header()
{	
    // Set Unicode font
	$this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$this->AddFont('DejaVuBold','','DejaVuSansCondensed-Bold.ttf',true);
	$this->AddFont('DejaVuItalic','','DejaVuSansCondensed-Oblique.ttf',true);
	// Arial bold 20
    $this->SetFont('DejaVuBold','',20);
    // Company name
    $this->Cell(0,10,$_SESSION['companyName'],0,0,'C');
	// Line break
    $this->Ln(15);

    // Title and Invoice date
		if (isset($_SESSION['language']) && $_SESSION['language'] == "en") {
			$ino = 'Credit Note No. ' . $_SESSION['prefix'] . sprintf('%05d', $_SESSION['invoiceNo']);
			$idate = 'Date: ' . $_SESSION['date'];
		} else {
			$ino = 'Kreditinė sąskaita Serija ' . $_SESSION['prefix'] . ' Nr. ' . sprintf('%05d', $_SESSION['invoiceNo']);
			$idate = 'Išrašymo data: ' . $_SESSION['date'];
	}
			$this->SetFont('DejaVuBold','',12);
			$this->Cell(0,5,$ino,0,2,'C');
			$this->Cell(0,5,$idate,0,2,'C');
	
	// Line break
    $this->Ln(15);
	
	// Company information
	// Arial 12
	$this->SetFont('DejaVu','',12);
	if (isset($_SESSION['language']) && $_SESSION['language'] == "en") {
		$coName = 'Supplier: ' . $_SESSION['companyName'];
    	$coCode = 'Code: '. $_SESSION['company_code'];
    	$coVAT = 'VAT Code: ' . $_SESSION['company_vat'];
		$coAddress1 = 'Address: ' . $_SESSION["company_address1"];
		$coAddress2 = $_SESSION["company_address2"];
		$coAddress3 = $_SESSION["company_address3"];
		$coAddress4 = $_SESSION["company_address4"];
		}
	else {
		$coName = 'Tiekėjas: ' . $_SESSION['companyName'];
    	$coCode = 'Įm. kodas: ' . $_SESSION["company_code"];
    	$coVAT = 'PVM kodas: ' . $_SESSION["company_vat"];
     	$coAddress1 = 'Adresas: ' . $_SESSION["company_address1"];
		$coAddress2 = $_SESSION["company_address2"];
		$coAddress3 = $_SESSION["company_address3"];
		$coAddress4 = $_SESSION["company_address4"];
		}
		
		if (isset($_SESSION['language']) && $_SESSION['language'] == "en") {
		$cuName = 'Customer: ' . $_SESSION['customer_name'];
    	$cuCode = 'Code: '. $_SESSION['customer_code'];
    	$cuVAT = 'VAT Code: ' . $_SESSION['customer_vat'];
		$cuAddress1 = 'Address: ' . $_SESSION["customer_address1"];
		$cuAddress2 = $_SESSION["customer_address2"];
		$cuAddress3 = $_SESSION["customer_address3"];
		$cuAddress4 = $_SESSION["customer_address4"];
		}
	else {
		$cuName = 'Pirkėjas: ' . $_SESSION['customer_name'];
    	$cuCode = 'Įm. kodas: ' . $_SESSION["customer_code"];
    	$cuVAT = 'PVM kodas: ' . $_SESSION["customer_vat"];
     	$cuAddress1 = 'Adresas: ' . $_SESSION["customer_address1"];
		$cuAddress2 = $_SESSION["customer_address2"];
		$cuAddress3 = $_SESSION["customer_address3"];
		$cuAddress4 = $_SESSION["customer_address4"];
		}
	
	$this->Cell(0,5,$coName,0,0,'L'); $this->Cell(0,5,$cuName,0,1,'R');
	$this->Cell(0,5,$coCode,0,0,'L'); $this->Cell(0,5,$cuCode,0,1,'R');
	$this->Cell(0,5,$coVAT,0,0,'L'); $this->Cell(0,5,$cuVAT,0,1,'R');
	$this->Cell(0,5,$coAddress1,0,0,'L'); $this->Cell(0,5,$cuAddress1,0,1,'R');
	$this->Cell(0,5,$coAddress2,0,0,'L'); $this->Cell(0,5,$cuAddress2,0,1,'R');
	$this->Cell(0,5,$coAddress3,0,0,'L'); $this->Cell(0,5,$cuAddress3,0,1,'R');
	$this->Cell(0,5,$coAddress4,0,0,'L'); $this->Cell(0,5,$cuAddress4,0,1,'R');
}

function Footer()
{
    // Position at 5 cm from bottom
    $this->SetY(-50);
	if (isset($_SESSION['language']) && $_SESSION['language'] == "en") {
		$reverseText = 'Credited VAT Invoice No. ' . $_SESSION['creditPrefix'] . sprintf('%05d', $_SESSION['creditNo']) . ' dated ' . $_SESSION['creditDate'];
		$payMethod = 'Payment details';
		$iban = 'Account: ';
	}
	else {
		$reverseText = 'Kredituojanti ' . $_SESSION['creditDate'] . ' PVM sąskaitą faktūrą Serija ' . $_SESSION['creditPrefix'] . ' Nr. ' . sprintf('%05d', $_SESSION['creditNo']);
		$payMethod = 'Mokėjimo informacija';
		$iban = 'Sąskaita: ';
	}

	$this->SetTextColor(255,0,0);
	$this->SetFont('DejaVu','',10);	
	$this->Cell(0,5,$reverseText,0,2,'L');
	
	// Line break
	$this->Ln();
    
	$this->SetTextColor(0);
	
	//$this->Cell(0,5,$thanks,0,2,'L');
	
	$this->Ln();
	
	$this->SetFont('DejaVuBold','',10);
    $this->Cell(0,5,$payMethod,0,2,'L');
    $this->Cell(0,5,$_SESSION['bankName'],0,2,'L');
    $this->Cell(0,5,$iban . $_SESSION['bankIBAN'],0,2,'L');	
    $this->SetFont('DejaVu','',10);
	$this->Cell(0,5,'SWIFT/BIC: ' . $_SESSION['bankSWIFT'],0,2,'L');	
}

// Colored table
function InvoiceLines()
{
    // Colors, line width and bold font
    $this->SetFillColor(160,160,160);
    $this->SetTextColor(0,0,0);
    $this->SetDrawColor(160,160,160);
    $this->SetLineWidth(.3);
    $this->SetFont('DejaVuBold','',9);
    // Header
    $this->Ln(20);
    // Column headings
	if (isset($_SESSION['language']) && $_SESSION['language'] == "en") {
	$header = array('No.', 'Description', 'Qty', 'Units', 'Price', 'VAT Rate', 'VAT Amount', 'Total', 'Total (incl. VAT)');
	} else {
	$header = array('Nr.', 'Pavadinimas', 'Kiekis', 'Mat. vnt.', 'Kaina', 'PVM tarifas', 'PVM suma', 'Suma', 'Suma (su PVM)');
	}
	$cellWidth = array(7, 35, 15, 15, 20, 22, 25, 20, 30);
    for($i=0;$i<count($header);$i++)
        $this->Cell($cellWidth[$i],7,$header[$i],1,0,'C',true);
	
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,224,224);
    $this->SetTextColor(0);
    $this->SetFont('DejaVu','',9);
    // Data
    $fill = false;
	
    for ($i = 0; $i <= $_SESSION['count']; $i++)  {
		// Set different language values
		if(isset($_SESSION['language']) && $_SESSION['language'] == "en") {
			$product[$i] = $_SESSION['productForeignDescription'][$i];
			$uom[$i] = $_SESSION['uomForeignDescription'][$i];
		} else {
			$product[$i] = $_SESSION['productDescription'][$i];
			$uom[$i] = $_SESSION['uom'][$i];
		}
		
        $this->Cell($cellWidth[0],5,$i+1,'LR',0,'L',$fill);
        $this->Cell($cellWidth[1],5,$product[$i],'LR',0,'L',$fill);
        $this->Cell($cellWidth[2],5,number_format($_SESSION['quantity'][$i], 2, ',', ''),'LR',0,'R',$fill);
        $this->Cell($cellWidth[3],5,$uom[$i],'LR',0,'R',$fill);
		$this->Cell($cellWidth[4],5,number_format($_SESSION['documentPrice'][$i], 2, ',', ''),'LR',0,'R',$fill);
		$this->Cell($cellWidth[5],5,number_format($_SESSION['vatPercentage'], 2, ',', ''),'LR',0,'R',$fill);
		$this->Cell($cellWidth[6],5,number_format($_SESSION['documentVAT'][$i], 2, ',', ''),'LR',0,'R',$fill);
		$this->Cell($cellWidth[7],5,number_format($_SESSION['documentNet'][$i], 2, ',', ''),'LR',0,'R',$fill);
		$this->Cell($cellWidth[8],5,number_format($_SESSION['documentGross'][$i], 2, ',', ''),'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($cellWidth),0,'','T');
	$this->Ln();
	
	// Totals table
	if (isset($_SESSION['language']) && $_SESSION['language'] == "en") {
			$netT = 'Net Total ';
			$vatT = 'VAT Amount';
			$grossT = 'TOTAL (incl. VAT)';
		}
		else {
			$netT = 'Iš viso';
			$vatT = 'PVM suma';
			$grossT = 'Iš viso (su PVM)';
		}
		
		$this->SetFont('DejaVu','',14);
		$this->Cell(120,10,$netT,0,0,'R'); $this->Cell(60,10,$_SESSION['documentNetTotal'],0,0,'R'); $this->Cell(0,10,$_SESSION['currency'],0,1,'R');
		$this->Cell(120,10,$vatT,0,0,'R'); $this->Cell(60,10,$_SESSION['documentVATTotal'],0,0,'R'); $this->Cell(0,10,$_SESSION['currency'],0,1,'R');
		$this->Cell(120,10,$grossT,0,0,'R'); $this->Cell(60,10,$_SESSION['documentGrossTotal'],0,0,'R'); $this->Cell(0,10,$_SESSION['currency'],0,1,'R');
		
	if (isset($_SESSION['currency']) && $_SESSION['currency'] != "EUR") {
		$this->SetFont('DejaVuItalic','',12);
		$this->SetTextColor(80,80,80);
		$this->Cell(120,7,$netT,0,0,'R'); $this->Cell(60,7,$_SESSION['localNetTotal'],0,0,'R'); $this->Cell(0,7,'EUR',0,1,'R');
		$this->Cell(120,7,$vatT,0,0,'R'); $this->Cell(60,7,$_SESSION['localVATTotal'],0,0,'R'); $this->Cell(0,7,'EUR',0,1,'R');
		$this->Cell(120,7,$grossT,0,0,'R'); $this->Cell(60,7,$_SESSION['localGrossTotal'],0,0,'R'); $this->Cell(0,7,'EUR',0,1,'R');
	}
	
	$this->Ln(10);
	
	$lang = $_SESSION['language'];
	$amount = (float)$_SESSION['documentGrossTotal'];
	$currency = $_SESSION['currency'];

if ($lang == "en") {
	$words = new NumberFormatter("en", NumberFormatter::SPELLOUT);
	$whole = floor($amount);
	$fraction = number_format((($amount - $whole) * 100), 0);
	$toWords = 'Amount in words: ' . $words->format($whole) . ' ' . $currency . ', ' . $fraction . ' cent(s)';
	
	$remark = "Remark: ";
	$vatreason = $_SESSION['vatForeignReason'];
	$issuer = 'Issued by: ';
} 
else {
	$zodziai = new NumberFormatter("lt_LT", NumberFormatter::SPELLOUT);
	$whole = floor($amount);
	$fraction = number_format((($amount - $whole) * 100), 0);
	$toWords = 'Suma žodžiais: ' . $zodziai->format($whole) . ' ' . $currency . ', ' . $fraction . ' ct';
	
	$remark = 'Pastaba: ';
	$vatreason = $_SESSION['vatReason'];
	$issuer = 'Sąskaitą išrašė: ';
}
	$this->SetTextColor(0);
	$this->SetFont('DejaVu','',12);
	$this->Cell(0,0,$toWords,0,2,'R');
	
	$this->Ln(10);
	$this->SetFont('DejaVu','',10);
	$this->Cell(0,0,$remark . $_SESSION['vatRate'] . " - " . $vatreason,0,2,'L');
	
	$this->Ln(10);
	$this->SetFont('DejaVu','',10);
	$this->Cell(0,0,$issuer . $_SESSION['f_name'] . " " . $_SESSION['l_name'],0,2,'L');
}
}
	
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	// Set unicode font
	$pdf->SetFont('DejaVu','',12);
	
	// Produce table
	$pdf->InvoiceLines();
	
	$pdf->Output();
} else {
	include 'header.php';
	echo '<table align="center"><tr><td>';
	echo '<div align="center" style="width:500px; border: 3px solid #090; margin-top:20px; margin-bottom: 1em; padding: 10px; border-radius: 15px 40px;">';
	$_SESSION['info'] = "Prieš formuojant PDF, PVM sąskaita faktūra turi būti išsaugota";
  	echo $_SESSION['info'];
	echo '</div></td></tr></table>';
	include 'footer.php';
}
?>