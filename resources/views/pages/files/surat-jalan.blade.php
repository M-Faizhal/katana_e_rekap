<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Surat Jalan - PT. Kamil Tria Niaga</title>
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
   * HEADER — fixed
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
    padding: 130px 60px 135px 60px;
    position: relative;
    z-index: 0;
  }

  /* ============================================================
   * DO HEADER ROW
   * ============================================================ */
  .do-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
  }
  .do-company-block {
    font-size: 10pt;
    line-height: 1.5;
  }
  .do-company-block .company-title {
    font-weight: bold;
    font-size: 11pt;
    margin-bottom: 2px;
  }
  .do-company-block a {
    color: #1a0dab;
    font-size: 9pt;
  }
  .do-title-block {
    text-align: right;
  }
  .do-title {
    font-family: Arial, sans-serif;
    font-size: 26pt;
    font-weight: 900;
    color: #424242;
    letter-spacing: 2px;
    line-height: 1;
    margin-bottom: 8px;
  }
  .do-meta-table {
    border-collapse: collapse;
    margin-left: auto;
  }
  .do-meta-table td {
    padding: 2px 6px;
    font-size: 10pt;
    border: 1px solid #999;
  }
  .do-meta-table .meta-label {
    font-weight: bold;
    background: #eee;
    white-space: nowrap;
  }
  .do-meta-table .meta-value {
    min-width: 100px;
  }

  /* ============================================================
   * SHIP FROM / SHIP TO
   * ============================================================ */
  .address-row {
    display: flex;
    gap: 10px;
    margin-bottom: 12px;
  }
  .address-box {
    flex: 1;
    border: 1px solid #999;
  }
  .box-header {
    background: #BDBDBD;
    font-weight: bold;
    font-size: 10pt;
    padding: 4px 8px;
    border-bottom: 1px solid #999;
  }
  .box-body {
    padding: 8px 10px;
    font-size: 10pt;
    line-height: 1.6;
    min-height: 80px;
  }
  .box-body .party-name {
    font-weight: bold;
    text-decoration: underline;
    margin-bottom: 4px;
  }
  .box-body .phone {
    margin-top: 4px;
  }

  /* ============================================================
   * MAIN TABLE
   * ============================================================ */
  .do-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
    font-size: 10pt;
    page-break-inside: auto;
  }
  .do-table thead {
    display: table-header-group;
  }
  .do-table tbody {
    display: table-row-group;
  }
  .do-table tr {
    page-break-inside: avoid;
    break-inside: avoid;
  }
  .do-table th {
    background: #BDBDBD;
    color: #111;
    padding: 6px 5px;
    text-align: center;
    border: 1px solid #555;
    font-weight: bold;
    font-size: 10pt;
  }
  .do-table td {
    padding: 4px 8px;
    border: 1px solid #999;
    vertical-align: middle;
    font-size: 10pt;
  }
  .do-table td.center { text-align: center; }
  .do-table tr:nth-child(even) td { background: #f9f9f9; }

  /* ============================================================
   * COMMENTS
   * ============================================================ */
  .comments-block {
    border: 1px solid #999;
    font-size: 9.5pt;
    line-height: 1.5;
    margin-bottom: 16px;
  }
  .comments-header {
    background: #BDBDBD;
    font-weight: bold;
    padding: 4px 8px;
    border-bottom: 1px solid #999;
    font-size: 10pt;
  }
  .comments-body {
    padding: 6px 8px;
  }
  .comments-section-title {
    font-weight: bold;
    margin-bottom: 2px;
    font-size: 9.5pt;
  }
  .comments-list {
    list-style: none;
    padding-left: 10px;
    margin: 0;
  }
  .comments-list li {
    margin-bottom: 1px;
  }
  .comments-list li::before {
    content: "- ";
  }

  /* ============================================================
   * KOTA & TANGGAL
   * ============================================================ */
  .kota-tanggal {
    text-align: center;
    font-size: 10pt;
    margin-bottom: 20px;
  }

  /* ============================================================
   * SIGNATURE ROW
   * ============================================================ */
  .signature-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    page-break-inside: avoid;
    break-inside: avoid;
  }
  .sig-block {
    text-align: center;
    width: 45%;
  }
  .sig-label {
    font-size: 10pt;
    font-weight: bold;
    margin-bottom: 4px;
  }
  .sig-space {
    height: 70px;
  }
  .sig-name-line {
    border-top: 1px solid #333;
    padding-top: 2px;
    font-size: 10pt;
    font-weight: bold;
    display: inline-block;
    min-width: 180px;
  }
  .sig-sub {
    font-size: 9.5pt;
    margin-top: 2px;
  }

  /* ============================================================
   * FOOTER — fixed
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
    .content { padding: 20px 60px; }
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

    <!-- DO Header: Company Info + DO Title -->
    <div class="do-header-row">
      <div class="do-company-block">
        <div class="company-title">PT. KAMIL TRIA NIAGA</div>
        <div>Magersari No.23-24 Blok AW,</div>
        <div>Magersari, Kec. Sidoarjo, Kab. Sidoarjo,</div>
        <div>Jawa Timur 61212</div>
        <div><a href="https://ptkatana.com/">https://ptkatana.com/</a></div>
      </div>
      <div class="do-title-block">
        <div class="do-title">DELIVERY ORDER</div>
        <table class="do-meta-table">
          <tr>
            <td class="meta-label">DATE</td>
            <td class="meta-value">06/11/2025</td>
          </tr>
          <tr>
            <td class="meta-label">DO #</td>
            <td class="meta-value">500</td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Ship From & Ship To -->
    <div class="address-row">
      <div class="address-box">
        <div class="box-header">SHIP FROM</div>
        <div class="box-body">
          <div class="party-name">PT Kamil Tria Niaga</div>
          <div>Magersari No.23-24 Blok AW, Magersari, Kec.</div>
          <div>Sidoarjo, Kabupaten Sidoarjo, Jawa Timur 61212</div>
          <div class="phone">+62 896-6804-5776</div>
        </div>
      </div>
      <div class="address-box">
        <div class="box-header">SHIP TO</div>
        <div class="box-body">
          <div class="party-name">SMKN 1 Kuta Selatan - Bali</div>
          <div>Jalan Gedong Sari, By Pass Ngurah Rai, Nusa</div>
          <div>Dua, Benoa, Kec. Kuta Sel., Kabupaten Badung,</div>
          <div>Bali 80361</div>
          <div class="phone">+62 851-0085-7139</div>
        </div>
      </div>
    </div>

    <!-- Main Product Table -->
    <table class="do-table">
      <thead>
        <tr>
          <th style="width:6%;">NO</th>
          <th>PRODUCT</th>
          <th style="width:10%;">QTY</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="center">1</td>
          <td>Analog circuit training kit</td>
          <td class="center">1</td>
        </tr>
        <tr>
          <td class="center">2</td>
          <td>alat pelatihan dasar kelistrikan (Electricity Fundamental Training System)</td>
          <td class="center">1</td>
        </tr>
        <tr>
          <td class="center">3</td>
          <td>Alat Pelatihan Elektronika Dasar (basic electronic trainer)</td>
          <td class="center">1</td>
        </tr>
        <tr>
          <td class="center">4</td>
          <td>Aplikasi PLC pada Traffic Light</td>
          <td class="center">1</td>
        </tr>
        <tr>
          <td class="center">5</td>
          <td>Elektronika dan Digital Trainer</td>
          <td class="center">1</td>
        </tr>
        <tr>
          <td class="center">6</td>
          <td>IoT Trainer</td>
          <td class="center">2</td>
        </tr>
        <tr>
          <td class="center">7</td>
          <td>Portable Solar Power Experiment Box</td>
          <td class="center">1</td>
        </tr>
        <tr>
          <td class="center">8</td>
          <td>Programmable Logic Controller Training Set</td>
          <td class="center">1</td>
        </tr>
        <tr>
          <td class="center">9</td>
          <td>Proximity Sensor Training Set</td>
          <td class="center">1</td>
        </tr>
        <tr>
          <td class="center">10</td>
          <td>HMI/SCADA Training System</td>
          <td class="center">1</td>
        </tr>
        <tr>
          <td class="center">11</td>
          <td>PLC dan HMI Trainer</td>
          <td class="center">1</td>
        </tr>
        <tr>
          <td class="center">12</td>
          <td>Trainer Solar panel</td>
          <td class="center">2</td>
        </tr>
      </tbody>
    </table>

    <!-- Comments -->
    <div class="comments-block">
      <div class="comments-header">Comments Or Special Instructions</div>
      <div class="comments-body">
        <div class="comments-section-title">Pengiriman</div>
        <ul class="comments-list">
          <li>sebelum pengiriman dimohon untuk menghubungi +62 896-6804-5776</li>
          <li>Pengiriman dilakukan pada jam kerja<br>&nbsp;&nbsp;&nbsp;senin – Jum'at 08.00 – 14.00</li>
        </ul>
      </div>
    </div>

    <!-- Kota & Tanggal -->
    <div class="kota-tanggal">Sidoarjo, 06 November 2025</div>

    <!-- Signature -->
    <div class="signature-row">
      <div class="sig-block">
        <div class="sig-label">Pengirim</div>
        <div class="sig-space"></div>
        <div>
          <span class="sig-name-line">( &nbsp;&nbsp;&nbsp;&nbsp; Aristo R. &nbsp;&nbsp;&nbsp;&nbsp; )</span>
        </div>
        <div class="sig-sub">PT. Kamil Tria Niaga</div>
      </div>
      <div class="sig-block">
        <div class="sig-label">Penerima</div>
        <div class="sig-space"></div>
        <div>
          <span class="sig-name-line">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</span>
        </div>
      </div>
    </div>

  </main>

</body>
</html>