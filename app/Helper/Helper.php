<?php
if (!function_exists('set_active')) {
    function set_active($url, $output = 'active')
    {
        if (is_array($url)) {
            foreach ($url as $u) {
                if (Route::is($u)) {
                    return $output;
                }
            }
        } else {
            if (Route::is($url)) {
                return $output;
            }
        }
    }
}

if (!function_exists('format_idr')) {
    function format_idr($val)
    {
        return number_format($val, 0, ',', '.');
    }
}

if (!function_exists('bulan_indo')) {
    function bulan_indo($bln)
    {
        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        return $bulan[$bln];
    }
}
if (!function_exists('bulan_interval')) {
    function bulan_interval($awal, $akhir)
    {
        $start = new DateTime($awal);
        $end = new DateTime($akhir);
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);
        $months = [];
        foreach ($period as $dt) {
            // $months[] = bulan_indo($dt->format('n'));
            $months[] = $dt->format('n');
        }
        return $months;
    }
}
