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
    background: #e0e0e0;
    display: flex;
    justify-content: center;
    padding: 10px;
  }

  .page {
    width: 210mm;
    min-height: 297mm;
    background: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
  }

  /* Corner Decorations (image-based for PDF) */
  .corner-tl, .corner-br {
    position: absolute;
    width: 80px;
    height: 80px;
    z-index: 0;
    background: none;
  }

  /* Top-right corner decoration */
  .corner-tl {
    top: 0;
    right: 0;
    left: auto;
    width: 140px;
    height: 100px;
  }

  /* Bottom-left corner decoration */
  .corner-br {
    bottom: 0;
    left: 0;
    right: auto;
    width: 140px;
    height: 140px;
  }

  .corner-img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
  }

  /* Header */
  .header {
    display: flex;
    align-items: center;
    padding: 18px 0px 18px 0px;
    border-bottom: 5px solid #C62828;
    position: relative;
    z-index: 1;
  }

  .logo-box {
    display: flex;
    align-items: center;
    gap: 0;
  }

  .logo-icon {
    width: 80px;
    height: 80px;
    position: relative;
    margin-right: 10px;
    margin-left: 50px;

  }

  /* Use actual logo from assets */
  .logo-img {
    width: 80px;
    height: 80px;
    display: block;
    object-fit: contain;
  }

  .company-name {
    font-family: Arial, sans-serif;
    font-size: 22px;
    font-weight: 800;
    color: #222;
    line-height: 1.1;
    letter-spacing: 1px;
  }
  .company-name span {
    display: block;
  }

  /* Body content */
  .content {
    padding: 20px 80px 70px 80px;
    position: relative;
    z-index: 1;
  }

  .surat-title {
    text-align: center;
    font-size: 10pt;
    font-weight: bold;
    text-decoration: underline;
    margin-bottom: 20px;
    letter-spacing: 1px;
  }

  .meta-table {
    width: 100%;
    margin-bottom: 18px;
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
    margin-bottom: 14px;
    font-size: 10pt;
    line-height: 1.7;
  }
  .recipient .name-line {
    font-weight: normal;
  }
  .recipient .underline {
    text-decoration: underline;
    letter-spacing: 2px;
    font-weight: bold;
    margin-top: 2px;
  }

  .perihal {
    margin-bottom: 14px;
    font-size: 10pt;
    display: flex;
    gap: 4px;
  }
  .perihal .label { white-space: nowrap; }
  .perihal .text { font-weight: bold; }

  .paragraph {
    font-size: 10pt;
    line-height: 1.7;
    text-align: justify;
    margin-bottom: 12px;
    text-indent: 40px;
  }

  /* Product Table */
  .product-table {
    width: 100%;
    border-collapse: collapse;
    margin: 14px 0;
    font-size: 10pt;
  }
  .product-table th {
    background: #BDBDBD;
    color: #111;
    padding: 10px 5px;
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
    line-height: 1.7;
    margin: 6px 0 10px 20px;
    text-align: justify;
  }
  .bullet-list li {
    margin-bottom: 4px;
    list-style: none;
    padding-left: 12px;
    position: relative;
  }
  .bullet-list li::before {
    content: "-";
    position: absolute;
    left: 0;
  }

  /* Signature */
  .signature-block {
    margin-top: 18px;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    padding-right: 40px;
  }
  .sig-company {
    font-weight: bold;
    font-size: 10pt;
    margin-bottom: 4px;
    text-align: center;
  }
  .sig-stamp-area {
    width: 140px;
    height: 80px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
  }
  .sig-stamp {
    width: 100px;
    height: 100px;
    border: 3px solid #c0392b;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 7pt;
    font-weight: bold;
    text-align: center;
    color: #c0392b;
    position: relative;
    opacity: 0.7;
  }
  .sig-name {
    font-weight: bold;
    font-size: 10pt;
    text-align: center;
    text-decoration: underline;
  }
  .sig-title {
    font-size: 10pt;
    text-align: center;
  }

  /* Footer */
  .footer {
    background: #111;
    color: #fff;
    padding: 14px 30px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px 30px;
    margin-top: 10px;
    font-size: 9pt;
  }
  .footer-item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
  }
  .footer-icon {
    color: #c0392b;
    font-size: 14px;
    margin-top: 1px;
    flex-shrink: 0;
  }
  .footer-label {
    color: #aaa;
    font-size: 8pt;
    margin-bottom: 2px;
  }
  .footer-value {
    color: #c0392b;
    line-height: 1.5;
  }

  @media print {
    body { background: none; padding: 0; }
    .page { box-shadow: none; }
  }
