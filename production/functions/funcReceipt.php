<?php
include '../../cradle_config.php';
include '../sql/sqlOpening.php';
global $conn;
global $R1rec;
global $R5rec;
global $SWrec;
global $TSrec;

$sql = $R1rec;
$resultR1 = $conn->query($sql);
if ($resultR1->num_rows > 0) {
    while($rowR1 = $resultR1->fetch_assoc()) {
        $receiptR1=$rowR1["r001"];
    }
}

$sqlR5 = $R5rec;
$resultR5 = $conn->query($sqlR5);
if ($resultR5->num_rows > 0) {
    while ($rowR5 = $resultR5->fetch_assoc()) {
        $receiptR5 = $rowR5["r005"];
    }
}

$sqlSW = $SWrec;
$resultSW = $conn->query($sqlSW);
if ($resultSW->num_rows > 0) {
    while ($rowSW = $resultSW->fetch_assoc()) {
        $receiptSW = $rowSW["sw001"];
    }
}

$sqlTS = $TSrec;
$resultTS = $conn->query($sqlTS);
if ($resultTS->num_rows > 0) {
    while ($rowTS = $resultTS->fetch_assoc()) {
        $receiptTS = $rowTS["ts001"];
    }
}

$sqlMD = $MDrec;
$resultMD = $conn->query($sqlMD);
if ($resultMD->num_rows > 0) {
    while ($rowMD = $resultMD->fetch_assoc()) {
        $receiptMD = $rowMD["md001"];
    }
}

$sqlJP = $JPrec;
$resultJP = $conn->query($sqlJP);
if ($resultJP->num_rows > 0) {
    while ($rowJP = $resultJP->fetch_assoc()) {
        $receiptJP = $rowJP["jp001"];
    }
}

$sqlCH = $CHrec;
$resultCH = $conn->query($sqlCH);
if ($resultCH->num_rows > 0) {
    while ($rowCH = $resultCH->fetch_assoc()) {
        $receiptCH = $rowCH["ch001"];
    }
}

$sqlSP = $SPrec;
$resultSP = $conn->query($sqlSP);
if ($resultSP->num_rows > 0) {
    while ($rowSP = $resultSP->fetch_assoc()) {
        $receiptSP = $rowSP["sp001"];
    }
}

$sqlFL =$FLrec;
$resultFL = $conn->query($sqlFL);
if ($resultFL->num_rows > 0) {
    while ($rowFL = $resultFL->fetch_assoc()) {
        $receiptFL = $rowFL["fl001"];
    }
}

$sqlMI =$MIrec;
$resultMI = $conn->query($sqlMI);
if ($resultMI->num_rows > 0) {
    while ($rowMI = $resultMI->fetch_assoc()) {
        $receiptMI = $rowMI["m001"];
    }
}

$sqlBC =$BCrec;
$resultBC = $conn->query($sqlBC);
if ($resultBC->num_rows > 0) {
    while ($rowBC = $resultBC->fetch_assoc()) {
        $receiptBC = $rowBC["bc001"];
    }
}

$sqlPL =$PLrec;
$resultPL = $conn->query($sqlPL);
if ($resultPL->num_rows > 0) {
    while ($rowPL = $resultPL->fetch_assoc()) {
        $receiptPL = $rowPL["pl001"];
    }
}

$sqlR7 =$R7rec;
$resultR7 = $conn->query($sqlR7);
if ($resultR7->num_rows > 0) {
    while ($rowR7 = $resultR7->fetch_assoc()) {
        $receiptR7 = $rowR7["r007"];
    }
}

$sqlTT =$TTrec;
$resultTT = $conn->query($sqlTT);
if ($resultTT->num_rows > 0) {
    while ($rowTT = $resultTT->fetch_assoc()) {
        $receiptTT = $rowTT["TOTAL"];
    }
}

global $R7man;
$resultMR7 = $conn->query($R7man);
if ($resultMR7->num_rows > 0) {
    while ($rowMR7 = $resultMR7->fetch_assoc()) {
        $receiptMR7 = $rowMR7["total"];
    }
}

global $R1man;
$resultMR1 = $conn->query($R1man);
if ($resultMR1->num_rows > 0) {
    while ($rowMR1 = $resultMR1->fetch_assoc()) {
        $receiptMR1 = $rowMR1["total"];
    }
}

global $PLman;
$resultMPL = $conn->query($PLman);
if ($resultMPL->num_rows > 0) {
    while ($rowMPL = $resultMPL->fetch_assoc()) {
        $receiptMPL = $rowMPL["total"];
    }
}

global $R5man;
$resultMR5 = $conn->query($R5man);
if ($resultMR5->num_rows > 0) {
    while ($rowMR5 = $resultMR5->fetch_assoc()) {
        $receiptMR5 = $rowMR5["total"];
    }
}

global $SWman;
$resultMSW = $conn->query($SWman);
if ($resultMSW->num_rows > 0) {
    while ($rowMSW = $resultMSW->fetch_assoc()) {
        $receiptMSW = $rowMSW["total"];
    }
}

global $TSman;
$resultMTS = $conn->query($TSman);
if ($resultMTS->num_rows > 0) {
    while ($rowMTS = $resultMTS->fetch_assoc()) {
        $receiptMTS = $rowMTS["total"];
    }
}

global $MDman;
$resultMMD = $conn->query($MDman);
if ($resultMMD->num_rows > 0) {
    while ($rowMMD = $resultMMD->fetch_assoc()) {
        $receiptMMD = $rowMMD["total"];
    }
}


global $JPman;
$resultMJP = $conn->query($JPman);
if ($resultMJP->num_rows > 0) {
    while ($rowMJP = $resultMJP->fetch_assoc()) {
        $receiptMJP = $rowMJP["total"];
    }
}


global $CHman;
$resultMCH = $conn->query($CHman);
if ($resultMCH->num_rows > 0) {
    while ($rowMCH = $resultMCH->fetch_assoc()) {
        $receiptMCH = $rowMCH["total"];
    }
}

global $FLman;
$resultMFL = $conn->query($FLman);
if ($resultMFL->num_rows > 0) {
    while ($rowMFL = $resultMFL->fetch_assoc()) {
        $receiptMFL = $rowMFL["total"];
    }
}

global $SPman;
$resultMSP = $conn->query($SPman);
if ($resultMSP->num_rows > 0) {
    while ($rowMSP = $resultMSP->fetch_assoc()) {
        $receiptMSP = $rowMSP["total"];
    }
}

global $BCman;
$resultMBC = $conn->query($BCman);
if ($resultMSP->num_rows > 0) {
    while ($rowMBC = $resultMBC->fetch_assoc()) {
        $receiptMBC = $rowMBC["total"];
    }
}
global $MIman;
$resultMMI = $conn->query($MIman);
if ($resultMMI->num_rows > 0) {
    while ($rowMMI = $resultMMI->fetch_assoc()) {
        $receiptMMI = $rowMMI["total"];
    }
}