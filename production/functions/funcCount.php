<?php
include 'funcReceipt.php';
include'../../cradle_config.php';
include '../sql/sqlOpening.php';

global $conn;

//general china

if ($resultR1F = mysqli_query($conn, $sqlR1)) {
    while ($rowR1F = mysqli_fetch_array($resultR1F)) {
        $partR1F = $rowR1F['Part Number'];
        $partDescR1F = $rowR1F['Part Description'];
        $partQtyR1F = $rowR1F['qty'];
    }
}
if ($resultR1 = mysqli_query($conn, $R1rec)) {
    while ($rowR1 = mysqli_fetch_array($resultR1)) {
        $receiptR1F = $rowR1["r001"];
        if ($receiptR1F <= '0'){
            $receiptR1F = '0';
        }
    }
}
if ($resultR1Man = mysqli_query($conn, $R1man)) {
    while ($rowR1M = mysqli_fetch_array($resultR1Man)) {
        $receiptR1Man = $rowR1M["total"];
        if ($receiptR1Man <= '0'){
            $receiptR1Man = '0';
        }
    }
}
if ($resultR1Con = mysqli_query($conn, $R1Con)) {
    while ($rowR1Con = mysqli_fetch_array($resultR1Con)) {
        $conR1 = $rowR1Con["consumption"];
        if ($conR1 <= '0'){
            $conR1 = '0';
        }
    }
}
if ($resultR1C = mysqli_query($conn, $sqlR1C)) {
    while ($rowR1C = mysqli_fetch_array($resultR1C)) {
        $R1C = $rowR1C['qty'];
    }
}

if ($resultR5F = mysqli_query($conn, $sqlR5)) {
    while ($rowR5F = mysqli_fetch_array($resultR5F)) {
        $partR5F = $rowR5F['Part Number'];
        $partDescR5F = $rowR5F['Part Description'];
        $partQtyR5F = $rowR5F['qty'];
    }
}
if ($resultR5 = mysqli_query($conn, $R5rec)) {
    while ($rowR5 = mysqli_fetch_array($resultR5)) {
        $receiptR5F = $rowR5["r005"];
        if ($receiptR5F <= '0'){
            $receiptR5F = '0';
        }
    }
}
if ($resultR5Man = mysqli_query($conn, $R5man)) {
    while ($rowR5M = mysqli_fetch_array($resultR5Man)) {
        $receiptR5Man = $rowR5M["total"];
        if ($receiptR5Man <= '0'){
            $receiptR5Man = '0';
        }
    }
}
if ($resultR5Con = mysqli_query($conn, $R5Con)) {
    while ($rowR5Con = mysqli_fetch_array($resultR5Con)) {
        $conR5 = $rowR5Con["consumption"];
        if ($conR5<= '0'){
            $conR5 = '0';
        }
    }
}
if ($resultR5C = mysqli_query($conn, $sqlR5C)) {
    while ($rowR5C = mysqli_fetch_array($resultR5C)) {
        $R5C = $rowR5C['qty'];
    }
}



if ($resultSwF = mysqli_query($conn, $sqlSw)) {
    while ($rowSwF = mysqli_fetch_array($resultSwF)) {
        $partSwF = $rowSwF['Part Number'];
        $partDescSwF = $rowSwF['Part Description'];
        $partQtySwF = $rowSwF['qty'];
    }
}
if ($resultSw = mysqli_query($conn, $SWrec)) {
    while ($rowSw = mysqli_fetch_array($resultSw)) {
        $receiptSw = $rowSw["sw001"];
        if ($receiptSw <= '0'){
            $receiptSw = '0';
        }
    }
}
if ($resultSwMan = mysqli_query($conn, $SWman)) {
    while ($rowSwM = mysqli_fetch_array($resultSwMan)) {
        $receiptSwMan = $rowSwM["total"];
        if ($receiptSwMan <= '0'){
            $receiptSwMan = '0';
        }
    }
}
if ($resultSwCon = mysqli_query($conn, $SWCon)) {
    while ($rowSwCon = mysqli_fetch_array($resultSwCon)) {
        $conSW = $rowSwCon['consumption'];
        if ($conSW <= '0') { $conSW = '0'; }
    }
}
if ($resultSwC = mysqli_query($conn, $sqlSwC)) {
    while ($rowSwC = mysqli_fetch_array($resultSwC)) {
        $SwC = $rowSwC['qty'];
    }
}