</style>
</head>
<body>
<div class="page">

  <!-- Corner decorations (images) -->
  <div class="corner-tl">
    <img class="corner-img" src="images/corner-top-right.png" alt="">
  </div>
  <div class="corner-br">
    <img class="corner-img" src="images/corner-bottom-left.png" alt="">
  </div>

  <!-- Header -->
  <div class="header">
    <div class="logo-box">
      <div class="logo-icon">
        <img class="logo-img" src="{{ asset('images/logo.png') }}" alt="Logo PT. Kamil Tria Niaga">
      </div>
      <div class="company-name">
        <span>KAMIL TRIA</span>
        <span>NIAGA</span>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="content">

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

    <div class="perihal">
      <span class="label">Perihal &nbsp;: &nbsp;</span>
      <span class="text">Penawaran Barang Untuk Badan Penanggulangan Bencana Daerah</span>
    </div>

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
          <td><a href="https://katalog.inaproc.id/kamil-tria-senso-stihl-18-inch" target="_blank">https://katalog.inaproc.id/kamil-tria-senso-stihl-18-inch</a></td>
        </tr>
        <tr>
          <td>Chainsaw Stihl 20 inch</td>
          <td class="center">2 Unit</td>
          <td class="right">Rp 10.300.000</td>
          <td class="right">Rp 20.600.000</td>
          <td><a href="https://katalog.inaproc.id/kamil-tria-senso-stihl-20-inch" target="_blank">https://katalog.inaproc.id/kamil-tria-senso-stihl-20-inch</a></td>
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

    <!-- Signature -->
    <div class="signature-block">
      <div style="text-align:center;">
        <div class="sig-company">PT. KAMIL TRIA NIAGA</div>
        <div class="sig-stamp-area">
          
        </div>
        <div class="sig-name">MAHENDA ABDILLAH KAMIL, S.Stat</div>
        <div class="sig-title">Direktur</div>
      </div>
    </div>

  </div><!-- end content -->

  <!-- Footer -->
  <div class="footer">
    <div class="footer-item">
      <span class="footer-icon">📍</span>
      <div>
        <div class="footer-label">Address Head Office</div>
        <div class="footer-value">Magersari Permai Blok AW-23 RT. 024 RW. 007,<br>Sidoarjo, Jawa Timur</div>
      </div>
    </div>
    <div class="footer-item">
      <span class="footer-icon">✉</span>
      <div>
        <div class="footer-label">Email</div>
        <div class="footer-value">kamiltrianiaga@gmail.com</div>
      </div>
    </div>
    <div class="footer-item">
      <span class="footer-icon">📍</span>
      <div>
        <div class="footer-label">Address Branch Office</div>
        <div class="footer-value">Jl. H. Abu No.57 3, RT.3/RW.7, Cipete Sel, Kec. Cilandak,<br>Kota Jakarta Selatan, DKI Jakarta</div>
      </div>
    </div>
    <div class="footer-item">
      <span class="footer-icon">📞</span>
      <div>
        <div class="footer-label">Phone</div>
        <div class="footer-value">0851-5523-2320</div>
      </div>
    </div>
  </div>

</div>
</body>
</html>