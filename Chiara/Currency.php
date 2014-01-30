<?php
namespace Chiara;
class Currency
{

    function parse($value, $allowed_currencies)
    {
        $symbols = 'Lek|؋|ƒ|ман|p\.|BZ\$|\$b|KM|P|лв|R\$|៛|¥|₡|kn|₱|Kč|kr|RD\$|€|¢|Q|L|Ft|HK\$|Rp|﷼|₪|J\$|₩|₭|Ls|Lt|ден|RM|₨|₮|MT|C\$|₦|B\/\.|Gs|S\/\.|zł|lei|руб|Дин\.|CHF|NT\$|฿|TT\$|₤|₴|\$U|Bs|₫|Z\$|\$';
        $currency = 'USD';
        if (preg_match('/(' . $symbols . ')/', $value, $match)) {
            if (isset($match[1])) {
                $currency = self::$currency_map[$match[1]];
                if (!in_array($currency, $allowed_currencies)) {
                    throw new \Exception($currency . ' is not allowed');
                }
            }
        }
        if (preg_match('/' . implode('|', $allowed_currencies) . '/', $value, $match)) {
            if (isset($match[1])) {
                $currency = $match[1];
            }
        }
        return array($currency, $this->extractNumber($value));
    }

    function extractNumber($value)
    {
        $cents = 0;
        if (preg_match('/([ ,\'\.0-9]+)[\.-\/,](\d\d)$/', $value, $matches)) {
            $cents = $matches[2];
            $dollars = preg_replace('/[^0-9]/', '', $matches[1]);
        } else {
            $dollars = preg_replace('/[^0-9]/', '', $value[1]);
        }
        return $dollars + ($cents/100);
    }

    static protected $currency_map = array(
        '$'    => 'USD',
        'Lek'  => 'ALL',
        '؋'       => 'AFN',
        'ƒ'    => 'AWG',
        'ман'  => 'AZN',
        'p.'   => 'BYR',
        'BZ$'  => 'BZD',
        '$b'   => 'BOB',
        'KM'   => 'BAM',
        'P'    => 'BWP',
        'лв'   => 'BGN',
        'R$'   => 'BRL',
        '៛'    => 'KHR',
        '¥'    => 'CNY',
        '₡'    => 'CRC',
        'kn'   => 'HRK',
        '₱'    => 'CBP',
        'Kč'   => 'CZK',
        'kr'   => 'DKK',
        'RD$'  => 'DOP',
        'kr'   => 'EEK',
        '€'    => 'EUR',
        '¢'    => 'GHC',
        'Q'    => 'GTQ',
        'L'    => 'HNL',
        'Ft'   => 'HUF',
        'HK$'  => 'HKD',
        'Rp'   => 'IDR',
        '﷼'       => 'IRR',
        '₪'    => 'ILS',
        'J$'   => 'JMD',
        'лв'   => 'KZT',
        '₩'    => 'KRW',
        '₭'    => 'LAK',
        'Ls'   => 'LVL',
        'Lt'   => 'LTL',
        'ден'  => 'MKD',
        'RM'   => 'MYR',
        '₨'    => 'MUR',
        '₮'    => 'MNT',
        'MT'   => 'MZN',
        'C$'   => 'NIO',
        '₦'    => 'NGN',
        'B/.'  => 'PAB',
        'Gs'   => 'PYG',
        'S/.'  => 'PEN',
        'zł'   => 'PLN',
        'lei'  => 'RON',
        'руб'  => 'RUB',
        'Дин.' => 'RSD',
        'CHF'  => 'CHF',
        'NT$'  => 'TWD',
        '฿'    => 'THB',
        'TT$'  => 'TTD',
        '₤'    => 'TRL',
        '₴'    => 'UAH',
        '$U'   => 'UYU',
        'Bs'   => 'VEF',
        '₫'    => 'VND',
        'Z$'   => 'ZWD',
    );
}