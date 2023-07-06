<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PtbController extends Controller
{
    public function index(Request $request)
    {
        $filterTahun = json_encode(GetFilterTahun());
        $filterTahun = json_decode($filterTahun, true);
        $data["filter_tahun"] = $filterTahun;

        $year = $request->year;
        if ($year == null){
            $year = date("Y");
        }

        $data["catar_pendaftar_perbulan"] = json_encode(GetPendaftarPerBulan($year));
        $data["catar_hereg_perbulan"] = json_encode(GetHeregPerBulan($year));
        $data["catar_by_provinsi"] = json_encode(GetCatarbyprovinsi($year));
        $data["catar_by_gender"] = json_encode(GetCatarbygender($year));
        $data["catar_bygender_byprodi"] = json_encode(GetCatarbygenderbyprodi($year));
        // $data["catar_by_informasi"] = json_encode(GetCatarbyinformasi());

        // $dataCatarInfo = GetCatarbyinformasi();
        // $dataCatarInfoLabel = [];
        // $dataCatarInfoJumlah = [];
        // foreach($dataCatarInfo as $value) {
        //     $dataCatarInfoLabel[] = $value["keterangan"];
        //     $dataCatarInfoJumlah[] = $value["total"];
        // }
        // $data["catar_by_informasi_label"] = json_encode($dataCatarInfoLabel);
        // $data["catar_by_informasi_data"] = json_encode($dataCatarInfoJumlah);
        // dd($data);

        // $datacatarbyinformasi = GetCatarbyinformasi();
        // $datacatarbyinformasilabel = [];
        // echo json_encode(GetCatarbyprovinsi()); die;

        return view('Dashboard.ptb',[
            "title" => "Penerimaan Taruna Baru",
            'year' => $year
        ])->with('data',$data);
    }
}

function GetFilterTahun() {
    $filter = DB::select(
        'SELECT DISTINCT catarTahunDaftar as filterTahun from ptb_calon_taruna ORDER BY catarTahunDaftar ASC',
        [],
        false
    );
    return $filter;
}

function GetPendaftarPerBulan($filterYear) {
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
        [$filterYear],
        false
    );
    return $dataPendaftarPerbulan;
}

function GetHeregPerBulan($filterYear) {
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
        ) AND ccc.catarTahunDaftar = ?
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
        [$filterYear],
        false
    );

    return $dataHereg;
}

function GetCatarbyprovinsi($filterYear) {
    $dataCatarbyprovinsi = DB::select(
        'SELECT
        COUNT(kkk.catarNoDaftar) AS y,
        kkk.provinsi AS x
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
        ) AND ccc.catarTahunDaftar = ?
    GROUP BY
        ddd.catarNoDaftar
    HAVING
        telahDibayarkanRaw > 200000
    ) kkk
    GROUP BY
        provinsi
    ORDER BY
        y
    DESC',
    [$filterYear],
    false
    );
    return $dataCatarbyprovinsi;
}

function GetCatarbygender($filterYear){
    $dataCatarbygender = DB::select(
        'SELECT
        COUNT(kkk.catarNoDaftar) AS y,
        kkk.catarJK AS x
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
        ) AND ccc.catarTahunDaftar = ?
    GROUP BY
        ddd.catarNoDaftar
    HAVING
        telahDibayarkanRaw > 200000
    ) kkk
    GROUP BY
        kkk.catarJK
    ',
    [$filterYear],
    false
    );
    return $dataCatarbygender;
}

function GetCatarbygenderbyprodi($filterYear){
    $dataCatarbygenderbyprodi = DB::select(
        'SELECT
        COUNT(kkk.catarNoDaftar) AS y,
        kkk.jk AS x
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
            ddd.jk,
            ddd.catarPil1
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
                CONCAT(aa.catarPil1, " - ", aa.catarJK) AS jk,
                aa.catarPil1
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
        ) AND ccc.catarTahunDaftar = ?
    GROUP BY
        ddd.catarNoDaftar
    HAVING
        telahDibayarkanRaw > 200000
    ) kkk
    GROUP BY
        kkk.jk,
        kkk.catarPil1
    ORDER BY
        kkk.catarPil1 ASC,
        y
    DESC
    ',
    [$filterYear],
    false
    );
    return $dataCatarbygenderbyprodi;
}

function GetCatarbyinformasi(){
    $dataCatarbyinformasi = DB::select(
    'SELECT
    TRUNCATE
        (
            ((COUNT(*) * 100) / totalTaruna),
            1
        ) AS jumlah,
        aa.catarInfoSTTKD AS keterangan
    FROM
        ptb_calon_taruna AS aa
    CROSS JOIN(
        SELECT COUNT(1) AS totalTaruna
        FROM
            ptb_calon_taruna
        WHERE
            catarTahunDaftar = "2023" AND catarInfoSTTKD IS NOT NULL
    ) AS catar
    WHERE
        aa.catarTahunDaftar = ?
    GROUP BY
        aa.catarInfoSTTKD
    ',
    ['2023'],
    false
    );
    return $dataCatarbyinformasi;
}
