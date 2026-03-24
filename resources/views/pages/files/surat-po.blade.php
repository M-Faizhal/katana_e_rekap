<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Surat Penawaran - PT. Kamil Tria Niaga</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  /*
   * ============================================================
   * KUNCI MULTI-HALAMAN:
   * 1. @page mendefinisikan margin atas/bawah halaman sebesar
   *    tinggi header + tinggi footer (+ sedikit padding ekstra).
   * 2. Header & footer pakai position:fixed dengan top/bottom:0
   *    dan z-index tinggi — browser print engine akan me-render
   *    ulang elemen fixed di setiap halaman.
   * 3. .content TIDAK boleh pakai padding-top/bottom sendiri
   *    karena sudah diurus oleh @page margin.
   * ============================================================
   */

  @page {
    size: A4;
    /* 
      margin-top  = tinggi header (header padding 18+18 + border 5 + logo 80 ≈ 121px)
                    + sedikit ruang napas → pakai 130px
      margin-bottom = tinggi footer (2 baris × ~50px + padding ≈ 120px)
                    → pakai 130px
      margin-left / margin-right bebas sesuai kebutuhan
    */
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
   * Pastikan tinggi totalnya ≤ margin-top @page di atas
   * ============================================================ */
  header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    /* Tinggi total header:
       padding-top 18 + padding-bottom 18 + logo 80 + border 5 = 121px
       Beri sedikit ruang: 121px sudah cukup, kita biarkan natural */
    padding: 14px 0 14px 50px;
    border-bottom: 5px solid #C62828;
    background: white;
    z-index: 15;
    /* Penting: overflow hidden agar tidak meluber */
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
   * CONTENT — padding mengikuti @page margin secara natural
   * Jangan set padding-top/bottom di sini; @page sudah handle.
   * Tapi untuk tampilan di BROWSER (non-print) kita tetap beri
   * padding agar tidak tertutup header/footer.
   * ============================================================ */
  .content {
    /* Untuk preview di browser: */
    padding: 130px 80px 135px 80px;
    position: relative;
    z-index: 0;
  }
  /* ============================================================
   * PO HEADER ROW
   * ============================================================ */
  .po-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
  }
  .po-company-block {
    font-size: 10pt;
    line-height: 1.5;
  }
  .po-company-block .company-title {
    font-weight: bold;
    font-size: 11pt;
    margin-bottom: 4px;
  }
  .po-company-block a {
    color: #1a0dab;
    font-size: 9pt;
  }
  .po-title-block {
    text-align: right;
  }
  .po-title {
    font-family: Arial, sans-serif;
    font-size: 26pt;
    font-weight: 900;
    color: #424242;
    letter-spacing: 2px;
    line-height: 1;
    margin-bottom: 8px;
  }
  .po-meta-table {
    border-collapse: collapse;
    margin-left: auto;
  }
  .po-meta-table td {
    padding: 2px 6px;
    font-size: 10pt;
    border: 1px solid #999;
  }
  .po-meta-table .meta-label {
    font-weight: bold;
    background: #eee;
    white-space: nowrap;
  }
  .po-meta-table .meta-value {
    min-width: 100px;
  }
 
  /* ============================================================
   * VENDOR / SHIP TO
   * ============================================================ */
  .vendor-row {
    display: flex;
    gap: 10px;
    margin-bottom: 12px;
  }
  .vendor-box, .shipto-box {
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
    padding: 6px 8px;
    font-size: 10pt;
    line-height: 1.5;
    min-height: 70px;
  }
  .box-body .vendor-name {
    font-weight: bold;
    margin-bottom: 3px;
  }
 
  /* ============================================================
   * MAIN TABLE
   * ============================================================ */
  .po-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
    font-size: 10pt;
    page-break-inside: auto;
  }
  .po-table thead {
    display: table-header-group;
  }
  .po-table tbody {
    display: table-row-group;
  }
  .po-table tr {
    page-break-inside: avoid;
    break-inside: avoid;
  }
  .po-table th {
    background: #BDBDBD;
    color: #111;
    padding: 6px 5px;
    text-align: center;
    border: 1px solid #555;
    font-weight: bold;
    font-size: 10pt;
  }
  .po-table td {
    padding: 4px 6px;
    border: 1px solid #999;
    vertical-align: top;
    font-size: 10pt;
  }
  .po-table td.center { text-align: center; }
  .po-table td.right { text-align: right; }
  .po-table tr:nth-child(even) td { background: #f9f9f9; }
 
  .spec-list {
    list-style: disc;
    padding-left: 14px;
    margin: 0;
    line-height: 1.5;
    font-size: 9.5pt;
  }
  .spec-list li { margin-bottom: 1px; }
 
  /* ============================================================
   * BOTTOM SECTION: Comments + Totals side by side
   * ============================================================ */
  .bottom-section {
    display: flex;
    gap: 10px;
    margin-bottom: 12px;
    align-items: flex-start;
  }
  .comments-block {
    flex: 1.4;
    border: 1px solid #999;
    font-size: 9.5pt;
    line-height: 1.5;
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
    margin-top: 6px;
    margin-bottom: 2px;
    font-size: 9.5pt;
  }
  .comments-section-title:first-child {
    margin-top: 0;
  }
  .comments-list {
    list-style: disc;
    padding-left: 14px;
    margin: 0;
  }
  .comments-list li { margin-bottom: 1px; }
 
  .totals-block {
    flex: 1;
  }
  .totals-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 6px;
  }
  .totals-table td {
    padding: 3px 6px;
    border: 1px solid #999;
    font-size: 10pt;
  }
  .totals-table .t-label { font-weight: bold; background: #eee; width: 40%; }
  .totals-table .t-value { text-align: right; }
  .totals-table .t-total-row td {
    background: #333;
    color: #fff;
    font-weight: bold;
  }
  .totals-table .t-total-row .t-label { background: #333; }
 
  .dp-table {
    width: 100%;
    border-collapse: collapse;
  }
  .dp-table td {
    padding: 3px 6px;
    border: 1px solid #999;
    font-size: 10pt;
  }
  .dp-table .dp-label { background: #eee; font-weight: normal; width: 40%; }
  .dp-table .dp-value { text-align: right; }
 
  /* ============================================================
   * SIGNATURE
   * ============================================================ */
  .signature-section {
    display: flex;
    justify-content: flex-end;
    margin-top: 8px;
    page-break-inside: avoid;
    break-inside: avoid;
  }
  .sig-box {
    text-align: center;
    min-width: 160px;
  }
  .sig-dept {
    font-size: 9.5pt;
    font-weight: bold;
    margin-bottom: 2px;
  }
  .sig-company-name {
    font-size: 9.5pt;
    font-weight: bold;
    text-decoration: underline;
    letter-spacing: 0.5px;
  }
  .sig-space {
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-style: italic;
    color: #aaa;
    font-size: 9pt;
  }
  .sig-name-line {
    border-top: 1px solid #333;
    padding-top: 2px;
    font-size: 9.5pt;
    font-weight: bold;
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

  /* ============================================================
   * PRINT OVERRIDES
   * Saat dicetak/@media print, padding .content diatur ulang
   * supaya pas dengan @page margin (tidak double-padding).
   * ============================================================ */
  @media print {
    body { background: none; padding: 0; margin: 0; }
    .content {
      /* @page sudah atur margin, jadi padding kiri-kanan saja */
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
 
    <!-- PO Header: Company Info + PO Title -->
    <div class="po-header-row">
      <div class="po-company-block">
        <div class="company-title">PT. KAMIL TRIA NIAGA</div>
        <div>Magersari No.23-24 Blok AW, Gajah Timur,</div>
        <div>Magersari, Kec. Sidoarjo, Kabupaten Sidoarjo,</div>
        <div>Jawa Timur 61212</div>
        <div><a href="https://ptkatana.com/">https://ptkatana.com/</a></div>
      </div>
      <div class="po-title-block">
        <div class="po-title">PURCHASE ORDER</div>
        <table class="po-meta-table">
          <tr>
            <td class="meta-label">DATE</td>
            <td class="meta-value">24/11/2025</td>
          </tr>
          <tr>
            <td class="meta-label">PO #</td>
            <td class="meta-value">520</td>
          </tr>
        </table>
      </div>
    </div>
 
    <!-- Vendor & Ship To -->
    <div class="vendor-row">
      <div class="vendor-box">
        <div class="box-header">VENDOR</div>
        <div class="box-body">
          <div class="vendor-name">Sofaku</div>
          <div>Jl. KH. Hasyim Ashari No.83, RT.002/RW.003,</div>
          <div>Poris Plawad Utara, Kec. Cipondoh, Kota</div>
          <div>Tangerang, Banten 15141</div>
        </div>
      </div>
      <div class="shipto-box">
        <div class="box-header">SHIP TO</div>
        <div class="box-body">
          <div>[Terlampir]</div>
        </div>
      </div>
    </div>
 
    <!-- Main Product Table -->
    <table class="po-table">
      <thead>
        <tr>
          <th style="width:4%;">NO</th>
          <th style="width:18%;">PRODUCT</th>
          <th style="width:42%;">SPESIFICATION</th>
          <th style="width:8%;">QTY</th>
          <th style="width:14%;">UNIT PRICE</th>
          <th style="width:14%;">TOTAL</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="center">1</td>
          <td><strong>Kursi Stool Laboratorium</strong></td>
          <td>
            <ul class="spec-list">
              <li>Bahan Produk: Papan Plywood + Busa Cetak</li>
              <li>Dimensi Produk/Ukuran : P 38 x L 38 x T 65 - 75 cm</li>
              <li>Dudukan : menggunakan papan Plywood dengan bantalan busa cetak dan cover menggunakan bahan Oscar</li>
              <li>Kaki menggunakan mekanis</li>
              <li>Gaslift ukuran 260 mm + footing bahan besi chrome | Kaki kursi menggunakan bahan PP uk. 320 mm</li>
              <li>Kursi menggunakan roda</li>
              <li>TKDN 40.00%</li>
            </ul>
          </td>
          <td class="center">216</td>
          <td class="right">Rp 620.000</td>
          <td class="right">Rp 133.920.000</td>
        </tr>
      </tbody>
    </table>
 
    <!-- Bottom: Comments + Totals -->
    <div class="bottom-section">
      <!-- Comments -->
      <div class="comments-block">
        <div class="comments-header">COMMENTS Or SPECIAL INTRUCTIONS</div>
        <div class="comments-body">
          <ul class="comments-list">
            <li>Harga tersebut <u><strong>include / Exclude</strong></u> Ppn 11%</li>
            <li>Harap Mencantumkan Surat Dukungan Principal</li>
            <li>Garansi resmi dari principal</li>
          </ul>
 
          <div class="comments-section-title">PEMBAYARAN</div>
          <ul class="comments-list">
            <li>Terms Pembayaran <u>2</u> Kali (30%, 30%, 40%)</li>
            <li>
              <strong>- DP / Termia 1</strong>: Project Mulai<br>
              <strong>- Termin 2</strong>: Produksi Mencapai >50%<br>
              <strong>- Pelunasan</strong>: Konfirmasi Pengiriman<br>
              * dapat didiskusikan sesuai kebutuhan
            </li>
          </ul>
 
          <div class="comments-section-title">PENGIRIMAN</div>
          <ul class="comments-list">
            <li>Target Pengerjaan <strong>19 Desember 2025</strong></li>
            <li>sebelum pengiriman dimohon untuk menghubungi +62 896-6804-5776</li>
            <li>Pengiriman dilakukan pada jam kerja senin – Jum'at 08.00 – 14.00</li>
          </ul>
        </div>
      </div>
 
      <!-- Totals -->
      <div class="totals-block">
        <table class="totals-table">
          <tr>
            <td class="t-label">DPP</td>
            <td class="t-value">Rp 133.920.000</td>
          </tr>
          <tr>
            <td class="t-label">TAX</td>
            <td class="t-value">Rp -</td>
          </tr>
          <tr>
            <td class="t-label">SHIPPING</td>
            <td class="t-value">Rp -</td>
          </tr>
          <tr>
            <td class="t-label">OTHER</td>
            <td class="t-value">Rp -</td>
          </tr>
          <tr class="t-total-row">
            <td class="t-label">TOTAL</td>
            <td class="t-value">Rp 133.920.000</td>
          </tr>
        </table>
 
        <table class="dp-table">
          <tr>
            <td class="dp-label">DP</td>
            <td style="text-align:center; border:1px solid #999;">30 %</td>
            <td class="dp-value">Rp 40.176.000</td>
          </tr>
          <tr>
            <td class="dp-label">Termin 2</td>
            <td style="text-align:center; border:1px solid #999;">30 %</td>
            <td class="dp-value">Rp 40.176.000</td>
          </tr>
          <tr>
            <td class="dp-label">Pelunasan</td>
            <td style="text-align:center; border:1px solid #999;">40 %</td>
            <td class="dp-value">Rp 53.568.000</td>
          </tr>
        </table>
      </div>
    </div>
 
    <!-- Signature -->
    <div class="signature-section">
      <div class="sig-box">
        <div class="sig-dept">PURCHASING</div>
        <div class="sig-company-name">PT. KAMIL TRIA NIAGA</div>
        <div class="sig-space"></div>
        <div class="sig-name-line">( &nbsp;&nbsp;&nbsp;&nbsp; ARISTO R. &nbsp;&nbsp;&nbsp;&nbsp; )</div>
      </div>
    </div>
 
  </main>

</body>
</html>