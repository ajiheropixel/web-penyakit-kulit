<?php
class BayesCalculator {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Hitung probabilitas tiap penyakit berdasarkan gejala yang dipilih user.
     * @param array $gejalaIds array of int (id gejala yang dipilih)
     * @return array hasil terurut dari probabilitas tertinggi, format:
     *   [ ['penyakit' => [...], 'persentase' => float], ... ]
     */
    public function hitung(array $gejalaIds) {
        if (empty($gejalaIds)) return [];

        // Ambil semua penyakit
        $penyakitList = $this->conn->query("SELECT * FROM penyakit")->fetchAll();
        $totalPenyakit = count($penyakitList);
        if ($totalPenyakit === 0) return [];

        $priorH = 1 / $totalPenyakit; // prior sama rata
        $skorMentah = []; // skor sebelum normalisasi: P(E|H) gabungan x P(H)

        $placeholders = implode(',', array_fill(0, count($gejalaIds), '?'));

        foreach ($penyakitList as $penyakit) {
            // Ambil rule gejala terpilih yang relevan dengan penyakit ini
            $stmt = $this->conn->prepare(
                "SELECT nilai_probabilitas FROM rule WHERE penyakit_id = ? AND gejala_id IN ($placeholders)"
            );
            $stmt->execute(array_merge([$penyakit['id']], $gejalaIds));
            $rules = $stmt->fetchAll();

            if (count($rules) === 0) {
                // Tidak ada gejala yang dipilih user relevan dengan penyakit ini
                $skorMentah[$penyakit['id']] = [
                    'penyakit' => $penyakit,
                    'skor' => 0
                ];
                continue;
            }

            // Gabungkan P(E|H) dengan perkalian (Naive Bayes)
            $gabunganEH = 1;
            foreach ($rules as $r) {
                $gabunganEH *= (float) $r['nilai_probabilitas'];
            }

            $skor = $gabunganEH * $priorH;
            $skorMentah[$penyakit['id']] = [
                'penyakit' => $penyakit,
                'skor' => $skor
            ];
        }

        // Normalisasi: skor / total semua skor, lalu ubah ke persen
        $totalSkor = array_sum(array_column($skorMentah, 'skor'));

        $hasil = [];
        foreach ($skorMentah as $item) {
            if ($item['skor'] <= 0) continue; // skip penyakit yang tidak relevan sama sekali
            $persentase = $totalSkor > 0 ? ($item['skor'] / $totalSkor) * 100 : 0;
            $hasil[] = [
                'penyakit' => $item['penyakit'],
                'persentase' => round($persentase, 2)
            ];
        }

        // Urutkan dari persentase tertinggi
        usort($hasil, function ($a, $b) {
            return $b['persentase'] <=> $a['persentase'];
        });

        return $hasil;
    }
}