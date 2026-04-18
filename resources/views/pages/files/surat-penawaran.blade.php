<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Surat Penawaran - PT. Kamil Tria Niaga</title>
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

  .content {
    padding: 0px 60px 0px 60px;
    position: relative;
    z-index: 0;
  }

  .product-table {
    width: 100%;
    border-collapse: collapse;
    margin: 8px 0;
    font-size: 10pt;
    page-break-inside: auto;
  }
  .product-table thead {
    display: table-header-group;
  }
  .product-table tbody {
    display: table-row-group;
  }
  .product-table tr {
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
    line-height: 30px;
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
        <!-- Address Head Office -->
        <td>
          <div>
            <div class="footer-icon">
              <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/maps.png'))) }}" alt="">
            </div>
            <div class="footer-text-block">
              <div class="footer-label">Address Head Office</div>
              <div class="footer-value">Magersari Permai Blok AW-23 RT. 024 RW. 007,<br>Sidoarjo, Jawa Timur</div>
            </div>
          </div>
        </td>
        <!-- Email -->
        <td>
          <div>
            <div class="footer-icon">
              <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/email.png'))) }}" alt="">
            </div>
            <div class="footer-text-block">
              <div class="footer-label">Email</div>
              <div class="footer-value">kamiltrianiaga@gmail.com</div>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <!-- Address Branch Office -->
        <td>
          <div>
            <div class="footer-icon">
              <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/maps.png'))) }}" alt="">
            </div>
            <div class="footer-text-block">
              <div class="footer-label">Address Branch Office</div>
              <div class="footer-value">Jl. H. Abu No.57 3, RT.3/RW.7, Cipete Sel,<br>Kec. Cilandak, DKI Jakarta</div>
            </div>
          </div>
        </td>
        <!-- Phone -->
        <td>
          <div>
            <div class="footer-icon">
              <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/telephone.png'))) }}" alt="">
            </div>
            <div class="footer-text-block">
              <div class="footer-label">Phone</div>
              <div class="footer-value">0851-5523-2320</div>
            </div>
          </div>
        </td>
      </tr>
    </table>
  </footer>

  <!-- CONTENT -->
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
              <div style="text-align: center;">
                @if($row['link'] && $row['link'] !== '-')
                    <a href="{{ $row['link'] }}" 
                      target="_blank" 
                      rel="noopener noreferrer"
                      style="
                          color: #1a56db; 
                          text-decoration: underline; 
                          font-size: 10px;
                          word-break: break-all;
                          overflow-wrap: break-word;
                          display: inline-block;
                          max-width: 150px;
                      ">
                        {{ $row['link'] }}
                    </a>
                @else
                    <span style="color: #6b7280;">-</span>
                @endif
            </div>
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
          <div class="sig-name">BUSTANUDIN KAMIL, S.T</div>
          <div class="sig-title">Direktur</div>
        </td>
      </tr>
    </table>

  </main>

</body>
</html>