if ($resultMd = mysqli_query($conn, $sql8)) {
    while ($rowMd = mysqli_fetch_array($resultMd)) {
        $partMd = $rowMd['Part Number'];
        $partDescMd = $rowMd['Part Description'];
        $partQtyMd = $rowMd['qty'];
    }
}
if ($resultMdM = mysqli_query($conn, $MDrec)) {
    while ($rowMdM = mysqli_fetch_array($resultMdM)) {
        $receiptMd = $rowMdM["md001"];
        if ($receiptMd<= '0'){
            $receiptMd = '0';
        }
    }
}
if ($resultM8Man = mysqli_query($conn, $MDman)) {
    while ($rowMdMan = mysqli_fetch_array($resultM8Man)) {
        $receiptMdMan = $rowMdMan["total"];
        if ($receiptMdMan <= '0'){
            $receiptMdMan = '0';
        }
    }
}
if ($resultMdCon = mysqli_query($conn, $MDCon)) {
    while ($rowMdCon = mysqli_fetch_array($resultMdCon)) {
        $conMD = $rowMdCon['consumption'];
        if ($conMD <= '0'){
            $conMD = '0';
        }
    }
}
if ($resultMdC = mysqli_query($conn, $sql8C)) {
    while ($rowMdC = mysqli_fetch_array($resultMdC)) {
        $MdC = $rowMdC['qty'];
    }
}


if ($resultJpF = mysqli_query($conn, $sqlJapan)) {
    while ($rowJp = mysqli_fetch_array($resultJpF)) {
        $partJp = $rowJp['Part Number'];
        $partDescJp = $rowJp['Part Description'];
        $partQtyJp = $rowJp['qty'];
    }
}
if ($resultJp = mysqli_query($conn, $JPrec)) {
    while ($rowJp = mysqli_fetch_array($resultJp)) {
        $receiptJp = $rowJp["jp001"];
        if ($receiptJp <= '0'){
            $receiptJp = '0';
        }
    }
}
if ($resultJpMan = mysqli_query($conn, $JPman)) {
    while ($rowJpM = mysqli_fetch_array($resultJpMan)) {
        $receiptJpMan = $rowJpM["total"];
        if ($receiptJpMan <= '0'){
            $receiptJpMan = '0';
        }
    }
}
if ($resultJpCon = mysqli_query($conn, $JPCon)) {
    while ($rowJpCon = mysqli_fetch_array($resultJpCon)) {
        $conJP = $rowJpCon['consumption'];
        if ($conJP <= '0'){
            $conJP = '0';
        }
    }
}
if ($resultJpC = mysqli_query($conn, $sqlJapanC)) {
    while ($rowJpC = mysqli_fetch_array($resultJpC)) {
        $JpC = $rowJpC['qty'];
    }
}

if ($resultChF = mysqli_query($conn, $sqlChina)) {
    while ($rowCh = mysqli_fetch_array($resultChF)) {
        $partCh = $rowCh['Part Number'];
        $partDescCh = $rowCh['Part Description'];
        $partQtyCh = $rowCh['qty'];
    }
}
if ($resultCh = mysqli_query($conn, $CHrec)) {
    while ($rowCh = mysqli_fetch_array($resultCh)) {
        $receiptCh = $rowCh["ch001"];
        if ($receiptCh <= '0'){
            $receiptCh = '0';
        }
    }
}
if ($resultChMan = mysqli_query($conn, $CHman)) {
    while ($rowChM = mysqli_fetch_array($resultChMan)) {
        $receiptChMan = $rowChM["total"];
        if ($receiptChMan <= '0'){
            $receiptChMan = '0';
        }
    }
}
if ($resultChCon = mysqli_query($conn, $CHCon)) {
    while ($rowChCon  = mysqli_fetch_array($resultChCon)) {
        $conCH = $rowChCon['consumption'];
        if ($conCH <= '0') { $conCH = '0'; }
    }
}
if ($resultChC = mysqli_query($conn, $sqlChinaC)) {
    while ($rowChC = mysqli_fetch_array($resultChC)) {
        $ChC = $rowChC['qty'];
    }
}

