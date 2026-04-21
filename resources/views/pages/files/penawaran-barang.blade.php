<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Penawaran Barang - PT. Kamil Tria Niaga</title>
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

  /* ── Corner decorations ─────────────────────────────── */
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

  /* ── Header (fixed, same as surat-penawaran) ─────────── */
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

  /* ── Content wrapper ─────────────────────────────────── */
  .content {
    padding: 0px 40px 0px 40px;
    position: relative;
    z-index: 0;
  }

  /* ── Page title ──────────────────────────────────────── */
  .page-title {
    text-align: center;
    font-size: 11pt;
    font-weight: bold;
    margin-bottom: 4px;
    letter-spacing: 1px;
    font-family: Arial, sans-serif;
  }
  .page-subtitle {
    text-align: center;
    font-size: 10pt;
    margin-bottom: 12px;
    font-family: 'Times New Roman', Times, serif;
  }

  /* ── Main barang table ───────────────────────────────── */
  .barang-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 9.5pt;
    table-layout: fixed;
    page-break-inside: auto;
  }
  .barang-table thead {
    display: table-header-group;
  }
  .barang-table tbody {
    display: table-row-group;
  }
  .barang-table tr {
    page-break-inside: avoid;
    break-inside: avoid;
  }

  /* Header row */
  .barang-table th {
    background: #BDBDBD;
    color: #111;
    padding: 6px 5px;
    text-align: center;
    border: 1.5px solid #444;
    font-weight: bold;
    font-family: Arial, sans-serif;
    font-size: 9pt;
    vertical-align: middle;
  }

  /* Data cells */
  .barang-table td {
    padding: 5px 6px;
    border: 1px solid #999;
    vertical-align: middle;
    font-family: 'Times New Roman', Times, serif;
    font-size: 9pt;
  }
  .barang-table td.center { text-align: center; }
  .barang-table td.right  { text-align: right; }

  /* Alternating row color */
  .barang-table tbody tr:nth-child(even) td {
    background: #f9f9f9;
  }

  /* No column */
  .col-no       { width: 4%; }
  /* Nama Barang */
  .col-nama     { width: 14%; }
  /* Spesifikasi */
  .col-spek     { width: 32%; }
  /* TKDN */
  .col-tkdn     { width: 7%; }
  /* Gambar */
  .col-gambar   { width: 20%; }
  /* Unit */
  .col-unit     { width: 8%; }
  /* Harga Satuan */
  .col-harga    { width: 15%; }

  /* Spesifikasi cell — preserve line formatting */
  .spek-text {
    white-space: pre-wrap;
    word-break: break-word;
    font-size: 8.5pt;
    line-height: 1.5;
  }

  /* Product image inside table */
  .product-img {
    max-width: 110px;
    max-height: 110px;
    display: block;
    margin: 0 auto;
    object-fit: contain;
  }
  .no-img {
    color: #aaa;
    font-style: italic;
    font-size: 8pt;
    text-align: center;
  }

  /* TKDN badge */
  .tkdn-badge {
    display: inline-block;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 8pt;
    font-weight: bold;
    font-family: Arial, sans-serif;
  }
  .tkdn-pdn  { background: white; }
  .tkdn-impor { background: white; }
  .tkdn-other { background: white; }

  /* Footer (same as surat-penawaran) */
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
    .content { padding: 20px 60px; }
  }
