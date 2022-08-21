<?php
    function solve($number) {
        $hyphen      = ' ';
        $conjunction = ' ';
        $separator   = ', ';
        $negative    = ' butun ';
        $decimal     = ' Haqiqiy ';
        $dictionary  = array(
            0                   => 'no\'l',
            1                   => 'bir',
            2                   => 'ikki',
            3                   => 'uch',
            4                   => 'to\'rt',
            5                   => 'besh',
            6                   => 'olti',
            7                   => 'Yetti',
            8                   => 'sakkiz',
            9                   => 'to\'qqiz',
            10                  => 'o\'n',
            11                  => 'o\'n bir',
            12                  => 'o\'n ikki',
            13                  => 'o\'n uch',
            14                  => '\'n to\'rt',
            15                  => 'o\'n besh',
            16                  => 'o\'n olti',
            17                  => 'o\'n etti',
            18                  => 'o\'n sakkiz',
            19                  => 'o\'n to\'qqiz',
            20                  => 'yigirma',
            30                  => 'o\'ttiz',
            40                  => 'qirq',
            50                  => 'ellik',
            60                  => 'oltmish',
            70                  => 'yetmish',
            80                  => 'sakson',
            90                  => 'to\'qson',
            100                 => 'yuz',
            1000                => 'ming',
            1000000             => 'million',
            1000000000          => 'milliard',
            1000000000000       => 'trillion',
            1000000000000000    => 'kvadrillion',
            1000000000000000000 => 'kvintilion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // qiymat ortib ketishi.
            trigger_error(
                ' Bu funksiya ishlash cheklovi - [' . PHP_INT_MAX . ' and ' . PHP_INT_MAX.']',
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . solve(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . solve($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = solve($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= solve($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
?>
