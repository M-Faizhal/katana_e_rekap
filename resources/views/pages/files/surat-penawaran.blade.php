<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Surat Penawaran - PT. Kamil Tria Niaga</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman:ital,wght@0,400;0,700;1,400&family=Arial:wght@400;700&display=swap');

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Times New Roman', Times, serif;
    font-size: 10pt;
    margin: 0;
    padding: 0;
    background: #fff;
  }

  .page {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
  }

  /* Corner Decorations */
  .corner-tl, .corner-br {
    position: absolute;
    z-index: 0;
  }

  .corner-tl {
    top: 0;
    right: 0;
    width: 140px;
    height: 100px;
    position: fixed;
  }

  .corner-br {
    bottom: 0;
    left: 0;
    width: 140px;
    height: 140px;
    position: fixed;
  }

  .corner-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  /* Header menggunakan Tabel */
  header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    padding: 18px 0px 18px 50px;
    border-bottom: 5px solid #C62828;
    z-index: 1;
    background: white;
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

  /* Body content */
  .content {
    padding: 140px 80px 150px 80px; /* Ditambahkan padding atas bawah agar tidak tertutup header/footer tetap */
    position: relative;
    z-index: 1;
  }

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
  .recipient .name-line { font-weight: normal; }
  .recipient .underline {
    text-decoration: underline;
    letter-spacing: 2px;
    font-weight: bold;
    margin-top: 2px;
  }

  /* Mengganti Flex di perihal dengan Table */
  .perihal-table {
    margin-bottom: 10px;
    font-size: 10pt;
  }
  .perihal-table td {
    vertical-align: top;
  }
  .perihal-label { width: 60px; white-space: nowrap; }
  .perihal-text { font-weight: bold; }

  .paragraph {
    font-size: 10pt;
    line-height: 1.4;
    text-align: justify;
    margin-bottom: 8px;
    text-indent: 40px;
  }

  /* Product Table */
  .product-table {
    width: 100%;
    border-collapse: collapse;
    margin: 8px 0;
    font-size: 10pt;
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
    padding: 1px 4px;
    border: 1px solid #999;
    vertical-align: middle;
  }
  .product-table td.center { text-align: center; }
  .product-table td.right { text-align: right; }
  .product-table td a {
    color: #1a0dab;
    font-size: 9pt;
    word-break: break-all;
  }
  .product-table tr:nth-child(even) td { background: #f9f9f9; }

  /* Bullet list */
  .bullet-list {
    font-size: 10pt;
    line-height: 1.4;
    margin: 4px 0 8px 20px;
    text-align: justify;
  }
  .bullet-list li {
    margin-bottom: 4px;
  }

  /* Signature - Menggunakan tabel untuk layout ke kanan */
  .signature-table {
    width: 100%;
    margin-top: 10px;
  }
  .sig-block {
    text-align: center;
  }
  .sig-company {
    font-weight: bold;
    font-size: 10pt;
    margin-bottom: 4px;
  }
  .sig-name {
    font-weight: bold;
    font-size: 10pt;
    text-decoration: underline;
    margin-top: 50px; /* Jarak untuk ttd/stempel */
  }
  .sig-title {
    font-size: 10pt;
  }

  /* Footer menggunakan Tabel menggantikan Grid & Flex */
  footer {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: transparent;
    color: #000;
    padding: 4px 30px 20px 100px;
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
    padding-bottom: 10px;
  }
  .footer-icon {
    float: left;
    width: 35px;
    height: 35px;
    text-align: center;
    border: 1.5px solid #c0392b;
    border-radius: 50%;
    background: #fff;
  }
  .footer-icon img {
    width: 18px;
    height: 18px;
    margin-top: 8px;
  }
  .footer-text-block {
    margin-left: 45px;
  }
  .footer-label {
    color: #000;
    font-size: 10pt;
    margin-bottom: 2px;
  }
  .footer-value {
    color: #c0392b;
    line-height: 1.4;
  }

  @media print {
    body { background: none; padding: 0; margin: 0; }
    .page { box-shadow: none; margin: 0; width: 100%; height: auto; }
  }
</style>
</head>
<body>

  <!-- Corner decorations FIXED diseluruh halaman -->
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
        <!-- Kolom Pertama -->
        <td style="padding-right: 10px;">
          <div>
            <div class="footer-icon"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0iI2MwMzkyYiI+PHBhdGggZD0iTTEyIDJDOC4xNCAyIDUgNS4xNCA1IDljMCA1LjI1IDcgMTMgNyAxM3M3LTcuNzUgNy0xM2MwLTMuODYtMy4xNC03LTctN3ptMCA5LjVjLTEuMzggMC0yLjUtMS4xMi0yLjUtMi41czEuMTItMi41IDIuNS0yLjUgMi41IDEuMTIgMi41IDIuNS0xLjEyIDIuNS0yLjUgMi41eiIvPjwvc3ZnPg==" alt=""></div>
            <div class="footer-text-block">
              <div class="footer-label">Address Head Office</div>
              <div class="footer-value">Magersari Permai Blok AW-23 RT. 024 RW. 007,<br>Sidoarjo, Jawa Timur</div>
            </div>
          </div>
        </td>
        <!-- Kolom Kedua -->
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
        <!-- Kolom Ketiga -->
        <td style="padding-top: 15px; padding-right: 10px;">
          <div>
            <div class="footer-icon"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0iI2MwMzkyYiI+PHBhdGggZD0iTTEyIDJDOC4xNCAyIDUgNS4xNCA1IDljMCA1LjI1IDcgMTMgNyAxM3M3LTcuNzUgNy0xM2MwLTMuODYtMy4xNC03LTctN3ptMCA5LjVjLTEuMzggMC0yLjUtMS4xMi0yLjUtMi41czEuMTItMi41IDIuNS0yLjUgMi41IDEuMTIgMi41IDIuNS0xLjEyIDIuNS0yLjUgMi41eiIvPjwvc3ZnPg==" alt=""></div>
            <div class="footer-text-block">
              <div class="footer-label">Address Branch Office</div>
              <div class="footer-value">Jl. H. Abu No.57 3, RT.3/RW.7, Cipete Sel,<br>Kec. Cilandak, DKI Jakarta</div>
            </div>
          </div>
        </td>
        <!-- Kolom Keempat -->
        <td style="padding-top: 15px;">
          <div>
            <div class="footer-icon"><img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0iI2MwMzkyYiI+PHBhdGggZD0iTTYuNjIgMTAuNzljMS40NCAyLjgzIDMuNzYgNS4xNCA2LjU5IDYuNTlsMi4yLTIuMmMuMjctLjI3LjY3LS4zNiAxLjAyLS4yNCAxLjEyLjM3IDIuMzMuNTcgMy41Ny41Ny41NSAwIDEgLjQ1IDEgMVYyMGMwIC41NS0uNDUgMS0xIDEtOS4zOSAwLTE3LTcuNjEtMTctMTcgMC0uNTUuNDUtMSAxLTFoMy41Yy41NSAwIDEgLjQ1IDEgMSAwIDEuMjUuMiAyLjQ1LjU3IDMuNTcuMTEuMzUuMDMuNzQtLjI1IDEuMDJsLTIuMiAyLjJWMTAuNzl6Ii8+PC9zdmc+==" alt=""></div>
            <div class="footer-text-block">
              <div class="footer-label">Phone</div>
              <div class="footer-value">0851-5523-2320</div>
            </div>
          </div>
        </td>
      </tr>
    </table>
  </footer>

  <!-- Content WAJIB diletakkan di bawah element fixed -->
  <main class="content">

    <div class="surat-title">SURAT PENAWARAN</div>

    <table class="meta-table">
      <tr>
        <td class="label">Nomor</td>
        <td class="colon">:</td>
        <td>428/SPw/KTN/I/2026</td>
        <td class="meta-row-right">Sidoarjo, 06 Februari 2026</td>
      </tr>
      <tr>
        <td class="label">Lampiran</td>
        <td class="colon">:</td>
        <td colspan="2">1 (satu) berkas</td>
      </tr>
    </table>

    <div class="recipient">
      <div>Kepada Yth,</div>
      <div class="name-line">Kepala Dinas Badan Penanggulangan Bencana Daerah</div>
      <div>Jl. Sultan Agung No. 19 Gadjah Timur Magersari Sidoarjo</div>
      <div class="underline">K A B U P A T E N &nbsp; S I D O A R J O</div>
    </div>

    <!-- Perihal -->
    <table class="perihal-table">
      <tr>
        <td class="perihal-label">Perihal &nbsp;: &nbsp;</td>
        <td class="perihal-text">Penawaran Barang Untuk Badan Penanggulangan Bencana Daerah</td>
      </tr>
    </table>

    <p class="paragraph">
      Melalui Surat ini, kami dari PT. Kamil Tria Niaga sebagai perusahaan yang bergerak dalam bidang penyedia
      barang dan jasa di ruang lingkup pemerintah ingin memberikan penawaran kepada Kepala Badan
      Penanggulangan Bencana Daerah Kabupaten Sidoarjo.
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
        <tr>
          <td>Chainsaw Stihl 18 inch</td>
          <td class="center">3 Unit</td>
          <td class="right">Rp 10.300.000</td>
          <td class="right">Rp 30.900.000</td>
          <td><a href="https://katalog.inaproc.id/kamil-tria-senso-stihl-18-inch" target="_blank">Link</a></td>
        </tr>
        <tr>
          <td>Chainsaw Stihl 20 inch</td>
          <td class="center">2 Unit</td>
          <td class="right">Rp 10.300.000</td>
          <td class="right">Rp 20.600.000</td>
          <td><a href="https://katalog.inaproc.id/kamil-tria-senso-stihl-20-inch" target="_blank">Link</a></td>
        </tr>
      </tbody>
    </table>

    <p style="font-size:10pt; font-family:'Times New Roman',Times,serif; line-height:1.7; text-align:justify; margin-bottom:8px;">
      Harga tertera masih dapat didiskusikan sesuai keperluan dan sudah termasuk dengan layanan antar. Jika
      penawaran kami sesuai dengan kebutuhan, maka berlaku ketentuan sebagai berikut:
    </p>

    <ul class="bullet-list">
      <li>Kami akan melaksanakan pekerjaan tersebut dengan jangka waktu pelaksanaan pekerjaan selama kurang dari 14 (Empat Belas) Hari Kalender.</li>
      <li>Penawaran ini berlaku selama 14 (Empat Belas) Hari kalender sejak tanggal 06 Februari 2026 sampai dengan 20 Februari 2026, dan harga bisa berubah sewaktu-waktu.</li>
    </ul>

    <p class="paragraph">
      Penawaran ini sudah memperhatikan ketentuan dan persyaratan yang berlaku untuk melaksanakan pekerjaan
      tersebut di atas. Dengan disampaikannya surat penawaran ini, maka kami menyatakan sanggup melaksanakan
      semua ketentuan yang tercantum dalam berkas.
    </p>

    <!-- Signature menggunakan Table -->
    <table class="signature-table">
      <tr>
        <td style="width: 60%;"></td> <!-- Kosong buat geser ke kanan -->
        <td style="width: 40%;" class="sig-block">
          <div class="sig-company">PT. KAMIL TRIA NIAGA</div>
          <br><br>
          <div class="sig-name">MAHENDA ABDILLAH KAMIL, S.Stat</div>
          <div class="sig-title">Direktur</div>
        </td>
      </tr>
    </table>

  </main><!-- end content -->

</body>
</html>