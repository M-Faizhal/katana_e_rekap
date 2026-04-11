<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tanda Terima - PT. Kamil Tria Niaga</title>
<style>


  @page {
    size: A4;
    margin-top: 130px;
    margin-bottom: 135px;
    margin-left: 0;
    margin-right: 0;
  }

  body {
    font-family: 'Times New Roman', Times, serif;
    font-size: 10pt;
    margin: 0;
    padding: 0;
    background: #fff;
  }

  /* ============================================================
   * CORNER DECORATIONS — fixed, tampil di semua halaman
   * ============================================================ */
  .corner-tl {
    position: fixed;
    top: -130px;
    right: 0;
    width: 140px;
    height: 100px;
    z-index: 20;
  }
  .corner-br {
    position: fixed;
    bottom: -135px;
    left: 0;
    width: 140px;
    height: 140px;
    z-index: 20;
  }
  .corner-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  /* ============================================================
   * HEADER — fixed, tinggi HARUS konsisten dan terukur
   * ============================================================ */
  header {
    position: fixed;
    top: -130px;
    left: 0;
    right: 0;
    padding: 14px 0 14px 50px;
    border-bottom: 5px solid #C62828;
    background: white;
    z-index: 15;
    overflow: hidden;
  }
  .header-table {
    width: 100%;
    border-collapse: collapse;
  }
  .header-table td {
    vertical-align: middle;
  }
  .logo-img {
    width: 80px;
    height: 80px;
    display: block;
  }
  .company-name {
    font-family: Arial, sans-serif;
    font-size: 22px;
    font-weight: 800;
    color: #222;
    line-height: 1.1;
    letter-spacing: 1px;
    margin-left: 10px;
  }

  /* ============================================================
   * CONTENT
   * ============================================================ */
  .content {
    padding: 0px 60px 0px 60px;
    position: relative;
    z-index: 0;
  }

  /* ============================================================
   * TANDA TERIMA STYLES (disesuaikan agar mirip contoh)
   * ============================================================ */
  .tt-title {
    text-align: center;
    font-size: 12pt;
    font-weight: bold;
    letter-spacing: 1px;
    margin: 10px 0 6px;
    padding-bottom: 4px;
    border-bottom: 2px solid #000;
    border-top: 1px solid #000;
  }

  .tt-nomor {
    text-align: center;
    font-size: 10pt;
    margin: 10px 0 18px;
  }

  .tt-tanggal {
    text-align: right;
    font-size: 10pt;
    margin-bottom: 10px;
  }

  .tt-info-row {
    font-size: 10pt;
    margin-bottom: 2px;
    line-height: 1.3;
  }
  .tt-info-label,
  .tt-info-colon,
  .tt-info-value {
    display: inline-block;
    vertical-align: top;
  }
  .tt-info-label { width: 85px; }
  .tt-info-colon { width: 10px; }
  .tt-info-value { width: calc(100% - 95px); }

  /* Table */
  .tt-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 6px;
    font-size: 10pt;

    /* DomPDF multi-page */
    page-break-inside: auto;
  }
  .tt-table thead {
    display: table-header-group;
  }
  .tt-table tfoot {
    display: table-footer-group;
  }
  .tt-table tr {
    page-break-inside: avoid;
  }

  /* Hindari properti yang sering diabaikan DomPDF */
  /* break-inside: avoid;  <-- dihilangkan */

  .tt-table th,
  .tt-table td {
    border: 1px solid #000;
    padding: 4px 6px;
  }
  .tt-table th {
    text-align: center;
    font-weight: bold;
  }
  .tt-table td.center { text-align: center; }

  /* Signature Section */
  .sig-section {
    width: 100%;
    margin-top: 70px;
    font-size: 10pt;

    /* jangan sampai kepotong halaman */
    page-break-inside: avoid;
    break-inside: avoid;
  }
  .sig-table {
    width: 100%;
    border-collapse: collapse;
  }
  .sig-td {
    width: 50%;
    text-align: center;
    vertical-align: top;
  }
  .sig-block-title { margin-bottom: 2px; }
  .sig-block-company { font-weight: bold; }
  .sig-space {
    height: 85px;
    margin: 6px auto;
  }
  .sig-name-line {
    font-weight: bold;
    margin-top: 30px;
  }
  .sig-dots {
    margin-top: 34px;
    font-weight: bold;
  }

  /* ============================================================
   * FOOTER — fixed, tampil di semua halaman
   * ============================================================ */
  footer {
    position: fixed;
    bottom: -135px;
    left: 0;
    right: 0;
    background: white;     
    color: #000;
    padding: 6px 30px 14px 100px;
    font-size: 9pt;
    z-index: 10;
    box-sizing: border-box;
  }
  .footer-table {
    width: 100%;
    border-collapse: collapse;
  }
  .footer-table td {
    width: 50%;
    vertical-align: top;
    padding-bottom: 6px;
  }
  .footer-icon {
    float: left;
    width: 32px;
    height: 32px;
    text-align: center;
    border: 1.5px solid #c0392b;
    border-radius: 50%;
    background: #fff;
  }
  .footer-icon img {
    width: 16px;
    height: 16px;
    margin-top: 7px;
  }
  .footer-text-block {
    margin-left: 38px;
  }
  .footer-label {
    color: #000;
    font-size: 9pt;
    margin-bottom: 1px;
  }
  .footer-value {
    color: #c0392b;
    line-height: 1.3;
    font-size: 9pt;
  }

  @media print {
    body { background: none; padding: 0; margin: 0; }
  }
