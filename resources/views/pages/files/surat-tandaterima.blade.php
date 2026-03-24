<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tanda Terima - PT. Kamil Tria Niaga</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

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
    top: 0;
    right: 0;
    width: 140px;
    height: 100px;
    z-index: 20;
  }
  .corner-br {
    position: fixed;
    bottom: 0;
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
    top: 0;
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
    padding: 130px 80px 135px 80px;
    position: relative;
    z-index: 0;
  }

  /* ============================================================
   * TANDA TERIMA STYLES
   * ============================================================ */
  .tt-title {
    text-align: center;
    font-size: 13pt;
    font-weight: bold;
    text-decoration: underline;
    margin-bottom: 10px;
    letter-spacing: 1px;
  }

  .tt-nomor {
    text-align: center;
    font-size: 10pt;
    margin-bottom: 16px;
  }

  .tt-tanggal {
    text-align: right;
    font-size: 10pt;
    margin-bottom: 10px;
  }

  .tt-info-row {
    display: flex;
    font-size: 10pt;
    margin-bottom: 3px;
    line-height: 1.6;
  }
  .tt-info-label {
    min-width: 100px;
    font-weight: normal;
  }
  .tt-info-colon {
    margin-right: 8px;
  }
  .tt-info-value {
    flex: 1;
  }

  /* Table */
  .tt-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 6px;
    margin-bottom: 30px;
    font-size: 10pt;
  }
  .tt-table th {
    background: #fff;
    border: 1px solid #333;
    padding: 5px 8px;
    text-align: center;
    font-weight: bold;
    font-size: 10pt;
  }
  .tt-table td {
    border: 1px solid #333;
    padding: 5px 8px;
    font-size: 10pt;
    vertical-align: top;
  }
  .tt-table td.center { text-align: center; }

  /* Signature Section */
  .sig-section {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    font-size: 10pt;
  }
  .sig-block {
    text-align: center;
    min-width: 180px;
  }
  .sig-block-title {
    font-weight: bold;
    margin-bottom: 4px;
    font-size: 10pt;
  }
  .sig-block-company {
    font-weight: bold;
    font-size: 10pt;
    margin-bottom: 4px;
  }
  .sig-space {
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .sig-name-line {
    border-top: 1px solid #333;
    padding-top: 4px;
    font-weight: bold;
    font-size: 10pt;
    text-align: center;
  }
  .sig-dots {
    font-size: 10pt;
    letter-spacing: 2px;
  }

  /* ============================================================
   * FOOTER — fixed, tampil di semua halaman
   * ============================================================ */
  footer {
    position: fixed;
    bottom: 0;
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
    .content {
      padding: 20px 80px;
    }
  }
</style>
</head>
<body>

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
    <div class="tt-nomor">DISPAR/22.468/KTN/X/2025</div>

    <!-- Tanggal -->
    <div class="tt-tanggal">Sidoarjo, 3 Oktober 2025</div>

    <!-- Info Penerima -->
    <div class="tt-info-row">
      <span class="tt-info-label">Terima Oleh</span>
      <span class="tt-info-colon">:</span>
      <span class="tt-info-value">Dinas Pariwisata dan Ekonomi Kreatif</span>
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
        <tr>
          <td class="center">1</td>
          <td>Loker 12 Pintu</td>
          <td class="center">Unit</td>
          <td class="center">17</td>
        </tr>
        <tr>
          <td class="center">2</td>
          <td>Lemari Arsip Besi Slidding Kaca</td>
          <td class="center">Unit</td>
          <td class="center">7</td>
        </tr>
        <tr>
          <td class="center">3</td>
          <td>Lemari Buffet Buku/Lemari Panjang 3,25 Meter</td>
          <td class="center">Unit</td>
          <td class="center">3</td>
        </tr>
      </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div class="sig-section">
      <div class="sig-block">
        <div class="sig-block-title">Pengirim</div>
        <div class="sig-block-company">PT. KAMIL TRIA NIAGA</div>
        <div class="sig-space">
          <!-- Tempat tanda tangan / stempel -->
        </div>
        <div class="sig-name-line">ARISTO RAMADHANI</div>
      </div>
      <div class="sig-block">
        <div class="sig-block-title">Penerima</div>
        <div class="sig-block-company">DINAS PARIWISATA DAN EKONOMI KREATIF</div>
        <div class="sig-space"></div>
        <div class="sig-dots">.................................</div>
      </div>
    </div>

  </main>

</body>
</html>