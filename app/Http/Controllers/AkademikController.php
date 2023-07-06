<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkademikController extends Controller
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

        $filterSemester = json_encode(GetFilterSemester());
        $filterSemester = json_decode($filterSemester, true);
        $data["filter_semester"] = $filterSemester;

        $semester = $request->semester;
        if ($semester == null) {
            $semester = "20222";
        }
        // dd($semester);

        $data["Mhs_byr_Lunas"] = json_encode(GetMhbyrLunas($semester));
        // dd($data["Dosen_by_GenderAndStatus"]);
        // $data["catar_by_provinsi"] = json_encode(GetCatarbyprovinsi($year));
        // $data["catar_by_gender"] = json_encode(GetCatarbygender($year));
        // $data["catar_bygender_byprodi"] = json_encode(GetCatarbygenderbyprodi($year));

        return view('Dashboard.akademik',[
            "title" => "Akademik",
            'semester' => $semester
        ])->with('data',$data);

    }
}

function GetFilterTahun() {
    $filterTahun = DB::select(
        'SELECT DISTINCT catarTahunDaftar as filterTahun from ptb_calon_taruna ORDER BY catarTahunDaftar ASC',
        [],
        false
    );
    return $filterTahun;
}

function GetFilterSemester() {
    $filterSemester = DB::select(
        'SELECT DISTINCT mhsstbyrSemId AS filterSemester FROM mahasiswa_status_bayar ORDER BY mhsstbyrSemId ASC',
        [],
        false
    );
    return $filterSemester;
}

function GetMhbyrLunas($filterSemester) {
    $dataMhsbyrLunas = DB::select(
        'SELECT
            COUNT(`mhsstbyrMhsNiu`) as total,
            m.prodi
        FROM
            `mahasiswa_status_bayar` sb
        JOIN akademik_mahasiswa m ON
            sb.`mhsstbyrMhsNiu` = m.niu
        WHERE
            `mhsstbyrSemId` = ? AND `mhsstbyrIsLunas` = 1
        GROUP BY m.prodi_kode;
        ',
        [$filterSemester],
        false
    );
    return $dataMhsbyrLunas;
}
