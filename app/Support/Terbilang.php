<?php

namespace App\Support;

class Terbilang
{
    private static array $angka = [
        '', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'
    ];

    public static function rupiah($nilai): string
    {
        $n = (int) floor((float) $nilai);
        if ($n === 0) {
            return 'Nol Rupiah';
        }

        $hasil = trim(self::convert($n));
        return ($hasil === '' ? 'Nol' : $hasil) . ' Rupiah';
    }

    private static function convert(int $n): string
    {
        if ($n < 12) {
            return self::$angka[$n];
        }
        if ($n < 20) {
            return self::$angka[$n - 10] . ' Belas';
        }
        if ($n < 100) {
            $satuan = $n % 10;
            return self::$angka[intdiv($n, 10)] . ' Puluh' . ($satuan !== 0 ? ' ' . self::$angka[$satuan] : '');
        }
        if ($n < 200) {
            $sisa = $n - 100;
            return 'Seratus' . ($sisa > 0 ? ' ' . self::convert($sisa) : '');
        }
        if ($n < 1000) {
            $sisa = $n % 100;
            return self::$angka[intdiv($n, 100)] . ' Ratus' . ($sisa > 0 ? ' ' . self::convert($sisa) : '');
        }
        if ($n < 2000) {
            $sisa = $n - 1000;
            return 'Seribu' . ($sisa > 0 ? ' ' . self::convert($sisa) : '');
        }
        if ($n < 1_000_000) {
            $sisa = $n % 1000;
            return self::convert(intdiv($n, 1000)) . ' Ribu' . ($sisa > 0 ? ' ' . self::convert($sisa) : '');
        }
        if ($n < 1_000_000_000) {
            $sisa = $n % 1_000_000;
            return self::convert(intdiv($n, 1_000_000)) . ' Juta' . ($sisa > 0 ? ' ' . self::convert($sisa) : '');
        }
        if ($n < 1_000_000_000_000) {
            $sisa = $n % 1_000_000_000;
            return self::convert(intdiv($n, 1_000_000_000)) . ' Miliar' . ($sisa > 0 ? ' ' . self::convert($sisa) : '');
        }

        $sisa = $n % 1_000_000_000_000;
        return self::convert(intdiv($n, 1_000_000_000_000)) . ' Triliun' . ($sisa > 0 ? ' ' . self::convert($sisa) : '');
    }
}