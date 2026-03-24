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
   * TABEL — pastikan tidak ada page-break di tengah baris
   * ============================================================ */
  .product-table {
    width: 100%;
    border-collapse: collapse;
    margin: 8px 0;
    font-size: 10pt;
    /* Cegah tabel terpotong sembarangan */
    page-break-inside: auto;
  }
  .product-table thead {
    /* Header tabel diulang di setiap halaman baru */
    display: table-header-group;
  }
  .product-table tbody {
    display: table-row-group;
  }
  .product-table tr {
    /* Cegah satu baris terpotong di tengah */
    page-break-inside: avoid;
    break-inside: avoid;
  }
  .product-table th {
    background: #BDBDBD;
    color: #111;
    padding: 8px 5px;
    text-align: center;
    border: 1px solid #444;
    font-weight: bold;
  }
  .product-table td {
    padding: 4px 5px;
    border: 1px solid #999;
    vertical-align: middle;
  }
  .product-table td.center { text-align: center; }
  .product-table td.right  { text-align: right; }
  .product-table td a {
    color: #1a0dab;
    font-size: 9pt;
    word-break: break-all;
  }
  .product-table tr:nth-child(even) td { background: #f9f9f9; }

  /* ============================================================
   * ELEMEN LAIN
   * ============================================================ */
  .surat-title {
    text-align: center;
    font-size: 10pt;
    font-weight: bold;
    text-decoration: underline;
    margin-bottom: 15px;
    letter-spacing: 1px;
  }
  .meta-table {
    width: 100%;
    margin-bottom: 10px;
  }
  .meta-table tr td {
    padding: 1px 0;
    vertical-align: top;
    font-size: 10pt;
  }
  .meta-table .label { width: 80px; }
  .meta-table .colon { width: 14px; }
  .meta-row-right {
    text-align: right;
    font-size: 10pt;
    white-space: nowrap;
  }
  .recipient {
    margin-bottom: 10px;
    font-size: 10pt;
    line-height: 1.3;
  }
  .recipient .underline {
    text-decoration: underline;
    letter-spacing: 2px;
    font-weight: bold;
    margin-top: 2px;
  }
  .perihal-table {
    margin-bottom: 10px;
    font-size: 10pt;
  }
  .perihal-table td { vertical-align: top; }
  .perihal-label { width: 60px; white-space: nowrap; }
  .perihal-text  { font-weight: bold; }
  .paragraph {
    font-size: 10pt;
    line-height: 1.4;
    text-align: justify;
    margin-bottom: 8px;
    text-indent: 40px;
  }
  .bullet-list {
    font-size: 10pt;
    line-height: 1.4;
    margin: 4px 0 8px 20px;
    text-align: justify;
  }
  .bullet-list li { margin-bottom: 4px; }

  /* Tanda tangan — jangan dipotong halaman */
  .signature-table {
    width: 100%;
    margin-top: 10px;
    page-break-inside: avoid;
    break-inside: avoid;
  }
  .sig-block    { text-align: center; }
  .sig-company  { font-weight: bold; font-size: 10pt; margin-bottom: 4px; }
  .sig-name     { font-weight: bold; font-size: 10pt; text-decoration: underline; margin-top: 50px; }
  .sig-title    { font-size: 10pt; }

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

    <div class="surat-title">SURAT PENAWARAN</div>

    @php
      $bulanId = [
        1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
        7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember',
      ];
      $formatTanggalId = function(?string $tanggal) use ($bulanId): string {
        if (empty($tanggal)) return '-';
        try {
          $dt = \Carbon\Carbon::parse($tanggal);
          return $dt->day . ' ' . ($bulanId[$dt->month] ?? '') . ' ' . $dt->year;
        } catch (\Throwable $e) { return $tanggal; }
      };
    @endphp

    <table class="meta-table">
      <tr>
        <td class="label">Nomor</td>
        <td class="colon">:</td>
        <td>{{ $surat['nomor'] ?? '-' }}</td>
        <td class="meta-row-right">{{ $surat['tempat_tanggal'] ?? '-' }}</td>
      </tr>
      <tr>
        <td class="label">Lampiran</td>
        <td class="colon">:</td>
        <td colspan="2">{{ $surat['lampiran'] ?? '-' }}</td>
      </tr>
    </table>

    <div class="recipient">
      <div>Kepada Yth,</div>
      <div class="name-line">{{ $surat['kepada'] ?? '-' }}</div>
      <div>{{ $surat['alamat_klien'] ?? '-' }}</div>
      <div class="underline">{{ $surat['wilayah_klien'] ?? '-' }}</div>
    </div>

    <table class="perihal-table">
      <tr>
        <td class="perihal-label">Perihal &nbsp;: &nbsp;</td>
        <td class="perihal-text">{{ $surat['perihal'] ?? '-' }}</td>
      </tr>
    </table>

    <p class="paragraph">
      Melalui Surat ini, kami dari PT. Kamil Tria Niaga sebagai perusahaan yang bergerak dalam bidang penyedia
      barang dan jasa di ruang lingkup pemerintah ingin memberikan penawaran kepada <span>{{ $surat['kepada'] ?? '-' }}</span>.
    </p>

    <p class="paragraph">
      Bersama dengan surat ini, kami ingin mengajukan penawaran barang yang diharapkan sesuai dengan
      kebutuhan. Kami lampirkan rincian penawaran:
    </p>

    <!-- ============================================================ -->
    <!-- PRODUCT TABLE                                                 -->
    <!-- thead pakai display:table-header-group → diulang tiap halaman -->
    <!-- ============================================================ -->
    <table class="product-table">
      <thead>
        <tr>
          <th>Nama Barang</th>
          <th>Kuantiti</th>
          <th>Harga Satuan</th>
          <th>Jumlah Harga</th>
          <th>Link Produk</th>
        </tr>
      </thead>
      <tbody>
        @php
          $rows = $items ?? [[
            'nama_barang'  => '-',
            'qty'          => '0',
            'satuan'       => '-',
            'harga_satuan' => 0,
            'subtotal'     => 0,
            'link'         => '',
          ]];
        @endphp

        @foreach($rows as $row)
        <tr>
          <td>{{ $row['nama_barang'] ?? '-' }}</td>
          <td class="center">{{ $row['qty'] ?? '-' }} {{ $row['satuan'] ?? '' }}</td>
          <td class="right">
            @if(isset($row['harga_satuan']) && is_numeric($row['harga_satuan']))
              Rp {{ number_format($row['harga_satuan'], 0, ',', '.') }}
            @else
              {{ $row['harga_satuan'] ?? '-' }}
            @endif
          </td>
          <td class="right">
            @if(isset($row['subtotal']) && is_numeric($row['subtotal']))
              Rp {{ number_format($row['subtotal'], 0, ',', '.') }}
            @else
              {{ $row['subtotal'] ?? '-' }}
            @endif
          </td>
          <td>
            @if(!empty($row['link']))
              <a href="{{ $row['link'] }}" target="_blank">Link</a>
            @else
              -
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <p style="font-size:10pt; font-family:'Times New Roman',Times,serif; line-height:1.7; text-align:justify; margin-bottom:8px;">
      Harga tertera masih dapat didiskusikan sesuai keperluan dan sudah termasuk dengan layanan antar. Jika
      penawaran kami sesuai dengan kebutuhan, maka berlaku ketentuan sebagai berikut:
    </p>

    <ul class="bullet-list">
      <li>Kami akan melaksanakan pekerjaan tersebut dengan jangka waktu pelaksanaan pekerjaan selama <b>{{ $surat['jangka_waktu'] ?? '-' }}</b>.</li>
      <li>
        Penawaran ini berlaku selama 14 (Empat Belas) Hari kalender
        @if(!empty($surat['sejak']) && !empty($surat['sampai']))
          sejak tanggal {{ $formatTanggalId($surat['sejak']) }} sampai dengan {{ $formatTanggalId($surat['sampai']) }},
        @else
          sejak tanggal - sampai dengan -,
        @endif
        dan harga bisa berubah sewaktu-waktu.
      </li>
    </ul>

    <p class="paragraph">
      Penawaran ini sudah memperhatikan ketentuan dan persyaratan yang berlaku untuk melaksanakan pekerjaan
      tersebut di atas. Dengan disampaikannya surat penawaran ini, maka kami menyatakan sanggup melaksanakan
      semua ketentuan yang tercantum dalam berkas.
    </p>

    <table class="signature-table">
      <tr>
        <td style="width: 60%;"></td>
        <td style="width: 40%;" class="sig-block">
          <div class="sig-company">PT. KAMIL TRIA NIAGA</div>
          <br><br>
          <div class="sig-name">MAHENDA ABDILLAH KAMIL, S.Stat</div>
          <div class="sig-title">Direktur</div>
        </td>
      </tr>
    </table>

  </main>

</body>
</html>