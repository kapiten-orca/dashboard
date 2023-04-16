<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ptbController extends Controller
{
    public function index()
    {
        
        $data["catar_pendaftar_perbulan"] = json_encode(GetPendaftarPerBulan());
        $data["catar_hereg_perbulan"] = json_encode(GetHeregPerBulan());
        
        return view('ptb.index')->with('data',$data);
    }

    public function hreg()
    {
        
        $data["catar_pendaftar_perbulan"] = json_encode(GetPendaftarPerBulan());
        $data["catar_hereg_perbulan"] = json_encode(GetHeregPerBulan());
        return view('ptb.hreg')->with('data',$data);
    }

    public function dataTaruna()
    {
        //echo json_encode(GetPendaftarBulan()); die;
        $data["catar_perbulan"] = json_encode(GetPendaftarBulan());
        $data["taruna_by_provinsi"] = json_encode(GetTarunaProvinsi());
        $data["taruna_by_gender"] = json_encode(GetTarunaGender());
        return view('ptb.dataTaruna')->with('data',$data);
    }
}
function GetPendaftarPerBulan() {
    $dataPendaftarPerbulan = DB::select(
        'SELECT
            COUNT(1) AS total,
            CONCAT(
                MONTHNAME(catarTimestamp),
                " ",
                YEAR(catarTimestamp)
            ) AS bulan
        FROM
            ptb_calon_taruna
        WHERE
            catarTahunDaftar = ?
        GROUP BY
            CONCAT(
                MONTHNAME(catarTimestamp),
                " ",
                YEAR(catarTimestamp)
            )
        ORDER BY
            YEAR(catarTimestamp) ASC,
            MONTH(catarTimestamp) ASC
        ',
        ["2023"],
        false
    );
    return $dataPendaftarPerbulan;
}
function GetHeregPerBulan() {
    // data hereg - tset
    $dataHereg = DB::select(
        'SELECT
        COUNT(kkk.catarNoDaftar) AS total,
        CONCAT(
            MONTHNAME(kkk.paymentTimestamp),
            " ",
            YEAR(kkk.paymentTimestamp)
        ) AS bulan
    FROM
        (
        SELECT
            ddd.catarNoDaftar,
            ddd.catarNama,
            ddd.catarNoWa,
            ddd.catarEmail,
            ddd.invoiceJenisBayar,
            ddd.invoiceVirtualAccount,
            ddd.harusDibayarkan,
            ddd.harusDibayarkanRaw,
            FORMAT(
                SUM(IFNULL(ddd.telahDibayarkan, 0)),
                2,
                "id_ID"
            ) AS telahDibayarkan,
            SUM(IFNULL(ddd.telahDibayarkan, 0)) AS telahDibayarkanRaw,
            IFNULL(ddd.jumlahPayment, 0) AS telahDicicil,
            ddd.statusLunas,
            IF(
                ddd.invoiceJenisBayar = "pendaftaran" AND ddd.catarBypassUangPendaftaran AND ddd.statusLunas = "LUNAS",
                "BYPASS",
                IF(
                    ddd.statusLunas = "LUNAS" OR ddd.telahDibayarkan > 0,
                    "BANK",
                    "-"
                )
            ) AS keterangan,
            ddd.invoiceBillingId,
            ddd.invoiceExpiredDate,
            ddd.invoiceBank,
            ddd.catarTahunDaftar,
            ddd.paymentTimestamp
        FROM
            (
            SELECT
                bb.invoiceJenisBayar,
                IF(
                    COUNT(*) = 0,
                    "-",
                    MAX(bb.invoiceVirtualAccount)
                ) AS invoiceVirtualAccount,
                cc.jumlahPayment,
                FORMAT(
                    IFNULL(
                        IF(
                            cc.jumlahPayment > 1 AND bb.invoiceNominal < cc.harusDibayarkan,
                            cc.harusDibayarkan,
                            bb.invoiceNominal
                        ),
                        0
                    ),
                    2,
                    "id_ID"
                ) AS harusDibayarkan,
                IFNULL(
                    IF(
                        cc.jumlahPayment > 1 AND bb.invoiceNominal < cc.harusDibayarkan,
                        cc.harusDibayarkan,
                        bb.invoiceNominal
                    ),
                    0
                ) harusDibayarkanRaw,
                cc.telahDibayarkan,
                IF(
                    bb.invoiceJenisBayar = "pendaftaran" AND aa.catarBypassUangPendaftaran,
                    "LUNAS",
                    IFNULL(
                        IF(
                            cc.jumlahPayment > 1,
                            cc.statusLunas,
                            IF(
                                cc.telahDibayarkan >= bb.invoiceNominal,
                                "LUNAS",
                                IF(
                                    cc.telahDibayarkan = 0 OR cc.telahDibayarkan IS NULL,
                                    "BELUM MEMBAYAR",
                                    "BELUM LUNAS"
                                )
                            )
                        ),
                        "BELUM MEMBAYAR"
                    )
                ) AS statusLunas,
                aa.catarBypassUangPendaftaran,
                aa.catarUserId,
                aa.catarNoDaftar,
                bb.invoiceExpiredDate,
                aa.catarNama,
                aa.catarNoWa,
                aa.catarEmail,
                bb.invoiceBillingId,
                bb.invoiceBank,
                aa.catarTahunDaftar,
                cc.paymentTimestamp
            FROM
                ptb_calon_taruna AS aa
            LEFT JOIN(
                    (
                    SELECT
                        aa1.catarNoDaftar,
                        aa1.catarBypassUangPendaftaran,
                        cc1.invoiceVirtualAccount,
                        cc1.invoiceJenisBayar,
                        cc1.invoiceNominal,
                        cc1.invoiceExpiredDate,
                        cc1.invoiceBillingId,
                        cc1.invoiceBank
                    FROM
                        ptb_calon_taruna aa1
                    LEFT JOIN ptb_invoice cc1 ON
                        cc1.invoiceNoDaftar = aa1.catarNoDaftar
                    WHERE
                        cc1.invoiceJenisBayar != "pendaftaran" AND cc1.invoiceBank = "BNI"
                )
                ) bb
            ON
                bb.catarNoDaftar = aa.catarNoDaftar
            LEFT JOIN(
                SELECT dd.*,
                    SUM(ee.invoiceNominal) AS harusDibayarkan,
                    IF(
                        (
                            SUM(ee.invoiceNominal) <= dd.telahDibayarkan
                        ),
                        "LUNAS",
                        IF(
                            dd.telahDibayarkan = 0 OR dd.telahDibayarkan IS NULL,
                            "BELUM MEMBAYAR",
                            "BELUM LUNAS"
                        )
                    ) AS statusLunas
                FROM
                    (
                    SELECT
                        aa.catarUserId,
                        aa.catarNoDaftar,
                        cc.invoiceVirtualAccount,
                        bb.paymentNotes,
                        bb.paymentTimestamp,
                        cc.invoiceNoDaftar,
                        SUM(bb.paymentNominal) AS telahDibayarkan,
                        COUNT(*) AS jumlahPayment
                    FROM
                        ptb_calon_taruna aa
                    LEFT JOIN ptb_invoice cc ON
                        cc.invoiceNoDaftar = aa.catarNoDaftar AND cc.invoiceBank = "BNI"
                    LEFT JOIN ptb_payment bb ON
                        bb.paymentVirtualAccount = cc.invoiceVirtualAccount
                    GROUP BY
                        aa.catarNoDaftar,
                        cc.invoiceVirtualAccount,
                        bb.paymentNotes
                ) dd
            LEFT JOIN ptb_invoice ee ON
                ee.invoiceVirtualAccount = dd.invoiceVirtualAccount
            GROUP BY
                dd.catarNoDaftar,
                ee.invoiceVirtualAccount,
                dd.paymentNotes
            ) AS cc
        ON
            bb.invoiceVirtualAccount = cc.invoiceVirtualAccount
        GROUP BY
            aa.catarNoDaftar,
            bb.invoiceVirtualAccount,
            bb.invoiceJenisBayar
        ) ddd
    LEFT JOIN ptb_calon_taruna ccc ON
        ccc.catarTahunDaftar = ddd.catarTahunDaftar
    WHERE
        (
            (
                ddd.invoiceJenisBayar = "uang masuk" AND ddd.statusLunas = "lunas" OR ddd.statusLunas = "belum lunas"
            ) OR(
                ddd.invoiceJenisBayar = "spp" AND ddd.statusLunas = "lunas" OR ddd.statusLunas = "belum lunas"
            )
        ) AND ccc.catarTahunDaftar = "2023"
    GROUP BY
        ddd.catarNoDaftar
    HAVING
        telahDibayarkanRaw > 200000
    ) kkk
    GROUP BY
        MONTH(kkk.paymentTimestamp)
    HAVING
        bulan IS NOT NULL
    ORDER BY
        YEAR(kkk.paymentTimestamp) ASC,
        MONTH(kkk.paymentTimestamp) ASC', 
        [], 
        false
    );

    return $dataHereg;
}
function GetTarunaProvinsi()
{
    $dataTarunaProvinsi = DB::select(
        'SELECT
        COUNT(kkk.catarNoDaftar) AS total,
        kkk.provinsi
    FROM
        (
        SELECT
            ddd.catarNoDaftar,
            ddd.catarNama,
            ddd.catarNoWa,
            ddd.catarEmail,
            ddd.invoiceJenisBayar,
            ddd.invoiceVirtualAccount,
            ddd.harusDibayarkan,
            ddd.harusDibayarkanRaw,
            FORMAT(
                SUM(IFNULL(ddd.telahDibayarkan, 0)),
                2,
                "id_ID"
            ) AS telahDibayarkan,
            SUM(IFNULL(ddd.telahDibayarkan, 0)) AS telahDibayarkanRaw,
            IFNULL(ddd.jumlahPayment, 0) AS telahDicicil,
            ddd.statusLunas,
            IF(
                ddd.invoiceJenisBayar = "pendaftaran" AND ddd.catarBypassUangPendaftaran AND ddd.statusLunas = "LUNAS",
                "BYPASS",
                IF(
                    ddd.statusLunas = "LUNAS" OR ddd.telahDibayarkan > 0,
                    "BANK",
                    "-"
                )
            ) AS keterangan,
            ddd.invoiceBillingId,
            ddd.invoiceExpiredDate,
            ddd.invoiceBank,
            ddd.catarTahunDaftar,
            IF(
                LENGTH(ddd.catarProvinsi) < 2 OR ddd.catarProvinsi IS NULL OR ddd.catarProvinsi = "undefined",
                "TIDAK TERKATEGORI",
                ddd.catarProvinsi
            ) AS provinsi
        FROM
            (
            SELECT
                bb.invoiceJenisBayar,
                IF(
                    COUNT(*) = 0,
                    "-",
                    MAX(bb.invoiceVirtualAccount)
                ) AS invoiceVirtualAccount,
                cc.jumlahPayment,
                FORMAT(
                    IFNULL(
                        IF(
                            cc.jumlahPayment > 1 AND bb.invoiceNominal < cc.harusDibayarkan,
                            cc.harusDibayarkan,
                            bb.invoiceNominal
                        ),
                        0
                    ),
                    2,
                    "id_ID"
                ) AS harusDibayarkan,
                IFNULL(
                    IF(
                        cc.jumlahPayment > 1 AND bb.invoiceNominal < cc.harusDibayarkan,
                        cc.harusDibayarkan,
                        bb.invoiceNominal
                    ),
                    0
                ) harusDibayarkanRaw,
                cc.telahDibayarkan,
                IF(
                    bb.invoiceJenisBayar = "pendaftaran" AND aa.catarBypassUangPendaftaran,
                    "LUNAS",
                    IFNULL(
                        IF(
                            cc.jumlahPayment > 1,
                            cc.statusLunas,
                            IF(
                                cc.telahDibayarkan >= bb.invoiceNominal,
                                "LUNAS",
                                IF(
                                    cc.telahDibayarkan = 0 OR cc.telahDibayarkan IS NULL,
                                    "BELUM MEMBAYAR",
                                    "BELUM LUNAS"
                                )
                            )
                        ),
                        "BELUM MEMBAYAR"
                    )
                ) AS statusLunas,
                aa.catarBypassUangPendaftaran,
                aa.catarUserId,
                aa.catarNoDaftar,
                bb.invoiceExpiredDate,
                aa.catarNama,
                aa.catarNoWa,
                aa.catarEmail,
                bb.invoiceBillingId,
                bb.invoiceBank,
                aa.catarTahunDaftar,
                aa.catarProvinsi
            FROM
                ptb_calon_taruna AS aa
            LEFT JOIN(
                    (
                    SELECT
                        aa1.catarNoDaftar,
                        aa1.catarBypassUangPendaftaran,
                        cc1.invoiceVirtualAccount,
                        cc1.invoiceJenisBayar,
                        cc1.invoiceNominal,
                        cc1.invoiceExpiredDate,
                        cc1.invoiceBillingId,
                        cc1.invoiceBank
                    FROM
                        ptb_calon_taruna aa1
                    LEFT JOIN ptb_invoice cc1 ON
                        cc1.invoiceNoDaftar = aa1.catarNoDaftar
                    WHERE
                        cc1.invoiceJenisBayar != "pendaftaran" AND cc1.invoiceBank = "BNI"
                )
                ) bb
            ON
                bb.catarNoDaftar = aa.catarNoDaftar
            LEFT JOIN(
                SELECT dd.*,
                    SUM(ee.invoiceNominal) AS harusDibayarkan,
                    IF(
                        (
                            SUM(ee.invoiceNominal) <= dd.telahDibayarkan
                        ),
                        "LUNAS",
                        IF(
                            dd.telahDibayarkan = 0 OR dd.telahDibayarkan IS NULL,
                            "BELUM MEMBAYAR",
                            "BELUM LUNAS"
                        )
                    ) AS statusLunas
                FROM
                    (
                    SELECT
                        aa.catarUserId,
                        aa.catarNoDaftar,
                        cc.invoiceVirtualAccount,
                        bb.paymentNotes,
                        cc.invoiceNoDaftar,
                        SUM(bb.paymentNominal) AS telahDibayarkan,
                        COUNT(*) AS jumlahPayment
                    FROM
                        ptb_calon_taruna aa
                    LEFT JOIN ptb_invoice cc ON
                        cc.invoiceNoDaftar = aa.catarNoDaftar AND cc.invoiceBank = "BNI"
                    LEFT JOIN ptb_payment bb ON
                        bb.paymentVirtualAccount = cc.invoiceVirtualAccount
                    GROUP BY
                        aa.catarNoDaftar,
                        cc.invoiceVirtualAccount,
                        bb.paymentNotes
                ) dd
            LEFT JOIN ptb_invoice ee ON
                ee.invoiceVirtualAccount = dd.invoiceVirtualAccount
            GROUP BY
                dd.catarNoDaftar,
                ee.invoiceVirtualAccount,
                dd.paymentNotes
            ) AS cc
        ON
            bb.invoiceVirtualAccount = cc.invoiceVirtualAccount
        GROUP BY
            aa.catarNoDaftar,
            bb.invoiceVirtualAccount,
            bb.invoiceJenisBayar
        ) ddd
    LEFT JOIN ptb_calon_taruna ccc ON
        ccc.catarTahunDaftar = ddd.catarTahunDaftar
    WHERE
        (
            (
                ddd.invoiceJenisBayar = "uang masuk" AND ddd.statusLunas = "lunas" OR ddd.statusLunas = "belum lunas"
            ) OR(
                ddd.invoiceJenisBayar = "spp" AND ddd.statusLunas = "lunas" OR ddd.statusLunas = "belum lunas"
            )
        ) AND ccc.catarTahunDaftar = "2022"
    GROUP BY
        ddd.catarNoDaftar
    HAVING
        telahDibayarkanRaw > 200000
    ) kkk
    GROUP BY
        provinsi
    ORDER BY
        total
    DESC
    '
    );
    return $dataTarunaProvinsi;
}
function GetTarunaGender() {
    $dataTarunaGender = DB::select(
        'SELECT
        COUNT(kkk.catarNoDaftar) AS total,
        kkk.catarJK
    FROM
        (
        SELECT
            ddd.catarNoDaftar,
            ddd.catarNama,
            ddd.catarNoWa,
            ddd.catarEmail,
            ddd.invoiceJenisBayar,
            ddd.invoiceVirtualAccount,
            ddd.harusDibayarkan,
            ddd.harusDibayarkanRaw,
            FORMAT(
                SUM(IFNULL(ddd.telahDibayarkan, 0)),
                2,
                "id_ID"
            ) AS telahDibayarkan,
            SUM(IFNULL(ddd.telahDibayarkan, 0)) AS telahDibayarkanRaw,
            IFNULL(ddd.jumlahPayment, 0) AS telahDicicil,
            ddd.statusLunas,
            IF(
                ddd.invoiceJenisBayar = "pendaftaran" AND ddd.catarBypassUangPendaftaran AND ddd.statusLunas = "LUNAS",
                "BYPASS",
                IF(
                    ddd.statusLunas = "LUNAS" OR ddd.telahDibayarkan > 0,
                    "BANK",
                    "-"
                )
            ) AS keterangan,
            ddd.invoiceBillingId,
            ddd.invoiceExpiredDate,
            ddd.invoiceBank,
            ddd.catarTahunDaftar,
            ddd.catarJK
        FROM
            (
            SELECT
                bb.invoiceJenisBayar,
                IF(
                    COUNT(*) = 0,
                    "-",
                    MAX(bb.invoiceVirtualAccount)
                ) AS invoiceVirtualAccount,
                cc.jumlahPayment,
                FORMAT(
                    IFNULL(
                        IF(
                            cc.jumlahPayment > 1 AND bb.invoiceNominal < cc.harusDibayarkan,
                            cc.harusDibayarkan,
                            bb.invoiceNominal
                        ),
                        0
                    ),
                    2,
                    "id_ID"
                ) AS harusDibayarkan,
                IFNULL(
                    IF(
                        cc.jumlahPayment > 1 AND bb.invoiceNominal < cc.harusDibayarkan,
                        cc.harusDibayarkan,
                        bb.invoiceNominal
                    ),
                    0
                ) harusDibayarkanRaw,
                cc.telahDibayarkan,
                IF(
                    bb.invoiceJenisBayar = "pendaftaran" AND aa.catarBypassUangPendaftaran,
                    "LUNAS",
                    IFNULL(
                        IF(
                            cc.jumlahPayment > 1,
                            cc.statusLunas,
                            IF(
                                cc.telahDibayarkan >= bb.invoiceNominal,
                                "LUNAS",
                                IF(
                                    cc.telahDibayarkan = 0 OR cc.telahDibayarkan IS NULL,
                                    "BELUM MEMBAYAR",
                                    "BELUM LUNAS"
                                )
                            )
                        ),
                        "BELUM MEMBAYAR"
                    )
                ) AS statusLunas,
                aa.catarBypassUangPendaftaran,
                aa.catarUserId,
                aa.catarNoDaftar,
                bb.invoiceExpiredDate,
                aa.catarNama,
                aa.catarNoWa,
                aa.catarEmail,
                bb.invoiceBillingId,
                bb.invoiceBank,
                aa.catarTahunDaftar,
                aa.catarJK
            FROM
                ptb_calon_taruna AS aa
            LEFT JOIN(
                    (
                    SELECT
                        aa1.catarNoDaftar,
                        aa1.catarBypassUangPendaftaran,
                        cc1.invoiceVirtualAccount,
                        cc1.invoiceJenisBayar,
                        cc1.invoiceNominal,
                        cc1.invoiceExpiredDate,
                        cc1.invoiceBillingId,
                        cc1.invoiceBank
                    FROM
                        ptb_calon_taruna aa1
                    LEFT JOIN ptb_invoice cc1 ON
                        cc1.invoiceNoDaftar = aa1.catarNoDaftar
                    WHERE
                        cc1.invoiceJenisBayar != "pendaftaran" AND cc1.invoiceBank = "BNI"
                )
                ) bb
            ON
                bb.catarNoDaftar = aa.catarNoDaftar
            LEFT JOIN(
                SELECT dd.*,
                    SUM(ee.invoiceNominal) AS harusDibayarkan,
                    IF(
                        (
                            SUM(ee.invoiceNominal) <= dd.telahDibayarkan
                        ),
                        "LUNAS",
                        IF(
                            dd.telahDibayarkan = 0 OR dd.telahDibayarkan IS NULL,
                            "BELUM MEMBAYAR",
                            "BELUM LUNAS"
                        )
                    ) AS statusLunas
                FROM
                    (
                    SELECT
                        aa.catarUserId,
                        aa.catarNoDaftar,
                        cc.invoiceVirtualAccount,
                        bb.paymentNotes,
                        cc.invoiceNoDaftar,
                        SUM(bb.paymentNominal) AS telahDibayarkan,
                        COUNT(*) AS jumlahPayment
                    FROM
                        ptb_calon_taruna aa
                    LEFT JOIN ptb_invoice cc ON
                        cc.invoiceNoDaftar = aa.catarNoDaftar AND cc.invoiceBank = "BNI"
                    LEFT JOIN ptb_payment bb ON
                        bb.paymentVirtualAccount = cc.invoiceVirtualAccount
                    GROUP BY
                        aa.catarNoDaftar,
                        cc.invoiceVirtualAccount,
                        bb.paymentNotes
                ) dd
            LEFT JOIN ptb_invoice ee ON
                ee.invoiceVirtualAccount = dd.invoiceVirtualAccount
            GROUP BY
                dd.catarNoDaftar,
                ee.invoiceVirtualAccount,
                dd.paymentNotes
            ) AS cc
        ON
            bb.invoiceVirtualAccount = cc.invoiceVirtualAccount
        GROUP BY
            aa.catarNoDaftar,
            bb.invoiceVirtualAccount,
            bb.invoiceJenisBayar
        ) ddd
    LEFT JOIN ptb_calon_taruna ccc ON
        ccc.catarTahunDaftar = ddd.catarTahunDaftar
    WHERE
        (
            (
                ddd.invoiceJenisBayar = "uang masuk" AND ddd.statusLunas = "lunas" OR ddd.statusLunas = "belum lunas"
            ) OR(
                ddd.invoiceJenisBayar = "spp" AND ddd.statusLunas = "lunas" OR ddd.statusLunas = "belum lunas"
            )
        ) AND ccc.catarTahunDaftar = "2022"
    GROUP BY
        ddd.catarNoDaftar
    HAVING
        telahDibayarkanRaw > 200000
    ) kkk
    GROUP BY
        kkk.catarJK'
    );
    return $dataTarunaGender;
}

function GetPendaftarBulan(){
    $dataPendaftarBulan = DB::select('SELECT		
    COUNT(1) AS total,		
    CONCAT(		
        DAY(catarTimestamp),		
        " ",		
        MONTHNAME(catarTimestamp),		
        " ",		
        YEAR(catarTimestamp)		
    ) AS bulan,
    DAY(catarTimestamp) as tanggal,
    MONTH(catarTimestamp) as kode_bulan,
    Year(catarTimestamp) as tahun
FROM		
    ptb_calon_taruna		
WHERE		
    catarTahunDaftar = "2022" AND MONTH(catarTimestamp) = 2		
GROUP BY
    DAY(catarTimestamp),
    MONTHNAME(catarTimestamp),
    YEAR(catarTimestamp)
ORDER BY		
    YEAR(catarTimestamp) ASC,		
    MONTH(catarTimestamp) ASC,		
    DAY(catarTimestamp) ASC');
    return $dataPendaftarBulan;
}
