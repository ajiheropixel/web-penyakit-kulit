<?php
require_once '../includes/functions.php';
require_once '../includes/auth_check.php';
require_once '../config/database.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$db = new Database();
$conn = $db->getConnection();

$id = (int) ($_GET['id'] ?? 0);

$stmt = $conn->prepare("
    SELECT rd.*, p.nama_penyakit, p.deskripsi, p.solusi, u.nama AS nama_user, u.email
    FROM riwayat_diagnosa rd
    JOIN penyakit p ON rd.penyakit_id = p.id
    JOIN users u ON rd.user_id = u.id
    WHERE rd.id = :id AND rd.user_id = :uid
");
$stmt->bindParam(':id', $id);
$stmt->bindParam(':uid', $_SESSION['user_id']);
$stmt->execute();
$data = $stmt->fetch();

if (!$data) {
    die('Data riwayat diagnosa tidak ditemukan atau bukan milik Anda.');
}

// Ambil nama-nama gejala yang dipilih
$gejalaIds = explode(',', $data['gejala_terpilih']);
$placeholders = implode(',', array_fill(0, count($gejalaIds), '?'));
$stmt = $conn->prepare("SELECT nama_gejala FROM gejala WHERE id IN ($placeholders)");
$stmt->execute($gejalaIds);
$daftarGejala = $stmt->fetchAll(PDO::FETCH_COLUMN);

$tanggal = date('d F Y, H:i', strtotime($data['created_at']));

$html = '
<style>
    body { font-family: sans-serif; color: #2c3e50; font-size: 12px; }
    .header { text-align: center; border-bottom: 3px solid #2196f3; padding-bottom: 15px; margin-bottom: 20px; }
    .header h2 { color: #1565c0; margin: 0; }
    .info-table { width: 100%; margin-bottom: 20px; }
    .info-table td { padding: 4px 0; }
    .result-box { background: #f4f8fb; border-left: 5px solid #2196f3; padding: 15px; margin-bottom: 20px; }
    .result-box h3 { margin: 0 0 5px; color: #1565c0; }
    .section-title { font-weight: bold; color: #1565c0; margin-top: 18px; margin-bottom: 5px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
    .footer-note { margin-top: 30px; font-size: 10px; color: #888; border-top: 1px solid #ddd; padding-top: 10px; }
    ul { margin: 5px 0; padding-left: 18px; }
</style>

<div class="header">
    <h2>SkinDiag</h2>
    <p>Laporan Hasil Diagnosa Penyakit Kulit</p>
</div>

<table class="info-table">
    <tr><td width="120">Nama Pasien</td><td width="10">:</td><td>' . htmlspecialchars($data['nama_user']) . '</td></tr>
    <tr><td>Email</td><td>:</td><td>' . htmlspecialchars($data['email']) . '</td></tr>
    <tr><td>Tanggal Diagnosa</td><td>:</td><td>' . $tanggal . '</td></tr>
</table>

<div class="result-box">
    <h3>' . htmlspecialchars($data['nama_penyakit']) . '</h3>
    <p>Tingkat Keyakinan: <strong>' . $data['persentase'] . '%</strong></p>
</div>

<div class="section-title">Gejala yang Dipilih</div>
<ul>';
foreach ($daftarGejala as $g) {
    $html .= '<li>' . htmlspecialchars($g) . '</li>';
}
$html .= '</ul>

<div class="section-title">Deskripsi Penyakit</div>
<p>' . nl2br(htmlspecialchars($data['deskripsi'])) . '</p>

<div class="section-title">Solusi & Penanganan</div>
<p>' . nl2br(htmlspecialchars($data['solusi'])) . '</p>

<div class="footer-note">
    Dokumen ini dihasilkan secara otomatis oleh sistem pakar SkinDiag berbasis Teorema Bayes.
    Hasil bersifat estimasi dan bukan pengganti diagnosa medis resmi dari dokter.
</div>
';

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Hasil_Diagnosa_' . str_replace(' ', '_', $data['nama_user']) . '.pdf', ['Attachment' => true]);