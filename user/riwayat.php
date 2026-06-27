<?php
$pageTitle = 'Riwayat Diagnosa';
$activePage = 'riwayat';
require_once '../includes/user_header.php';
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("
    SELECT rd.*, p.nama_penyakit
    FROM riwayat_diagnosa rd
    JOIN penyakit p ON rd.penyakit_id = p.id
    WHERE rd.user_id = :uid
    ORDER BY rd.created_at DESC
");
$stmt->bindParam(':uid', $_SESSION['user_id']);
$stmt->execute();
$riwayat = $stmt->fetchAll();
?>

<div class="admin-card">
    <h6 class="fw-bold mb-4">Riwayat Diagnosa Saya</h6>

    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Hasil Diagnosa</th>
                    <th>Keyakinan</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($riwayat) > 0): ?>
                    <?php foreach ($riwayat as $r): ?>
                    <tr>
                        <td><?= date('d M Y, H:i', strtotime($r['created_at'])) ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($r['nama_penyakit']) ?></td>
                        <td><span class="badge bg-primary"><?= $r['persentase'] ?>%</span></td>
                        <td>
                            <a href="cetak_pdf.php?id=<?= $r['id'] ?>" target="_blank" class="badge-action bg-primary-subtle text-primary" title="Unduh PDF"><i class="fa-solid fa-file-pdf"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">
    <div class="empty-state">
        <i class="fa-solid fa-inbox"></i>
        Belum ada data gejala. Klik "Tambah Gejala" untuk menambahkan.
    </div>
</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/user_footer.php'; ?>