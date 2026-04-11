<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat PO - PT. Kamil Tria Niaga</title>
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
    color: #000;
    background: #fff;
  }

  /* ============================================================
   * CORNER DECORATIONS
   * ============================================================ */
  .corner-tl {
    position: fixed; top: -130px; right: 0;
    width: 140px; height: 100px; z-index: 20;
  }
  .corner-br {
    position: fixed; bottom: -135px; left: 0;
    width: 140px; height: 140px; z-index: 20;
  }
  .corner-img { width: 100%; height: 100%; object-fit: cover; }

  /* ============================================================
   * HEADER — fixed
   * ============================================================ */
  header {
    position: fixed; top: -130px; left: 0; right: 0;
    padding: 14px 0 14px 50px;
    border-bottom: 5px solid #C62828;
    background: white;
    z-index: 15;
    overflow: hidden;
  }
  .header-table { width: 100%; border-collapse: collapse; }
  .header-table td { vertical-align: middle; }
  .logo-img { width: 80px; height: 80px; display: block; }
  .company-name {
    font-family: Arial, sans-serif;
    font-size: 22px; font-weight: 800;
    color: #222; line-height: 1.1;
    letter-spacing: 1px; margin-left: 10px;
  }

  /* ============================================================
   * CONTENT
   * ============================================================ */
  .content {
    padding: 0px 60px 0px 60px;
  }

  /* ============================================================
   * PO HEADER — table-based (dompdf safe)
   * ============================================================ */
  .po-header-outer {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 12px;
  }
  .po-header-outer td {
    vertical-align: top;
    padding: 0;
  }
  .po-company-block { font-size: 10pt; line-height: 1.6; }
  .po-company-block .company-title { font-weight: bold; font-size: 11pt; margin-bottom: 2px; }
  .po-company-block a { color: #1a0dab; font-size: 9pt; }

  .po-title-block { text-align: right; }
  .po-title {
    font-family: Arial, sans-serif;
    font-size: 26pt; font-weight: 900;
    color: #424242; letter-spacing: 2px;
    line-height: 1; margin-bottom: 8px;
    display: block;
  }
  .po-meta-table { border-collapse: collapse; margin-left: auto; }
  .po-meta-table td { padding: 3px 8px; font-size: 10pt; border: 1px solid #999; }
  .po-meta-table .meta-label { font-weight: bold; background: #f0f0f0; white-space: nowrap; min-width: 55px; }
  .po-meta-table .meta-value { min-width: 120px; text-align: left; }

  /* ============================================================
   * VENDOR / SHIP TO — full width, stacked, table-based
   * ============================================================ */
  .section-box {
    width: 100%;
    border-collapse: collapse;
  }
  .section-box td { padding: 0; }
  .box-header {
    background: #BDBDBD;
    font-weight: bold;
    font-size: 10pt;
    padding: 4px 8px;
  }
  .box-body {
    padding: 8px 10px;
    font-size: 10pt;
    line-height: 1.6;
    min-height: 55px;
  }
  .box-body .vendor-name { font-weight: bold; margin-bottom: 2px; }

  /* ============================================================
   * MAIN TABLE — dompdf safe, word-wrap on spec col
   * ============================================================ */
  .po-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 12px;
    font-size: 10pt;
    table-layout: fixed;
  }
  .po-table th {
    background: #BDBDBD;
    color: #111;
    padding: 6px 5px;
    text-align: center;
    border: 1px solid #555;
    font-weight: bold;
    font-size: 10pt;
    overflow: hidden;
  }
  .po-table td {
    padding: 6px 6px;
    border: 1px solid #999;
    vertical-align: top;
    font-size: 10pt;
    word-wrap: break-word;
    overflow-wrap: break-word;
    overflow: hidden;
  }
  .po-table td.center { text-align: center; vertical-align: middle; }
  .po-table td.right  { text-align: right;  vertical-align: middle; }
  .po-table td.middle { vertical-align: middle; }
  .po-table tr:nth-child(even) td { background: #f9f9f9; }

  .spec-list {
    list-style: disc;
    padding-left: 14px;
    margin: 0;
    line-height: 1.55;
    font-size: 9.5pt;
    word-wrap: break-word;
  }
  .spec-list li { margin-bottom: 1px; }

  /* ============================================================
   * BOTTOM SECTION — table-based (dompdf safe)
   * ============================================================ */
  .bottom-outer {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 14px;
  }
  .bottom-outer > tbody > tr > td {
    vertical-align: top;
    padding: 0;
  }
  .td-comments { width: 58%; padding-right: 8px !important; }
  .td-totals   { width: 42%; }

  /* Comments */
  .comments-block { border: 1px solid #999; font-size: 9.5pt; line-height: 1.6; width: 100%; }
  .comments-header { background: #BDBDBD; font-weight: bold; padding: 4px 8px; border-bottom: 1px solid #999; font-size: 10pt; }
  .comments-body { padding: 8px 10px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;}
  .comments-section-title { font-weight: bold; margin-top: 7px; margin-bottom: 2px; font-size: 10pt; }
  .comments-section-title:first-child { margin-top: 0; }
  .comments-list { list-style: disc; padding-left: 14px; margin: 0; line-height: 1.6; }
  .comments-list li { margin-bottom: 1px; }
  .comments-sublist { list-style: none; padding-left: 10px; margin: 2px 0; line-height: 1.6; }
  .comments-sublist li::before { content: "- "; }

  /* Totals */
  .totals-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 6px;
  }
  .totals-table td { padding: 4px 8px; border: 1px solid #999; font-size: 10pt; }
  .totals-table .t-label { width: 45%; background: #fff; }
  .totals-table .t-value { text-align: right; }
  .totals-table .t-total-row td { background: #333; color: #fff; font-weight: bold; }

  .dp-table { width: 100%; border-collapse: collapse; }
  .dp-table td { padding: 4px 8px; border: 1px solid #999; font-size: 10pt; }
  .dp-table .dp-label { width: 38%; font-weight: bold; }
  .dp-table .dp-pct   { width: 20%; text-align: center; font-weight: bold; }
  .dp-table .dp-value { text-align: right; }

  /* ============================================================
   * SIGNATURE
   * ============================================================ */
  .sig-dept { font-size: 10pt; font-weight: bold; margin-bottom: 2px; }
  .sig-company-name { font-size: 10pt; font-weight: bold;  letter-spacing: 0.5px; margin-bottom: 4px; }
  .sig-space { height: 70px; }
  .sig-name-line { border-bottom: 1px solid #333; padding-top: 3px; font-size: 10pt; font-weight: bold; }

  /* ============================================================
   * FOOTER — fixed
   * ============================================================ */
  footer {
    position: fixed; bottom: -135px; left: 0; right: 0;
    background: white; color: #000;
    padding: 6px 30px 14px 100px;
    font-size: 9pt; z-index: 10; box-sizing: border-box;
  }
  .footer-table { width: 100%; border-collapse: collapse; }
  .footer-table td { width: 50%; vertical-align: top; padding-bottom: 6px; }
  .footer-icon {
    float: left; width: 32px; height: 32px;
    text-align: center;
    border: 1.5px solid #c0392b; border-radius: 50%; background: #fff;
  }
  .footer-icon img { width: 16px; height: 16px; margin-top: 7px; }
  .footer-text-block { margin-left: 38px; }
  .footer-label { color: #000; font-size: 9pt; margin-bottom: 1px; }
  .footer-value { color: #c0392b; line-height: 1.3; font-size: 9pt; }

  @media print {
    body { background: none; }
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

  <!-- HEADER FIXED -->
  <header>
    <table class="header-table">
      <tr>
        <td style="width:80px;">
          <img class="logo-img" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Logo">
        </td>
        <td>
          <div class="company-name">KAMIL TRIA<br>NIAGA</div>
        </td>
      </tr>
    </table>
  </header>

  <!-- FOOTER FIXED -->
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
            <div class="footer-icon"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0iI2MwMzkyYiI+PHBhdGggZD0iTTEyIDJDOC4xNCAyIDUgNS4xNCA1IDljMCA1LjI1IDcgMTMgNyAxM3M3LTcuNzUgNy0xM2MwLTMuODYtMy4xNC03LTctN3ptMCA5LjVjLTEuMzggMC0yLjUtMS4xMi0yLjUtMi41czEuMTItMi41IDIuNS0yLjUgMi41IDEuMTIgMi41IDIuNS0xLjIgMi41LTIuNSAyLjV6Ii8+PC9zdmc+" alt=""></div>
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

    <!-- PO HEADER: company kiri | title+meta kanan (TABLE BASED) -->
    <table class="po-header-outer">
      <tr>
        <td style="width:55%;">
          <div class="po-company-block">
            <div class="company-title">PT. KAMIL TRIA NIAGA</div>
            <div>Magersari No.23-24 Blok AW, Gajah Timur,</div>
            <div>Magersari, Kec. Sidoarjo, Kabupaten Sidoarjo,</div>
            <div>Jawa Timur 61212</div>
            <div><a href="https://ptkatana.com/">https://ptkatana.com/</a></div>
          </div>
        </td>
        <td style="width:45%; vertical-align:top; text-align:right;">
          <span class="po-title">PURCHASE ORDER</span>
          <table class="po-meta-table">
            <tr>
              <td class="meta-label">DATE</td>
              <td class="meta-value">{{ optional($suratPo->tanggal_surat)->format('d/m/Y') ?? '-' }}</td>
            </tr>
            <tr>
              <td class="meta-label">PO #</td>
              <td class="meta-value">{{ $suratPo->po_number ?? ($proyek->kode_proyek ?? '-') }}</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <!-- VENDOR & SHIP TO — side by side, no outer border -->
    <table style="width:100%;  margin-bottom:12px;">
      <tr>
        <!-- VENDOR kiri -->
        <td style="width:50%; vertical-align:top; padding:0;  ">
          <div class="box-header">VENDOR</div>
          <div class="box-body">
            <div class="vendor-name">{{ $vendor->nama_vendor ?? '-' }}</div>
            @if(!empty($vendor->alamat))
              {!! nl2br(e($vendor->alamat)) !!}
            @endif
          </div>
        </td>
        <!-- SHIP TO kanan -->
        <td style="width:50%; vertical-align:top; padding:0;  border-left:none;">
          <div class="box-header">SHIP TO</div>
          <div class="box-body">
            <div class="vendor-name">{{ $suratPo->ship_to_instansi ?? ($proyek->instansi ?? '-') }}</div>
            <div>{!! nl2br(e($suratPo->ship_to_alamat ?? ($proyek->kab_kota ?? '-'))) !!}</div>
          </div>
        </td>
      </tr>
    </table>

    <!-- MAIN PRODUCT TABLE -->
    <table class="po-table">
      <thead>
        <tr>
          <th style="width:4%;">NO</th>
          <th style="width:16%;">PRODUCT</th>
          <th style="width:42%;">SPESIFICATION</th>
          <th style="width:7%;">QTY</th>
          <th style="width:15%;">UNIT PRICE</th>
          <th style="width:16%;">TOTAL</th>
        </tr>
      </thead>
      <tbody>
        @foreach(($suratPo->items ?? collect()) as $i => $item)
          <tr>
            <td class="center">{{ $i+1 }}</td>
            <td class="middle"><strong>{{ $item->barang->nama_barang ?? '-' }}</strong></td>
            <td>
              @if(!empty($item->spec_html))
                @php
                  $specSafe = (string) $item->spec_html;
                  $specSafe = preg_replace('~<img\b[^>]*\bsrc\s*=\s*(["\"])\s*[^"\']*?\.webp(?:\?[^"\']*)?\1[^>]*>~i', '', $specSafe);
                  $specSafe = preg_replace('~\ssrcset\s*=\s*(["\"]).*?webp.*?\1~i', '', $specSafe);
                  $specSafe = preg_replace('~url\(([^)]*?\.webp[^)]*)\)~i', 'url()', $specSafe);
                  $specSafe = preg_replace('~background(?:-image)?\s*:\s*[^;]*?\.webp[^;]*;?~i', '', $specSafe);
                @endphp
                {!! $specSafe !!}
              @else
                <span style="color:#666;">-</span>
              @endif
            </td>
            <td class="center">{{ $item->qty }}</td>
            <td class="right">Rp {{ number_format((float)$item->unit_price, 0, ',', '.') }}</td>
            <td class="right">Rp {{ number_format((float)$item->line_total, 0, ',', '.') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <!-- BOTTOM SECTION (TABLE BASED — dompdf safe) -->
    <table class="bottom-outer">
      <tr>
        <!-- Comments kiri -->
        <td class="td-comments">
          <div class="comments-block">
            <div class="comments-header">COMMENTS Or SPECIAL INTRUCTIONS</div>
            <div class="comments-body">
              @if(!empty($suratPo->comments_html))
                @php
                  $commentsSafe = (string) $suratPo->comments_html;
                  $commentsSafe = preg_replace('~<img\b[^>]*\bsrc\s*=\s*(["\"])\s*[^"\']*?\.webp(?:\?[^"\']*)?\1[^>]*>~i', '', $commentsSafe);
                  $commentsSafe = preg_replace('~\ssrcset\s*=\s*(["\"]).*?webp.*?\1~i', '', $commentsSafe);
                  $commentsSafe = preg_replace('~url\(([^)]*?\.webp[^)]*)\)~i', 'url()', $commentsSafe);
                  $commentsSafe = preg_replace('~background(?:-image)?\s*:\s*[^;]*?\.webp[^;]*;?~i', '', $commentsSafe);
                @endphp
                {!! $commentsSafe !!}
              @else
                <div style="color:#666;">-</div>
              @endif
            </div>
          </div>
        </td>

        <!-- Totals kanan -->
        <td class="td-totals">
          <table class="totals-table">
            <tr>
              <td class="t-label">DPP</td>
              <td class="t-value">Rp {{ number_format((float)$suratPo->dpp, 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td class="t-label">TAX</td>
              <td class="t-value">Rp {{ number_format((float)($suratPo->tax ?? 0), 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td class="t-label">SHIPPING</td>
              <td class="t-value">Rp {{ number_format((float)($suratPo->shipping ?? 0), 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td class="t-label">OTHER</td>
              <td class="t-value">Rp {{ number_format((float)($suratPo->other ?? 0), 0, ',', '.') }}</td>
            </tr>
            <tr class="t-total-row">
              <td class="t-label">TOTAL</td>
              <td class="t-value">Rp {{ number_format((float)$suratPo->total, 0, ',', '.') }}</td>
            </tr>
          </table>

          <table class="dp-table" style="margin-top:6px;">
            <tr>
              <td class="dp-label">DP</td>
              <td class="dp-pct">{{ number_format((float)$suratPo->dp_percent, 0) }} %</td>
              <td class="dp-value">Rp {{ number_format((float)$suratPo->dp_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td class="dp-label">Termin 2</td>
              <td class="dp-pct">{{ number_format((float)$suratPo->termin2_percent, 0) }} %</td>
              <td class="dp-value">Rp {{ number_format((float)$suratPo->termin2_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td class="dp-label">Pelunasan</td>
              <td class="dp-pct">{{ number_format((float)$suratPo->pelunasan_percent, 0) }} %</td>
              <td class="dp-value">Rp {{ number_format((float)$suratPo->pelunasan_amount, 0, ',', '.') }}</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <!-- SIGNATURE -->
<table style="width:100%; border-collapse:collapse; margin-top:20px; break-inside: avoid;">
  <tr>
    <td style="width:100%; text-align:right; vertical-align:top;">

      <table style="border-collapse:collapse; display:inline-table;">
        <tr>
          <td style="text-align:center; min-width:200px; padding:0 20px;">

            <!-- DEPT -->
            <div style="font-weight:bold; font-size:12px;">
              PURCHASING
            </div>

            <!-- COMPANY -->
            <div style="font-weight:bold; font-size:12px;">
              PT. KAMIL TRIA NIAGA
            </div>

            <!-- SIGNATURE IMAGE -->
            @if(!empty($suratPo->ttd_purchasing))
            <div style="margin-top:10px;">
              <img src="{{ public_path('storage/'.$suratPo->ttd_purchasing) }}"
                   style="height:80px;">
            </div>
            @else
            <div style="height:80px;"></div>
            @endif

            <!-- NAME LINE -->
            <div style="margin-top:5px;">
              ( ........................................ )
            </div>

            <!-- NAME -->
            <div style="font-weight:bold;">
              {{ $proyek->adminPurchasing->nama ?? $suratPo->purchasing->name ?? 'ARISTO R.' }}
            </div>

          </td>
        </tr>
      </table>

    </td>
  </tr>
</table>

  </main>
</body>
</html>