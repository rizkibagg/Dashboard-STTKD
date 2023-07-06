<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
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
        // dd($year);

        $data["total_bayar_pendaftaran"] = json_encode(GetCountTotalBayarPendaftaran($year));
        $totalBP = json_decode($data["total_bayar_pendaftaran"], true)[0]['total'];
        $data["total_bayar_uang_masuk"] = json_encode(GetBayarUangMasuk($year));
        $totalBUM = json_decode($data["total_bayar_uang_masuk"], true)[0]['total'];
        $data["total_bayar_spp"] = json_encode(GetBayarSPP($year));
        $totalBSPP = json_decode($data["total_bayar_spp"], true)[0]['total'];
        $data["total_bayar_asrama"] = json_encode(GetBayarAsrama($year));
        $totalBA = json_decode($data["total_bayar_asrama"], true)[0]['total'];
        // $data["catar_by_provinsi"] = json_encode(GetCatarbyprovinsi($year));
        // $data["catar_by_gender"] = json_encode(GetCatarbygender($year));
        // $data["catar_bygender_byprodi"] = json_encode(GetCatarbygenderbyprodi($year));

        return view('Dashboard.home',[
            "title" => "Home",
            'year' => $year,
            'totalBP' => $totalBP,
            'totalBUM' => $totalBUM,
            'totalBSPP' => $totalBSPP,
            'totalBA' => $totalBA
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

function GetCountTotalBayarPendaftaran($filterYear) {
    $dataTotalBayarPendaftaran = DB::select(
        'SELECT
            COUNT(kkk.catarNoDaftar) AS total
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
                ddd.catarTahunDaftar
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
                    aa.catarTahunDaftar
                FROM
                    ptb_calon_taruna AS aa
                LEFT JOIN(
                        (
                        SELECT
                            aaa.catarNoDaftar,
                            aaa.catarBypassUangPendaftaran,
                            IF(
                                RIGHT(ccc.invoiceVirtualAccount, 1) != 1,
                                "-",
                                ccc.invoiceVirtualAccount
                            ) AS invoiceVirtualAccount,
                            "PENDAFTARAN" AS invoiceJenisBayar,
                            200000 AS invoiceNominal,
                            ccc.invoiceExpiredDate,
                            ccc.invoiceBillingId,
                            ccc.invoiceBank
                        FROM
                            ptb_calon_taruna aaa
                        LEFT JOIN ptb_invoice ccc ON
                            ccc.invoiceNoDaftar = aaa.catarNoDaftar
                        LEFT JOIN ptb_payment bbb ON
                            bbb.paymentVirtualAccount = ccc.invoiceVirtualAccount
                        WHERE
                            (
                                (
                                    ccc.invoiceJenisBayar = "pendaftaran" AND ccc.invoiceBank = "BNI" AND bbb.paymentId IS NOT NULL
                                ) OR aaa.catarBypassUangPendaftaran = "1"
                            )
                        GROUP BY
                            aaa.catarId
                    )
                UNION ALL
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
                        cc1.invoiceBank = "BNI"
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
        WHERE
            (
                ddd.invoiceJenisBayar = "pendaftaran" AND(
                    ddd.statusLunas = "lunas" OR ddd.statusLunas = "belum lunas"
                )
            ) AND ddd.catarTahunDaftar = ?
        GROUP BY
            ddd.catarNoDaftar,
            ddd.invoiceJenisBayar
        ) kkk
        ',
        [$filterYear],
        false
    );
    return $dataTotalBayarPendaftaran;
}

function GetBayarUangMasuk($filterYear) {
    $dataBayarUangMasuk = DB::select(
        'SELECT
            COUNT(kkk.catarNoDaftar) AS total
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
                ddd.catarTahunDaftar
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
                    aa.catarTahunDaftar
                FROM
                    ptb_calon_taruna AS aa
                LEFT JOIN(
                        (
                        SELECT
                            aaa.catarNoDaftar,
                            aaa.catarBypassUangPendaftaran,
                            IF(
                                RIGHT(ccc.invoiceVirtualAccount, 1) != 1,
                                "-",
                                ccc.invoiceVirtualAccount
                            ) AS invoiceVirtualAccount,
                            "PENDAFTARAN" AS invoiceJenisBayar,
                            200000 AS invoiceNominal,
                            ccc.invoiceExpiredDate,
                            ccc.invoiceBillingId,
                            ccc.invoiceBank
                        FROM
                            ptb_calon_taruna aaa
                        LEFT JOIN ptb_invoice ccc ON
                            ccc.invoiceNoDaftar = aaa.catarNoDaftar
                        LEFT JOIN ptb_payment bbb ON
                            bbb.paymentVirtualAccount = ccc.invoiceVirtualAccount
                        WHERE
                            (
                                (
                                    ccc.invoiceJenisBayar = "pendaftaran" AND ccc.invoiceBank = "BNI" AND bbb.paymentId IS NOT NULL
                                ) OR aaa.catarBypassUangPendaftaran = "1"
                            )
                        GROUP BY
                            aaa.catarId
                    )
                UNION ALL
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
                        cc1.invoiceBank = "BNI"
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
        WHERE
            (
                ddd.invoiceJenisBayar = "uang masuk" AND(
                    ddd.statusLunas = "lunas" OR ddd.statusLunas = "belum lunas"
                )
            ) AND ddd.catarTahunDaftar = ?
        GROUP BY
            ddd.catarNoDaftar,
            ddd.invoiceJenisBayar
        HAVING
            telahDibayarkanRaw > 200000
        ) kkk
        ',
        [$filterYear],
        false
    );
    return $dataBayarUangMasuk;
}

function GetBayarSPP($filterYear) {
    $dataBayarSPP = DB::select(
        'SELECT
            COUNT(kkk.catarNoDaftar) AS total
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
                ddd.catarTahunDaftar
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
                    aa.catarTahunDaftar
                FROM
                    ptb_calon_taruna AS aa
                LEFT JOIN(
                        (
                        SELECT
                            aaa.catarNoDaftar,
                            aaa.catarBypassUangPendaftaran,
                            IF(
                                RIGHT(ccc.invoiceVirtualAccount, 1) != 1,
                                "-",
                                ccc.invoiceVirtualAccount
                            ) AS invoiceVirtualAccount,
                            "PENDAFTARAN" AS invoiceJenisBayar,
                            200000 AS invoiceNominal,
                            ccc.invoiceExpiredDate,
                            ccc.invoiceBillingId,
                            ccc.invoiceBank
                        FROM
                            ptb_calon_taruna aaa
                        LEFT JOIN ptb_invoice ccc ON
                            ccc.invoiceNoDaftar = aaa.catarNoDaftar
                        LEFT JOIN ptb_payment bbb ON
                            bbb.paymentVirtualAccount = ccc.invoiceVirtualAccount
                        WHERE
                            (
                                (
                                    ccc.invoiceJenisBayar = "pendaftaran" AND ccc.invoiceBank = "BNI" AND bbb.paymentId IS NOT NULL
                                ) OR aaa.catarBypassUangPendaftaran = "1"
                            )
                        GROUP BY
                            aaa.catarId
                    )
                UNION ALL
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
                        cc1.invoiceBank = "BNI"
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
        WHERE
            (
                ddd.invoiceJenisBayar = "spp" AND(
                    ddd.statusLunas = "lunas" OR ddd.statusLunas = "belum lunas"
                )
            ) AND ddd.catarTahunDaftar = ?
        GROUP BY
            ddd.catarNoDaftar,
            ddd.invoiceJenisBayar
        HAVING
            telahDibayarkanRaw > 200000
        ) kkk
        ',
        [$filterYear],
        false
    );
    return $dataBayarSPP;
}

function GetBayarAsrama($filterYear) {
    $dataBayarAsrama = DB::select(
        'SELECT
            COUNT(kkk.catarNoDaftar) AS total
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
                ddd.catarTahunDaftar
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
                    aa.catarTahunDaftar
                FROM
                    ptb_calon_taruna AS aa
                LEFT JOIN(
                        (
                        SELECT
                            aaa.catarNoDaftar,
                            aaa.catarBypassUangPendaftaran,
                            IF(
                                RIGHT(ccc.invoiceVirtualAccount, 1) != 1,
                                "-",
                                ccc.invoiceVirtualAccount
                            ) AS invoiceVirtualAccount,
                            "PENDAFTARAN" AS invoiceJenisBayar,
                            200000 AS invoiceNominal,
                            ccc.invoiceExpiredDate,
                            ccc.invoiceBillingId,
                            ccc.invoiceBank
                        FROM
                            ptb_calon_taruna aaa
                        LEFT JOIN ptb_invoice ccc ON
                            ccc.invoiceNoDaftar = aaa.catarNoDaftar
                        LEFT JOIN ptb_payment bbb ON
                            bbb.paymentVirtualAccount = ccc.invoiceVirtualAccount
                        WHERE
                            (
                                (
                                    ccc.invoiceJenisBayar = "pendaftaran" AND ccc.invoiceBank = "BNI" AND bbb.paymentId IS NOT NULL
                                ) OR aaa.catarBypassUangPendaftaran = "1"
                            )
                        GROUP BY
                            aaa.catarId
                    )
                UNION ALL
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
                        cc1.invoiceBank = "BNI"
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
        WHERE
            (
                ddd.invoiceJenisBayar = "asrama" AND(
                    ddd.statusLunas = "lunas" OR ddd.statusLunas = "belum lunas"
                )
            ) AND ddd.catarTahunDaftar = ?
        GROUP BY
            ddd.catarNoDaftar,
            ddd.invoiceJenisBayar
        HAVING
            telahDibayarkanRaw > 200000
        ) kkk
        ',
        [$filterYear],
        false
    );
    return $dataBayarAsrama;
}
