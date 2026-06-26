<?php
require_once 'includes/header.php';
?>

<section class="hero-section" id="home">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="section-subtitle">Sistem Pakar Kesehatan Kulit</span>
                <h1 class="hero-title mt-2">Diagnosa Dini <br>Penyakit Kulit Anda</h1>
                <p class="hero-text">Gunakan sistem pakar berbasis Teorema Bayes untuk membantu mengidentifikasi kemungkinan penyakit kulit berdasarkan gejala yang Anda alami, lengkap dengan solusi penanganannya.</p>
                <a href="auth/register.php" class="btn btn-gradient">Mulai Diagnosa <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <div class="hero-img-wrap">
                    <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=800&q=80" alt="Diagnosa Kulit" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="info-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="info-card">
                    <h5><i class="fa-solid fa-brain text-primary me-2"></i>Metode</h5>
                    <p class="text-muted">Sistem menggunakan <strong>Teorema Bayes</strong> untuk menghitung probabilitas penyakit berdasarkan kombinasi gejala yang dipilih pengguna.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card">
                    <h5><i class="fa-solid fa-list-check text-primary me-2"></i>Cara Kerja</h5>
                    <p class="text-muted">Pilih gejala yang Anda rasakan dari daftar checkbox, lalu sistem otomatis menghitung dan menampilkan hasil diagnosa beserta solusinya.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card">
                    <h5><i class="fa-solid fa-file-pdf text-primary me-2"></i>Hasil & Riwayat</h5>
                    <p class="text-muted">Hasil diagnosa dapat diunduh dalam format PDF, dan seluruh riwayat diagnosa Anda tersimpan rapi di akun masing-masing.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding" id="about">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1631815588090-d4bfec5b1ccb?w=800&q=80" alt="Tentang" class="img-fluid rounded-4">
            </div>
            <div class="col-lg-6">
                <span class="section-subtitle">Tentang Sistem</span>
                <h2 class="section-title mt-2">Membantu Identifikasi Awal Penyakit Kulit</h2>
                <p class="text-muted">SkinDiag dikembangkan sebagai alat bantu identifikasi awal kondisi kulit secara online. Sistem ini tidak menggantikan diagnosa dokter, namun memberikan gambaran awal yang bisa menjadi acuan sebelum berkonsultasi lebih lanjut ke tenaga medis profesional.</p>
                <ul class="list-unstyled mt-4">
                    <li class="mb-2"><i class="fa-solid fa-circle-check text-primary me-2"></i>Perhitungan berbasis data & probabilitas</li>
                    <li class="mb-2"><i class="fa-solid fa-circle-check text-primary me-2"></i>Mudah digunakan, tanpa instalasi</li>
                    <li class="mb-2"><i class="fa-solid fa-circle-check text-primary me-2"></i>Riwayat diagnosa tersimpan otomatis</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-light" id="services">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-subtitle">Layanan</span>
            <h2 class="section-title mt-2">Apa yang Kami Sediakan</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon"><i class="fa-solid fa-clipboard-list"></i></div>
                    <h5>Form Diagnosa Interaktif</h5>
                    <p class="text-muted">Pilih gejala dengan mudah melalui tampilan checkbox yang jelas dan terstruktur.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon"><i class="fa-solid fa-chart-pie"></i></div>
                    <h5>Hasil Probabilitas</h5>
                    <p class="text-muted">Hasil diagnosa ditampilkan dengan persentase keyakinan berdasarkan Teorema Bayes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon"><i class="fa-solid fa-book-medical"></i></div>
                    <h5>Solusi & Penanganan</h5>
                    <p class="text-muted">Setiap hasil diagnosa dilengkapi rekomendasi solusi dan penanganan awal.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding" id="contact">
    <div class="container">
        <div class="contact-section">
            <div class="row align-items-center g-4">
                <div class="col-lg-5">
                    <h2 class="fw-bold">Punya Pertanyaan?</h2>
                    <p class="mt-2" style="opacity:0.9">Hubungi kami untuk informasi lebih lanjut mengenai sistem ini.</p>
                </div>
                <div class="col-lg-7">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6"><input type="text" class="form-control" placeholder="Nama Anda"></div>
                            <div class="col-md-6"><input type="email" class="form-control" placeholder="Email Anda"></div>
                            <div class="col-12"><textarea class="form-control" rows="3" placeholder="Pesan Anda"></textarea></div>
                            <div class="col-12"><button type="button" class="btn btn-light fw-semibold">Kirim Pesan</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>