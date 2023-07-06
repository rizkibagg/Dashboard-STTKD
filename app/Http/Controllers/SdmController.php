<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SdmController extends Controller
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

        $data["Dosen_by_HomeBaseProdi"] = json_encode(GetDosenByHomeBaseProdi());
        $data["Dosen_by_Gender"] = json_encode(GetDosenByGender());
        $data["Dosen_by_GenderAndStatus"] = json_encode(GetDosenByGenderByStatus());
        // dd($data["Dosen_by_GenderAndStatus"]);
        // $data["catar_by_provinsi"] = json_encode(GetCatarbyprovinsi($year));
        // $data["catar_by_gender"] = json_encode(GetCatarbygender($year));
        // $data["catar_bygender_byprodi"] = json_encode(GetCatarbygenderbyprodi($year));

        return view('Dashboard.sdm',[
            "title" => "Sumber Daya Manusia",
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

function GetDosenByHomeBaseProdi() {
    $dataDosenbyHomeBaseProdi = DB::select(
        'SELECT
            COUNT(1) as total,
            #d_karyawan.karHomebase,
            ref_home_base.hbBase
        FROM
            d_karyawan
        JOIN ref_home_base
            ON d_karyawan.karHomebase = ref_home_base.hbId
        WHERE
            karStatusDosen IS NOT NULL
        GROUP BY
            karHomebase
        '
        // [$filterYear],
        // false
    );
    return $dataDosenbyHomeBaseProdi;
}

function GetDosenByGender() {
    $dataDosenbyGender = DB::select(
        'SELECT
            COUNT(1) as total,
            karJK
        FROM
            d_karyawan
        WHERE
            karJK IN ("L", "P") AND
            karStatusDosen IS NOT NULL
        GROUP BY
            karJK;
        '
        // [$filterYear],
        // false
    );
    return $dataDosenbyGender;
}

function GetDosenByGenderByStatus() {
    $dataDosenbyGenderbyStatus = DB::select(
        'SELECT
            COUNT(1) as total,
            CONCAT(k.karJK, " - ", sd.sdStatus) AS karJK_sdStatus
        FROM
            d_karyawan k
        JOIN ref_statusDosen sd
            ON k.karStatusDosen = sd.sdId
        WHERE
            karJK IN ("L", "P") AND
            karStatusDosen IS NOT NULL
        GROUP BY
            k.karJK,
            sd.sdStatus;
        '
        // [$filterYear],
        // false
    );
    return $dataDosenbyGenderbyStatus;
}
