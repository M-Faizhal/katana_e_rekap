<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice - PT. Kamil Tria Niaga</title>
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
   * CORNER DECORATIONS
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
   * HEADER
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

  .inv-right-block { text-align: right; justify-content: space-between;  }
  .inv-date {
    font-size: 10pt;
    font-weight: bold;
    color: #032846;
    white-space: nowrap;
  }
  .inv-no {
    font-size: 10pt;
    margin-top: 70px;
  }

  /* Divider line under company block */
  .inv-divider {
    border: none;
    border-top: 1px solid #aaa;
    margin-bottom: 12px;
  }

  /* BILL TO / SHIP TO (use table for DomPDF stability) */
  .bill-ship-table { width: 100%; border-collapse: collapse; margin-top: 12px; margin-bottom: 14px; table-layout: fixed; }
  .bill-ship-table td { vertical-align: top; width: 50%; padding-right: 18px; }
  .bill-ship-table td:last-child { padding-right: 0; padding-left: 18px; }
  .bill-ship-label { font-weight: bold; font-size: 10pt; margin-bottom: 4px; }
  .bill-ship-col { font-size: 10pt; line-height: 1.4; word-break: break-word; overflow-wrap: break-word; }

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
  .inv-table td { padding: 6px 8px; border: 1px solid #999; vertical-align: top; font-size: 10pt;   overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; break-inside: avoid;}

  .inv-table td.center { text-align: center; vertical-align: middle; }
  .inv-table td.right { text-align: right; vertical-align: middle; }

  .item-name {
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
    display: inline-block;
    text-align: center;
    font-size: 10pt;
    margin-left: auto;
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

  /* Make right info block stable in DomPDF */
  .inv-top-table { width: 100%; border-collapse: collapse; }
  .inv-top-table td { vertical-align: top; }
  .inv-top-left { width: 70%; }
  .inv-top-right { width: 30%; text-align: right; }

  /* Bottom (payment left + approved right) using table for DomPDF */
  .bottom-table { width: 100%; border-collapse: collapse; margin-top: 18px; break-inside: avoid;}
  .bottom-table td { vertical-align: top; }
  .bottom-left { width: 60%; }
  .bottom-right { width: 40%; text-align: right; }
  .approved-block { display: inline-block; text-align: center; font-size: 10pt; }

  /* ============================================================
   * FOOTER
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
              <div class="footer-value">Magersari Permai Blok AW-23 RT. 024,<br>Sidoarjo, Jawa Timur</div>
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

    @php
      $tanggal = isset($invoice) && $invoice->tanggal_surat
          ? \Carbon\Carbon::parse($invoice->tanggal_surat)->translatedFormat('d F Y')
          : '';
      $nomor = $invoice->nomor_surat ?? '';

      $billInstansi = $invoice->bill_to_instansi ?? '';
      $billAlamat = $invoice->bill_to_alamat ?? '';
      $shipInstansi = $invoice->ship_to_instansi ?? '';
      $shipAlamat = $invoice->ship_to_alamat ?? '';

      $splitLines = function ($text) {
          $text = trim((string)$text);
          if ($text === '') return [];
          $text = str_replace(["\r\n", "\r"], "\n", $text);
          return array_values(array_filter(array_map('trim', explode("\n", $text)), fn($l) => $l !== ''));
      };
    @endphp

    <!-- Judul INVOICE -->
    <div class="inv-title">INVOICE</div>

    <table class="inv-top-table">
      <tr>
        <td class="inv-top-left">
          <div class="inv-company-block">
            <div class="inv-company-name">PT. KAMIL TRIA NIAGA</div>
            <div>Magersari Permai Blok AW-23 RT. 024</div>
            <div>RW. 007, Sidoarjo</div>
            <div>085155232320</div>
            <div><a href="mailto:kamiltrianiaga@gmail.com">kamiltrianiaga@gmail.com</a></div>
          </div>
        </td>
        <td class="inv-top-right">
          <div class="inv-date">{{ $tanggal }}</div>
          <div class="inv-no">{{ $nomor }}</div>
        </td>
      </tr>
    </table>

    <table class="bill-ship-table">
      <tr>
        <td>
          <div class="bill-ship-col">
            <div class="bill-ship-label">BILL TO</div>
            @if($billInstansi)
              <div>{{ $billInstansi }}</div>
            @endif
            @foreach($splitLines($billAlamat) as $line)
              <div>{{ $line }}</div>
            @endforeach
          </div>
        </td>
        <td>
          <div class="bill-ship-col">
            <div class="bill-ship-label">SHIP TO</div>
            @if($shipInstansi)
              <div>{{ $shipInstansi }}</div>
            @endif
            @foreach($splitLines($shipAlamat) as $line)
              <div>{{ $line }}</div>
            @endforeach
          </div>
        </td>
      </tr>
    </table>

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
        @foreach(($details ?? []) as $i => $d)
          @php
            $harga = (float)($d['harga_satuan'] ?? 0);
            $subtotal = (float)($d['subtotal'] ?? 0);
            $keteranganHtml = $d['keterangan_html'] ?? null;
          @endphp
          <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td>
              <div class="item-name">{{ $d['nama_barang'] ?? '-' }}</div>
              @if(!empty($keteranganHtml))
                <div class="item-desc">{!! $keteranganHtml !!}</div>
              @endif
            </td>
            <td class="center">{{ $d['qty'] ?? '-' }}</td>
            <td class="center">{{ $d['satuan'] ?? '-' }}</td>
            <td class="right">Rp {{ number_format($harga, 0, ',', '.') }}</td>
            <td class="right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
          </tr>
        @endforeach

        <!-- Terbilang + Total Row -->
        <tr class="terbilang-row">
          <td colspan="4" style="border-right: 1px solid #999; vertical-align: middle;">
            <div class="terbilang-label">Terbilang:</div>
            <div class="terbilang-value">
              {{ function_exists('terbilang') ? terbilang((float)($total ?? 0)) : '' }}
            </div>
          </td>
          <td class="total-label" style="border: 1px solid #999; vertical-align: middle;">
            <strong>TOTAL (Rp)</strong>
            <span>(Sudah Termasuk Pajak)</span>
          </td>
          <td class="total-value" style="border: 1px solid #999; vertical-align: middle; padding-right: 8px;">
            Rp {{ number_format((float)($total ?? 0), 0, ',', '.') }}
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Bottom: Pembayaran + Approved By -->
    <table class="bottom-table">
      <tr>
        <td class="bottom-left">
          <div class="payment-box">
            <div>Pembayaran transfer ke :</div>
            <div>&nbsp;</div>
            <div>Bank Mandiri Cab. Sidoarjo</div>
            <div>A/c 141-00-3180688-8</div>
            <div>A/n PT. Kamil Tria Niaga</div>
          </div>
        </td>
        <td class="bottom-right">
          <div class="approved-block">
            <div class="approved-label">Approved By</div>
            <div class="approved-sig-space">
              <!-- Tanda tangan / stempel -->
            </div>
            <div class="approved-name">BUSTANUDIN KAMIL, S.T</div>
            <div class="approved-title">Direktur</div>
          </div>
        </td>
      </tr>
    </table>

  </main>

</body>
</html>