</style>
</head>
<body>

  {{-- ── Corner decorations ─────────────────────────────── --}}
  <div class="corner-tl">
    <img class="corner-img"
         src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/kanan-atas.png'))) }}"
         alt="">
  </div>
  <div class="corner-br">
    <img class="corner-img"
         src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/kiri-bawah.png'))) }}"
         alt="">
  </div>

  {{-- ── Fixed Header ────────────────────────────────────── --}}
  <header>
    <table class="header-table">
      <tr>
        <td style="width:80px;">
          <img class="logo-img"
               src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}"
               alt="Logo">
        </td>
        <td>
          <div class="company-name">KAMIL TRIA<br>NIAGA</div>
        </td>
      </tr>
    </table>
  </header>

  {{-- ── Fixed Footer ────────────────────────────────────── --}}
  <footer>
    <table class="footer-table">
      <tr>
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

  {{-- ── Main Content ─────────────────────────────────────── --}}
  <main class="content">

    <div class="page-title">PENAWARAN BARANG</div>
    <div class="page-subtitle">{{ $surat['kepada'] ?? ($proyek->nama_klien ?? $proyek->nama_proyek ?? '-') }}</div>

    <table class="barang-table">
      <thead>
        <tr>
          <th class="col-no">No</th>
          <th class="col-nama">Nama Barang</th>
          <th class="col-spek">Spesifikasi</th>
          <th class="col-tkdn">TKDN</th>
          <th class="col-gambar">Gambar</th>
          <th class="col-unit">Unit</th>
          <th class="col-harga">Harga Satuan</th>
        </tr>
      </thead>
      <tbody>

        @php
          /**
           * $itemsDetail = collection/array of objects / arrays, each containing:
           *   - nama_barang    : string
           *   - spesifikasi    : string  (from barang.spesifikasi)
           *   - pdn_tkdn_impor : string  (from barang.pdn_tkdn_impor — e.g. 'PDN', 'TKDN', 'Impor')
           *   - foto_barang    : string  (path stored in barang.foto_barang)
           *   - qty            : numeric
           *   - satuan         : string
           *   - harga_satuan   : numeric
           *   - subtotal       : numeric
           */
          $rows = $itemsDetail ?? $items ?? [];
        @endphp

        @forelse($rows as $i => $row)
          @php
            // ── Normalize: support both array and object ──────────────
            $get = fn($key, $fallback = '-') =>
                is_array($row) ? ($row[$key] ?? $fallback) : ($row->{$key} ?? $fallback);

            $namaBarang  = $get('nama_barang');
            $spesifikasi = $get('spesifikasi', '');
            $tkdn        = strtoupper(trim($get('pdn_tkdn_impor', '')));
            $fotoBarang  = $get('foto_barang', '');
            $qty         = $get('qty', 0);
            $satuan      = $get('satuan', 'Unit');
            $hargaSatuan = $get('harga_satuan', 0);

            // ── TKDN badge class ──────────────────────────────────────
            $tkdnClass = match(true) {
                str_contains($tkdn, 'PDN')  => 'tkdn-pdn',
                str_contains($tkdn, 'IMPOR') => 'tkdn-impor',
                str_contains($tkdn, 'TKDN') => 'tkdn-pdn',
                default => 'tkdn-other',
            };

            // ── Foto barang absolute path ─────────────────────────────
            // foto_barang may be stored as:
            //   a) relative path in storage: "barang/foto/xxx.jpg"
            //   b) full public path
            $fotoPath = null;
            if (!empty($fotoBarang) && $fotoBarang !== '-') {
                // Try storage disk public first
                $candidate = storage_path('app/public/' . ltrim($fotoBarang, '/\\'));
                if (file_exists($candidate)) {
                    $fotoPath = $candidate;
                } else {
                    // Try public_path
                    $candidate2 = public_path(ltrim($fotoBarang, '/\\'));
                    if (file_exists($candidate2)) {
                        $fotoPath = $candidate2;
                    }
                }
            }

            // ── Detect image MIME type for base64 embedding ───────────
            $fotoBase64 = null;
            $fotoMime   = 'image/jpeg';
            if ($fotoPath) {
                try {
                    $ext = strtolower(pathinfo($fotoPath, PATHINFO_EXTENSION));
                    $fotoMime = match($ext) {
                        'png'  => 'image/png',
                        'gif'  => 'image/gif',
                        'webp' => 'image/webp',
                        default => 'image/jpeg',
                    };
                    $raw = file_get_contents($fotoPath);
                    if ($raw) {
                        $fotoBase64 = base64_encode($raw);
                    }
                } catch (\Throwable $e) {
                    $fotoBase64 = null;
                }
            }
          @endphp

          <tr>
            {{-- No --}}
            <td class="center">{{ $loop->iteration }}</td>

            {{-- Nama Barang --}}
            <td>{{ $namaBarang }}</td>

            {{-- Spesifikasi --}}
            <td>
              @if(!empty($spesifikasi) && $spesifikasi !== '-')
                <div class="spek-text">{{ $spesifikasi }}</div>
              @else
                <span style="color:#aaa;font-style:italic;">-</span>
              @endif
            </td>

            {{-- TKDN --}}
            <td class="center">
              @if(!empty($tkdn) && $tkdn !== '-')
                <span class="tkdn-badge {{ $tkdnClass }}">{{ $tkdn }}</span>
              @else
                <span style="color:#aaa;">-</span>
              @endif
            </td>

            {{-- Gambar --}}
            <td class="center">
              @if($fotoBase64)
                <img class="product-img"
                     src="data:{{ $fotoMime }};base64,{{ $fotoBase64 }}"
                     alt="{{ $namaBarang }}">
              @else
                <div class="no-img">Tidak ada<br>gambar</div>
              @endif
            </td>

            {{-- Unit --}}
            <td class="center">{{ $qty }} {{ $satuan }}</td>

            {{-- Harga Satuan --}}
            <td class="right">
              @if(is_numeric($hargaSatuan))
                Rp {{ number_format($hargaSatuan, 0, ',', '.') }}
              @else
                {{ $hargaSatuan }}
              @endif
            </td>
          </tr>

        @empty
          <tr>
            <td colspan="7" class="center" style="padding:20px;color:#aaa;font-style:italic;">
              Tidak ada data barang.
            </td>
          </tr>
        @endforelse

      </tbody>
    </table>

  </main>

</body>
</html>