    <?php
    include '../functions/funcCount.php';
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

    $data = array(
        array(
            'skuid' => '',
            'partNumber' => 'RM-FS-SP001',
            'partDescription' => 'RM:Foam Scrap:Normal - General/ Code G - Spain',
            'openingBalance' => number_format($partQtySpF,2),
            'manufacturingReceipts' => number_format($receiptSpF + $receiptSpManF,2),
            'totalRebonding' => number_format($receiptSpF + $receiptSpManF + $partQtySpF ,2),
            'bomConsumption' => number_format($conSP,2),
            'closingBalance' => number_format(($receiptSpF + $receiptSpManF + $partQtySpF)-$conSP ,2),
            'actualBalance' => number_format($SpC,2),
            'variancee' => number_format($SpC-(($receiptSpF + $receiptSpManF + $partQtySpF)-$conSP),2)
        ),

        array(
            'skuid' => '',
            'partNumber' => 'RM-FS-CH001',
            'partDescription' => 'RM:Foam Scrap: Normal - General/ Code G - China',
            'openingBalance' => number_format($partQtyCh,2),
            'manufacturingReceipts' => number_format($receiptCh + $receiptChMan,2),
            'totalRebonding' => number_format($receiptCh + $receiptChMan + $partQtyCh ,2),
            'bomConsumption' => number_format($conCH,2),
            'closingBalance' => number_format(($receiptCh + $receiptChMan + $partQtyCh)-$conCH ,2),
            'actualBalance' => number_format($ChC,2),
            'variancee' => number_format($ChC-(($receiptCh + $receiptChMan + $partQtyCh)-$conCH),2)
        ),

        array(
            'skuid' => '',
            'partNumber' => 'RM-FS-JM001',
            'partDescription' => 'RM:Foam Scrap:Normal - Japan/ Code J',
            'openingBalance' => number_format($partQtyJp,2),
            'manufacturingReceipts' => number_format($receiptJp + $receiptJpMan,2),
            'totalRebonding' => number_format($receiptJp + $receiptJpMan + $partQtyJp ,2),
            'bomConsumption' => number_format($conJP,2),
            'closingBalance' => number_format(($receiptJp + $receiptJpMan + $partQtyJp)-$conJP ,2),
            'actualBalance' => number_format($JpC,2),
            'variancee' => number_format($JpC-(($receiptJp + $receiptJpMan + $partQtyJp)-$conJP),2)
        ),

        array(
            'skuid' => '',
            "partNumber" => "RM-FS-FL002",
            'partDescription'=> 'RM:Foam Scrap: Filter - Code F (JF)',
            'openingBalance' => number_format($partQtyFl,2),
            'manufacturingReceipts' => number_format($receiptFl + $receiptFlMan,2),
            'totalRebonding' => number_format($receiptFl + $receiptFlMan + $partQtyFl ,2),
            'bomConsumption' => number_format($conFL,2),
            'closingBalance' => number_format(($receiptFl + $receiptFlMan + $partQtyFl)-$conFL ,2),
            'actualBalance' => number_format($FlC,2),
            'variancee' => number_format($FlC-(($receiptFl + $receiptFlMan + $partQtyFl)-$conFL),2)
        ),
        array(
            'skuid' => '',
            'partNumber' => 'RM-FS-BR001',
            'partDescription' => 'RM:Foam Scrap: Bra - Code B',
            'openingBalance' => number_format($partQtyBr,2),
            'manufacturingReceipts' => number_format($receiptBr + $receiptBrMan,2),
            'totalRebonding' => number_format($receiptBr + $receiptBrMan + $partQtyBr ,2),
            'bomConsumption' => number_format($conBR,2),
            'closingBalance' => number_format(($receiptBr + $receiptBrMan + $partQtyBr)-$conBR ,2),
            'actualBalance' => number_format($BrC,2),
            'variancee' => number_format($BrC-(($receiptBr + $receiptBrMan + $partQtyBr)-$conBR),2)
        ),
        array(
            'skuid' => '',
            'partNumber' => 'RM-CH-MD008',
            'partDescription' => 'RM:Chemicals:MDI 1518H',
            'openingBalance' => number_format($partQtyMd,2),
            'manufacturingReceipts' => number_format($receiptMd + $receiptMdMan,2),
            'totalRebonding' => number_format($receiptMd + $receiptMdMan + $partQtyMd ,2),
            'bomConsumption' => number_format($conMD,2),
            'closingBalance' => number_format(($receiptMd + $receiptMdMan + $partQtyMd)-$conMD ,2),
            'actualBalance' => number_format($MdC,2),
            'variancee' => number_format($MdC-(($receiptMd + $receiptMdMan + $partQtyMd)-$conMD),2)
        ),
        array(
            'skuid' => '',
            'partNumber' => 'RM-CH-MD009',
            'partDescription' => 'Rm:chemicals:mdi-Polyol',
            'openingBalance' =>number_format($partQtyPl,2),
            'manufacturingReceipts' => number_format($receiptPl + $receiptPlMan,2),
            'totalRebonding' => number_format($receiptPl + $receiptPlMan + $partQtyPl ,2),
            'bomConsumption' => number_format($conPL,2),
            'closingBalance' => number_format(($receiptPl + $receiptPlMan + $partQtyPl)-$conPL ,2),
            'actualBalance' => number_format($PlC,2),
            'variancee' => number_format($PlC-(($receiptPl + $receiptPlMan + $partQtyPl)-$conPL),2)
        ),
        array(
            'skuid' => '',
            'partNumber' => 'RM-FM-FR007',
            'partDescription' => 'Rm:foam :Recon Mix',
            'openingBalance' => number_format($partQtyR7,2),
            'manufacturingReceipts' => number_format($receiptR7 + $receiptR7Man,2),
            'totalRebonding' => number_format($receiptR7 + $receiptR7Man + $partQtyR7 ,2),
            'bomConsumption' => number_format($conR7,2),
            'closingBalance' => number_format(($receiptR7 + $receiptR7Man + $partQtyR7)-$conR7 ,2),
            'actualBalance' => number_format($R7C,2),
            'variancee' => number_format($R7C-(($receiptR7 + $receiptR7Man + $partQtyR7)-$conR7),2)
        ),
        array(
            'skuid' => '',
            'partNumber' => 'RM-CH-MD007',
            'partDescription' => 'RM:Chemicals:MDI 1518',
            'openingBalance' => number_format($partQty8HF,2),
            'manufacturingReceipts' => number_format($receipt8H + $receipt8HMan,2),
            'totalRebonding' => number_format($receipt8H + $receipt8HMan + $partQty8HF ,2),
            'bomConsumption' => number_format($con8H,2),
            'closingBalance' => number_format(($receipt8H + $receipt8HMan + $partQty8HF)-$con8H ,2),
            'actualBalance' => number_format($C8HC,2),
            'variancee' => number_format($C8HC-(($receipt8H + $receipt8HMan + $partQty8HF)-$con8H),2)
        ),
        array(
            'skuid' => '',
            'partNumber' => 'RM-FS-SW001',
            'partDescription' => 'Rm:foam Scrap:sweepings',
            'openingBalance' => number_format($partQtySwF,2),
            'manufacturingReceipts' => number_format($receiptSw + $receiptSwMan,2),
            'totalRebonding' => number_format($receiptSw + $receiptSwMan + $partQtySwF ,2),
            'bomConsumption' => number_format($conSW,2),
            'closingBalance' => number_format(($receiptSw + $receiptSwMan + $partQtySwF)-$conSW ,2),
            'actualBalance' => number_format($SwC,2),
            'variancee' => number_format($SwC-(($receiptSw + $receiptSwMan + $partQtySwF)-$conSW),2)
        ),
        array(
            'skuid' => '',
            'partNumber' => 'RM-FM-FR001',
            'partDescription' => 'Rm:foam :Comfort Recycle',
            'openingBalance' => number_format($partQtyR1F,2),
            'manufacturingReceipts' => number_format($receiptR1F + $receiptR1Man,2),
            'totalRebonding' => number_format($receiptR1F + $receiptR1Man + $partQtyR1F ,2),
            'bomConsumption' => number_format($conR1,2),
            'closingBalance' => number_format(($receiptR1F + $receiptR1Man + $partQtyR1F)-$conR1 ,2),
            'actualBalance' => number_format($R1C,2),
            'variancee' => number_format($R1C-(($receiptR1F + $receiptR1Man + $partQtyR1F)-$conR1),2)
        ),
        array(
            'skuid' => '',
            'partNumber' => 'RM-FM-FR005',
            'partDescription' => 'Rm:foam :Recon Recycle',
            'openingBalance' => number_format($partQtyR5F,2),
            'manufacturingReceipts' => number_format($receiptR5F + $receiptR5Man,2),
            'totalRebonding' => number_format($receiptR5F + $receiptR5Man + $partQtyR5F ,2),
            'bomConsumption' => number_format($conR5,2),
            'closingBalance' => number_format(($receiptR5F + $receiptR5Man + $partQtyR5F)-$conR5 ,2),
            'actualBalance' => number_format($R5C,2),
            'variancee' => number_format($R5C-(($receiptR5F + $receiptR5Man + $partQtyR5F)-$conR5),2)
        ),
//        array(
//            'skuid' => '',
//            'partNumber' => '',
//            'partDescription' => 'Total',
//            'openingBalance' => number_format($partQtyR5F,2),
//            'manufacturingReceipts' =>  number_format($sumreceipts,2) ,
//            'totalRebonding' => number_format($sumopening + $sumreceipts,2),
//            'bomConsumption' => number_format($sumconsumption,2),
//            'closingBalance' => number_format(($sumreceipts+ $sumopening)-$sumconsumption,2),
//            'actualBalance' => number_format($sumclosing,2),
//            'variancee' => number_format($sumclosing -($sumreceipts+ $sumopening)-$sumconsumption,2)
//        ),

    );

    header('Content-Type: application/json');
    echo json_encode($data);