if ($result = mysqli_query($conn, $sqlSpain)) {
    while ($row = mysqli_fetch_array($result)) {
        $partSpF = $row['Part Number'];
        $partDescSpF = $row['Part Description'];
        $partQtySpF = $row['qty'];
    }
}
if ($resultSPF = mysqli_query($conn, $SPrec)) {
    while ($rowSPF = mysqli_fetch_array($resultSPF)) {
        $receiptSpF = $rowSPF["sp001"];
        if ($receiptSpF <= '0') {
            $receiptSpF = '0';
        }
    }
}
if ($resultSpManF = mysqli_query($conn, $SPman)) {
    while ($rowSpMF = mysqli_fetch_array($resultSpManF)) {
        $receiptSpManF = $rowSpMF["total"];
        if ($receiptSpManF <= '0') {
            $receiptSpManF = '0';
        }
    }
}
if ($resultSpCon = mysqli_query($conn, $SPCon)) {
    while ($rowSpCon  = mysqli_fetch_array($resultSpCon)) {
        $conSP = $rowSpCon['consumption'];
        if ($conSP <= '0') { $conSP = '0'; }
    }
}
if ($resultSpC = mysqli_query($conn, $sqlSpainC)) {
    while ($rowSpC = mysqli_fetch_array($resultSpC)) {
        $SpC = $rowSpC['qty'];
    }
}

if ($resultFlF = mysqli_query($conn, $sqlFilter)) {
    while ($rowFl = mysqli_fetch_array($resultFlF)) {
        $partFl = $rowFl['Part Number'];
        $partDescFl = $rowFl['Part Description'];
        $partQtyFl = $rowFl['qty'];
    }
}
if ($resultFl = mysqli_query($conn, $FLrec)) {
    while ($rowFl = mysqli_fetch_array($resultFl)) {
        $receiptFl = $rowFl["fl001"];
        if ($receiptFl <= '0'){
            $receiptFl = '0';
        }
    }
}
if ($resultFlMan = mysqli_query($conn, $FLman)) {
    while ($rowFlM = mysqli_fetch_array($resultFlMan)) {
        $receiptFlMan = $rowFlM["total"];
        if ($receiptFlMan <= '0'){
            $receiptFlMan = '0';
        }
    }
}
if ($resultFlCon = mysqli_query($conn, $FLCon)) {
    while ($rowFlCon  = mysqli_fetch_array($resultFlCon)) {
        $conFL = $rowFlCon['consumption'];
        if ($conFL <= '0') { $conFL = '0'; }
    }
}
if ($resultFlC = mysqli_query($conn, $sqlFilterC)) {
    while ($rowFlC = mysqli_fetch_array($resultFlC)) {
        $FlC = $rowFlC['qty'];
    }
}

if ($result8HF = mysqli_query($conn, $sql8H)) {
    while ($row8H = mysqli_fetch_array($result8HF)) {
        $part8HF = $row8H['Part Number'];
        $partDesc8HF = $row8H['Part Description'];
        $partQty8HF = $row8H['qty'];
    }
}
if ($result8H = mysqli_query($conn, $MIrec)) {
    while ($row8H = mysqli_fetch_array($result8H)) {
        $receipt8H = $row8H["m001"];
        if ($receipt8H <= '0'){
            $receipt8H = '0';
        }
    }
}
if ($result8HMan = mysqli_query($conn, $MIman)) {
    while ($row8HM = mysqli_fetch_array($result8HMan)) {
        $receipt8HMan = $row8HM["total"];
        if ($receipt8HMan <= '0'){
            $receipt8HMan = '0';
        }
    }
}
if ($result8HCon = mysqli_query($conn, $MICon)) {
    while ($row8HCon  = mysqli_fetch_array($result8HCon)) {
        $con8H = $row8HCon['consumption'];
        if ($con8H <= '0') { $con8H = '0'; }
    }
}
if ($result8HC = mysqli_query($conn, $sql8HC)) {
    while ($row8HC = mysqli_fetch_array($result8HC)) {
        $C8HC = $row8HC['qty'];
    }
}


