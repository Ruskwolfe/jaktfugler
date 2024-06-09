<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JaktfuglerController extends Controller
{
    private $jakttider_fugler = [
        // Add your bird data here
        "toppskarv" => "01.10. til 30.11. i Finnmark, Troms, Nordland og Nord-Trøndelag, samt spesifikke kommuner i Sør-Trøndelag",
        "storskarv" => "01.10. til 30.11. over hele landet med unntak",
        "storskarv ungfugl med hvit buk" => "01.10. til 30.11. i Møre og Romsdal og Sør-Trøndelag (unntatt visse kommuner)",
        "storskarv i ferskvann" => "21.08. til 23.12. i Østfold, Akershus, Buskerud, Vestfold, Telemark, Aust-Agder, Vest-Agder og Rogaland",
        "kortnebbgås" => "10.08. til 23.12. generelt, med regionale begrensninger",
        "grågås" => "10.08. til 23.12. med spesifikke begrensninger i Finnmark og varierte datoer i andre områder",
        "brunnakke" => "21.08. til 23.12. over hele landet",
        "krikkand" => "21.08. til 23.12. over hele landet",
        "stokkand" => "21.08. til 23.12. over hele landet",
        "toppand" => "10.09. til 23.12. over hele landet",
        "havelle" => "10.09. til 23.12. over hele landet",
        "kvinand" => "10.09. til 23.12. over hele landet",
        "siland" => "10.09. til 23.12. over hele landet",
        "laksand" => "10.09. til 23.12. over hele landet",
        "svartand" => "10.09. til 23.12. i Østfold, Akershus, Oslo, Vestfold, Buskerud, Telemark, Aust-Agder, Vest-Agder",
        "ærfugl" => "01.10. til 30.11. i Østfold, Vestfold, Buskerud, Telemark, Aust-Agder, Vest-Agder",
        "jerpe" => "10.09. til 23.12. over hele landet",
        "orrfugl" => "10.09. til 23.12. over hele landet",
        "storfugl" => "10.09. til 23.12. over hele landet",
        "lirype" => "10.09. til 28.02./29.02. over hele landet, med utvidelse til 15.03. i noen nordlige områder",
        "fjellrype" => "10.09. til 28.02./29.02. over hele landet, med utvidelse til 15.03. i noen nordlige områder",
        "heilo" => "21.08. til 31.10. over hele landet, unntatt Rogaland hvor arten er fredet",
        "enkeltbekkasin" => "21.08. til 31.10. over hele landet",
        "rugde" => "10.09. til 23.12. over hele landet",
        "gråmåke" => "21.08. til 28.02./29.02. over hele landet",
        "svartbak" => "21.08. til 28.02./29.02. over hele landet",
        "ringdue" => "21.08. til 23.12. over hele landet, unntatt Troms og Finnmark hvor arten er fredet",
        "gråtrost" => "10.08. til 23.12. over hele landet",
        "rødvingetrost" => "10.08. til 23.12. over hele landet",
        "nøtteskrike" => "10.08. til 28.02./29.02. over hele landet, unntatt i Nordland, Troms, og Finnmark hvor arten er fredet",
        "skjære" => "10.08. til 28.02./29.02. over hele landet",
        "kråke" => "15.07. til 31.03. over hele landet",
        "ravn" => "10.08. til 28.02./29.02. over hele landet, med utvidelse til 15.03. i Troms og Finnmark",
    ];

    public function index() {
        return view('index');
    }

    public function sjekk(Request $request) {
        $art_input = strtolower(trim($request->input('art_input')));
        $art_input = str_replace(' ', '_', $art_input);

        if (array_key_exists($art_input, $this->jakttider_fugler)) {
            $art_navn = ucwords(str_replace('_', ' ', $art_input));
            $result = "Jakttider for $art_navn: " . $this->jakttider_fugler[$art_input];
        } else {
            $sorterte_arter = $this->finn_naermeste_arter($art_input, array_keys($this->jakttider_fugler));
            $result = "Foreslåtte arter:<ul>";
            foreach ($sorterte_arter as $foreslaatt_art) {
                $result .= "<li>" . ucwords(str_replace('_', ' ', $foreslaatt_art)) . "</li>";
            }
            $result .= "</ul>";
        }

        return view('index', ['result' => $result]);
    }

    public function liste() {
        $arter = array_keys($this->jakttider_fugler);
        sort($arter);
        $result = "Tilgjengelige arter for jakttider:<ul>";
        foreach ($arter as $art) {
            $result .= "<li>" . ucwords(str_replace('_', ' ', $art)) . "</li>";
        }
        $result .= "</ul>";

        return view('index', ['result' => $result]);
    }

    private function levenshtein_distance($s1, $s2) {
        $len1 = strlen($s1);
        $len2 = strlen($s2);
        $matrix = array();

        if ($len1 == 0) return $len2;
        if ($len2 == 0) return $len1;

        for ($i = 0; $i <= $len1; $i++) {
            $matrix[$i][0] = $i;
        }

        for ($j = 0; $j <= $len2; $j++) {
            $matrix[0][$j] = $j;
        }

        for ($i = 1; $i <= $len1; $i++) {
            $c1 = $s1[$i - 1];
            for ($j = 1; $j <= $len2; $j++) {
                $c2 = $s2[$j - 1];
                $cost = ($c1 == $c2) ? 0 : 1;
                $matrix[$i][$j] = min($matrix[$i - 1][$j] + 1, $matrix[$i][$j - 1] + 1, $matrix[$i - 1][$j - 1] + $cost);
            }
        }

        return $matrix[$len1][$len2];
    }

    private function finn_naermeste_arter($input_art, $tilgjengelige_arter) {
        $avstander = [];
        foreach ($tilgjengelige_arter as $art) {
            $avstander[$art] = $this->levenshtein_distance($input_art, $art);
        }
        asort($avstander);
        return array_keys(array_slice($avstander, 0, 3));
    }
}
