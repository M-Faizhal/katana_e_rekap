<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice - PT. Kamil Tria Niaga</title>
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
   * CORNER DECORATIONS
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
   * HEADER
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
   * INVOICE STYLES
   * ============================================================ */

  .inv-title {
    font-family: Arial, sans-serif;
    font-size: 28pt;
    font-weight: 900;
    color: #222;
    margin-bottom: 16px;
    letter-spacing: 1px;
  }

  .inv-top-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 4px;
  }

  .inv-company-block {
    font-size: 10pt;
    line-height: 1.6;
  }
  .inv-company-block .inv-company-name {
    font-weight: bold;
    font-size: 10.5pt;
  }
  .inv-company-block a {
    color: #1a0dab;
    font-size: 9.5pt;
  }

  .inv-date {
    font-size: 10pt;
    font-weight: bold;
    color: #032846;
    text-align: right;
    white-space: nowrap;
  }

  .inv-no {
    text-align: right;
    font-size: 10pt;
    margin-bottom: 14px;
  }

  /* Divider line under company block */
  .inv-divider {
    border: none;
    border-top: 1px solid #aaa;
    margin-bottom: 12px;
  }

  /* Main Table */
  .inv-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 0;
    font-size: 10pt;
  }
  .inv-table th {
    background: #B71C1C;
    color: #fff;
    padding: 7px 8px;
    text-align: center;
    border: 1px solid #555;
    font-weight: bold;
    font-size: 10pt;
    line-height: 1.3;
  }
  .inv-table td {
    padding: 6px 8px;
    border: 1px solid #999;
    vertical-align: top;
    font-size: 10pt;
  }
  .inv-table td.center { text-align: center; vertical-align: middle; }
  .inv-table td.right { text-align: right; vertical-align: middle; }

  .item-name {
    font-weight: bold;
    margin-bottom: 2px;
  }
  .item-sub {
    font-weight: bold;
    margin-bottom: 6px;
  }
  .item-desc-title {
    font-weight: bold;
    margin-bottom: 2px;
  }
  .item-desc {
    margin-bottom: 2px;
    line-height: 1.5;
  }
  .item-note-title {
    font-weight: bold;
    margin-top: 6px;
    margin-bottom: 2px;
  }

  /* Terbilang + Total row */
  .terbilang-row td {
    background: #fff;
    vertical-align: middle;
  }
  .terbilang-label {
    font-size: 9pt;
    color: #555;
    margin-bottom: 2px;
  }
  .terbilang-value {
    font-weight: bold;
    font-size: 10pt;
  }
  .total-label {
    text-align: center;
    font-weight: bold;
    font-size: 10pt;
    border-right: 1px solid #999;
  }
  .total-label span {
    font-size: 9pt;
    font-weight: normal;
    display: block;
  }
  .total-value {
    text-align: right;
    font-weight: bold;
    font-size: 10pt;
    white-space: nowrap;
  }

  /* Bottom Section: Payment + Signature */
  .bottom-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-top: 18px;
    gap: 20px;
  }

  .payment-box {
    border: 1px solid #aaa;
    padding: 10px 14px;
    font-size: 10pt;
    line-height: 1.8;
    min-width: 220px;
    max-width: 260px;
  }
  .payment-box div {
    font-size: 10pt;
  }

  .approved-block {
    text-align: center;
    font-size: 10pt;
  }
  .approved-label {
    font-size: 10pt;
    margin-bottom: 4px;
  }
  .approved-sig-space {
    width: 120px;
    height: 90px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 4px auto;
  }
  .approved-name {
    font-weight: bold;
    font-size: 10pt;
    text-decoration: underline;
    margin-bottom: 2px;
  }
  .approved-title {
    font-size: 10pt;
  }

  /* ============================================================
   * FOOTER
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
    .content { padding: 20px 80px; }
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

    <!-- Judul INVOICE -->
    <div class="inv-title">INVOICE</div>

    <!-- Company Info + Tanggal -->
    <div class="inv-top-row">
      <div class="inv-company-block">
        <div class="inv-company-name">PT. KAMIL TRIA NIAGA</div>
        <div>Magersari Permai Blok AW-23 RT. 024</div>
        <div>RW. 007, Sidoarjo</div>
        <div>085155232320</div>
        <div><a href="mailto:kamiltrianiaga@gmail.com">kamiltrianiaga@gmail.com</a></div>
      </div>
      <div>
        <div class="inv-date">08 Desember 2025</div>
      </div>
    </div>

    <!-- Nomor Invoice -->
    <div class="inv-no">060/INV/KTN/XII/2025</div>

    <!-- Tabel Invoice -->
    <table class="inv-table">
      <thead>
        <tr>
          <th style="width:5%;">NO</th>
          <th style="width:47%;">NAMA BARANG</th>
          <th style="width:7%;">VOL</th>
          <th style="width:8%;">SAT</th>
          <th style="width:17%;">HARGA SATUAN (Rp)</th>
          <th style="width:16%;">JUMLAH HARGA (Rp)</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="center">1</td>
          <td>
            <div class="item-name">a/n ARIF KURNIADI</div>
            <div class="item-sub">Dinas Pertanian</div>
            <div class="item-desc-title">Transportasi dan Penginapan<br>Madura 3-5 Desember 2025, dengan rincian :</div>
            <div class="item-desc">- Penginapan, tgl 3 s.d 5 Desember 2025<br>&nbsp;&nbsp;1 Orang x 1.200.000 = 1.200.000</div>
            <div class="item-desc">- Biaya Transportasi, tgl 3 s.d 5<br>&nbsp;&nbsp;Desember 2025<br>&nbsp;&nbsp;1 Orang x 1.400.000 = 1.400.000</div>
            <div class="item-note-title">Keterangan :</div>
            <div class="item-desc">-Penginapan di Azana Style Hotel dan Myze Hotel<br>-Transportasi Menggunakan Hiace</div>
          </td>
          <td class="center">1</td>
          <td class="center">Package</td>
          <td class="right">Rp 2.600.000</td>
          <td class="right">Rp 2.600.000</td>
        </tr>
        <!-- Terbilang + Total Row -->
        <tr class="terbilang-row">
          <td colspan="4" style="border-right: 1px solid #999; vertical-align: middle;">
            <div class="terbilang-label">Terbilang:</div>
            <div class="terbilang-value">Dua Juta Enam Ratus Ribu Rupiah</div>
          </td>
          <td class="total-label" style="border: 1px solid #999; vertical-align: middle;">
            <strong>TOTAL (Rp)</strong>
            <span>(Sudah Termasuk Pajak)</span>
          </td>
          <td class="total-value" style="border: 1px solid #999; vertical-align: middle; padding-right: 8px;">
            Rp 2.600.000
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Bottom: Pembayaran + Approved By -->
    <div class="bottom-section">
      <div class="payment-box">
        <div>Pembayaran transfer ke :</div>
        <div>&nbsp;</div>
        <div>Bank Mandiri Cab. Sidoarjo</div>
        <div>A/c 141-00-3180688-8</div>
        <div>A/n PT. Kamil Tria Niaga</div>
      </div>

      <div class="approved-block">
        <div class="approved-label">Approved By</div>
        <div class="approved-sig-space">
          <!-- Tanda tangan / stempel -->
        </div>
        <div class="approved-name">Mahenda Abdillah Kamil, S.Stat</div>
        <div class="approved-title">Direktur</div>
      </div>
    </div>

  </main>

</body>
</html>