</style>
</head>
<body>

  @php
    $nomorSurat  = $surat['nomor_surat'] ?? null;
    $tempatSurat = $surat['tempat_surat'] ?? null;
    $tanggalRaw  = $surat['tanggal_surat'] ?? null; // Y-m-d (string)

    $tanggalSurat = '-';
    if (!empty($tanggalRaw)) {
      try {
        $tanggalSurat = \Carbon\Carbon::parse($tanggalRaw)->translatedFormat('d F Y');
      } catch (\Throwable $e) {
        $tanggalSurat = $tanggalRaw;
      }
    }

    $penerima = $surat['penerima'] ?? ($proyek->instansi ?? '-');
    $wilayah = $surat['wilayah'] ?? ($proyek->kab_kota ?? null);
    $pengirimNama = $surat['pengirim'] ?? '-';

    // Default tempat surat mengikuti footer (Sidoarjo) jika kosong
    $tempatTgl = trim(($tempatSurat ?: 'Sidoarjo') . ', ' . $tanggalSurat);

    $penerimaFull = trim($penerima . (empty($wilayah) ? '' : (' ' . $wilayah)));
  @endphp

  <!-- Corner decorations -->
  <div class="corner-tl">
    <img class="corner-img" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/kanan-atas.png'))) }}" alt="">
  </div>
  <div class="corner-br">
    <img class="corner-img" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/kiri-bawah.png'))) }}" alt="">
  </div>

  <!-- Header FIXED -->
  <header>
    <table class="header-table">
      <tr>
        <td style="width: 80px;">
          <img class="logo-img" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Logo">
        </td>
        <td>
          <div class="company-name">
            KAMIL TRIA<br>NIAGA
          </div>
        </td>
      </tr>
    </table>
  </header>

  <!-- Footer FIXED -->
 <footer>
    <table class="footer-table">
      <tr>
        <td>
          <div>
            <div class="footer-icon"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0iI2MwMzkyYiI+PHBhdGggZD0iTTEyIDJDOC4xNCAyIDUgNS4xNCA1IDljMCA1LjI1IDcgMTMgNyAxM3M3LTcuNzUgNy0xM2MwLTMuODYtMy4xNC03LTctN3ptMCA5LjVjLTEuMzggMC0yLjUtMS4xMi0yLjUtMi41czEuMTItMi41IDIuNS0yLjUgMi41IDEuMTIgMi41IDIuNS0xLjEyIDIuNS0yLjUgMi41eiIvPjwvc3ZnPg==" alt=""></div>
            <div class="footer-text-block">
              <div class="footer-label">Address Head Office</div>
              <div class="footer-value">Magersari Permai Blok AW-23 RT. 024 RW. 007,<br>Sidoarjo, Jawa Timur</div>
            </div>
          </div>
        </td>
        <td>
          <div>
            <div class="footer-icon"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0iI2MwMzkyYiI+PHBhdGggZD0iTTIwIDRIMGMtMS4xIDAtMS45OS45LTEuOTkgMkwyIDE4YzAgMS4xLjkgMiAyIDJoMTZjMS4xIDAgMi0uOSAyLTJWNmMwLTEuMS0uOS0yLTItMnptMCA0bC04IDUtOC01VjZsOCA1IDgtNXYyeiIvPjwvc3ZnPg==" alt=""></div>
            <div class="footer-text-block">
              <div class="footer-label">Email</div>
              <div class="footer-value">kamiltrianiaga@gmail.com</div>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div>
            <div class="footer-icon"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0iI2MwMzkyYiI+PHBhdGggZD0iTTEyIDJDOC4xNCAyIDUgNS4xNCA1IDljMCA1LjI1IDcgMTMgNyAxM3M3LTcuNzUgNy0xM2MwLTMuODYtMy4xNC03LTctN3ptMCA5LjVjLTEuMzggMC0yLjUtMS4xMi0yLjUtMi41czEuMTItMi41IDIuNS0yLjUgMi41IDEuMTIgMi41IDIuNS0xLjEyIDIuNS0yLjUgMi41eiIvPjwvc3ZnPg==" alt=""></div>
            <div class="footer-text-block">
              <div class="footer-label">Address Branch Office</div>
              <div class="footer-value">Jl. H. Abu No.57 3, RT.3/RW.7, Cipete Sel,<br>Kec. Cilandak, DKI Jakarta</div>
            </div>
          </div>
        </td>
        <td>
          <div>
            <div class="footer-icon"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0iI2MwMzkyYiI+PHBhdGggZD0iTTYuNjIgMTAuNzljMS40NCAyLjgzIDMuNzYgNS4xNCA2LjU5IDYuNTlsMi4yLTIuMmMuMjctLjI3LjY3LS4zNiAxLjAyLS4yNCAxLjEyLjM3IDIuMzMuNTcgMy41Ny41Ny41NSAwIDEgLjQ1IDEgMVYyMGMwIC41NS0uNDUgMS0xIDEtOS4zOSAwLTE3LTcuNjEtMTctMTcgMC0uNTUuNDUtMSAxLTFoMy41Yy41NSAwIDEgLjQ1IDEgMSAwIDEuMjUuMiAyLjQ1LjU3IDMuNTcuMTEuMzUuMDMuNzQtLjI1IDEuMDJsLTIuMiAyLjJWMTAuNzl6Ii8+PC9zdmc+" alt=""></div>
            <div class="footer-text-block">
              <div class="footer-label">Phone</div>
              <div class="footer-value">0851-5523-2320</div>
            </div>
          </div>
        </td>
      </tr>
    </table>
  </footer>

  <!-- ============================================================ -->
  <!-- CONTENT                                                       -->
  <!-- ============================================================ -->
  <main class="content">

    <!-- Judul -->
    <div class="tt-title">TANDA TERIMA</div>

    <!-- Nomor Surat -->
    <div class="tt-nomor">{{ $nomorSurat ?: '-' }}</div>

    <!-- Tanggal -->
    <div class="tt-tanggal">{{ $tempatTgl }}</div>

    <!-- Info Penerima -->
    <div class="tt-info-row">
      <span class="tt-info-label">Terima Oleh</span>
      <span class="tt-info-value">: {{ $penerima }} {{ $wilayah }}</span>
    </div>
    <div class="tt-info-row">
      <span class="tt-info-label">Berupa</span>
      <span class="tt-info-colon">:</span>
      <span class="tt-info-value"></span>
    </div>

    <!-- Tabel Barang -->
    <table class="tt-table">
      <thead>
        <tr>
          <th style="width: 6%;">No</th>
          <th style="width: 60%;">Nama Barang</th>
          <th style="width: 18%;">Satuan</th>
          <th style="width: 16%;">Qty</th>
        </tr>
      </thead>
      <tbody>
        @forelse(($items ?? []) as $idx => $it)
          <tr>
            <td class="center">{{ $idx + 1 }}</td>
            <td>{{ $it['nama_barang'] ?? '-' }}</td>
            <td class="center">{{ $it['satuan'] ?? '-' }}</td>
            <td class="center">{{ $it['qty'] ?? 0 }}</td>
          </tr>
        @empty
          <tr>
            <td class="center">1</td>
            <td>-</td>
            <td class="center">-</td>
            <td class="center">0</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <!-- Tanda Tangan (disusun seperti contoh menggunakan table) -->
    <div class="sig-section" style="break-inside: avoid;">
      <table class="sig-table">
        <tr>
          <td class="sig-td">
            <div class="sig-block-title">Pengirim</div>
            <div class="sig-block-company">PT. KAMIL TRIA NIAGA</div>
            <div class="sig-space"></div>
            <div class="sig-name-line">{{ strtoupper($pengirimNama ?: '-') }}</div>
          </td>
          <td class="sig-td">
            <div class="sig-block-title">Penerima</div>
            <div class="sig-block-company">{{ strtoupper($penerimaFull) }}</div>
            <div class="sig-space"></div>
            <div class="sig-dots">....................................</div>
          </td>
        </tr>
      </table>
    </div>

  </main>

</body>
</html>