if ($resultBrF = mysqli_query($conn, $sqlBra)) {
    while ($rowBr = mysqli_fetch_array($resultBrF)) {
        $partBr = $rowBr['Part Number'];
        $partDescBr = $rowBr['Part Description'];
        $partQtyBr = $rowBr['qty'];
    }
}
if ($resultBr = mysqli_query($conn, $BCrec)) {
    while ($rowBr = mysqli_fetch_array($resultBr)) {
        $receiptBr = $rowBr["bc001"];
        if ($receiptBr <= '0'){
            $receiptBr = '0';
        }
    }
}
if ($resultBrMan = mysqli_query($conn, $BCman)) {
    while ($rowBrM = mysqli_fetch_array($resultBrMan)) {
        $receiptBrMan = $rowBrM["total"];
        if ($receiptBrMan <= '0'){
            $receiptBrMan = '0';
        }
    }
}
if ($resultBrCon = mysqli_query($conn, $BCCon)) {
    while ($rowBrCon  = mysqli_fetch_array($resultBrCon)) {
        $conBR = $rowBrCon['consumption'];
        if ($conBR <= '0') { $conBR = '0'; }
    }
}
if ($resultBrC = mysqli_query($conn, $sqlBraC)) {
    while ($rowBrC = mysqli_fetch_array($resultBrC)) {
        $BrC = $rowBrC['qty'];
    }
}

if ($resultPlF = mysqli_query($conn, $sqlPl)) {
    while ($rowPl = mysqli_fetch_array($resultPlF)) {
        $partPl = $rowPl['Part Number'];
        $partDescPl = $rowPl['Part Description'];
        $partQtyPl = $rowPl['qty'];
    }
}
if ($resultPl = mysqli_query($conn, $PLrec)) {
    while ($rowPl = mysqli_fetch_array($resultPl)) {
        $receiptPl = $rowPl["pl001"];
        if ($receiptPl <= '0'){
            $receiptPl = '0';
        }
    }
}
if ($resultPlMan = mysqli_query($conn, $PLman)) {
    while ($rowPlM = mysqli_fetch_array($resultPlMan)) {
        $receiptPlMan = $rowPlM["total"];
        if ($receiptPlMan <= '0'){
            $receiptPlMan = '0';
        }
    }
}
if ($resultPlCon = mysqli_query($conn, $PLCon)) {
    while ($rowPlCon  = mysqli_fetch_array($resultPlCon)) {
        $conPL = $rowPlCon['consumption'];
        if ($conPL <= '0') { $conPL = '0'; }
    }
}
if ($resultPlC = mysqli_query($conn, $sqlPlC)) {
    while ($rowPlC  = mysqli_fetch_array($resultPlC)) {
        $PlC = $rowPlC['qty'];
    }
}



if ($resultR7F = mysqli_query($conn, $sqlR7)) {
    while ($rowR7 = mysqli_fetch_array($resultR7F)) {
        $partR7 = $rowR7['Part Number'];
        $partDescR7 = $rowR7['Part Description'];
        $partQtyR7 = $rowR7['qty'];
    }
}
if ($resultR7 = mysqli_query($conn, $R7rec)) {
    while ($rowR7 = mysqli_fetch_array($resultR7)) {
        $receiptR7 = $rowR7["r007"];
        if ($receiptR7 <= '0'){
            $receiptR7 = '0';
        }
    }
}
if ($resultR7Man = mysqli_query($conn, $R7man)) {
    while ($rowR7M = mysqli_fetch_array($resultR7Man)) {
        $receiptR7Man = $rowR7M["total"];
        if ($receiptR7Man <= '0'){
            $receiptR7Man = '0';
        }
    }
}
if ($resultR7Con = mysqli_query($conn, $R7Con)) {
    while ($rowR7Con = mysqli_fetch_array($resultR7Con)) {
        $conR7 = $rowR7Con['consumption'];
        if ($conR7 <= '0'){
            $conR7 = '0';
        }
    }
}
if ($resultR7FC = mysqli_query($conn, $sqlR7C)) {
    while ($rowR7C = mysqli_fetch_array($resultR7FC)) {
        $R7C = $rowR7C['qty'];
    }
}

$sumopening= $partQtySpF + $partQtyCh + $partQtyJp + $partQtyFl + $partQty8HF + $partQtyR1F + $partQtyBr + $partQtyMd + $partQtyR5F + $partQtyPl + $partQtySwF + $partQtyR1F;
$sumreceipts= $receiptSpF + $receiptCh + $receiptJp + $receiptFl + $receipt8H + $receiptR1F + $receiptBr + $receiptMd + $receiptR5F + $receiptPl + $receiptSw + $receiptR1F;
$sumconsumption = $conSP + $conCH + $conJP + $conFL + $con8H + $conR1 + $conBR +$conMD + $conR5 + $conPL + $conSW + $conR1;
$sumclosing = $SpC + $ChC + $JpC + $FlC + $C8HC + $R1C + $BrC + $MdC + $R5C + $PlC + $SwC + $R1C;


echo    









