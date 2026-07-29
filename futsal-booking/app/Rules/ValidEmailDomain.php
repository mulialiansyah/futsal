<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmailDomain implements ValidationRule
{
    /**
     * Daftar domain email yang umum diketahui typo-nya.
     * key = typo, value = saran yang benar
     */
    protected static array $commonTypos = [
        // Gmail typos
        'gmai.com' => 'gmail.com',
        'gmial.com' => 'gmail.com',
        'gmal.com' => 'gmail.com',
        'gamil.com' => 'gmail.com',
        'gnail.com' => 'gmail.com',
        'gmil.com' => 'gmail.com',
        'gmaill.com' => 'gmail.com',
        'gmail.co' => 'gmail.com',
        'gmail.cm' => 'gmail.com',
        'gmail.om' => 'gmail.com',
        'gmail.con' => 'gmail.com',
        'gail.com' => 'gmail.com',
        'gemail.com' => 'gmail.com',
        'gimail.com' => 'gmail.com',
        'gmali.com' => 'gmail.com',
        'gmaul.com' => 'gmail.com',
        'gmeil.com' => 'gmail.com',

        // Yahoo typos
        'yaho.com' => 'yahoo.com',
        'yahooo.com' => 'yahoo.com',
        'yaho.co.id' => 'yahoo.co.id',
        'yahoo.co.i' => 'yahoo.co.id',
        'yhoo.com' => 'yahoo.com',
        'yaoo.com' => 'yahoo.com',
        'yhaoo.com' => 'yahoo.com',
        'yahooo.co.id' => 'yahoo.co.id',
        'yaho.co.id' => 'yahoo.co.id',

        // Hotmail typos
        'hotmal.com' => 'hotmail.com',
        'hotmai.com' => 'hotmail.com',
        'hotmial.com' => 'hotmail.com',
        'hotmil.com' => 'hotmail.com',
        'hotmali.com' => 'hotmail.com',
        'hotmaill.com' => 'hotmail.com',

        // Outlook typos
        'outlok.com' => 'outlook.com',
        'outook.com' => 'outlook.com',
        'outlool.com' => 'outlook.com',
        'outllok.com' => 'outlook.com',
        'outloook.com' => 'outlook.com',
        'outlokk.com' => 'outlook.com',

        // iCloud typos
        'iclod.com' => 'icloud.com',
        'iclould.com' => 'icloud.com',
        'icoud.com' => 'icloud.com',
    ];

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value) || !str_contains($value, '@')) {
            return; // Biarkan validasi lain menangani
        }

        $parts = explode('@', $value);
        $domain = strtolower(end($parts));

        // Cek apakah domain ada di daftar typo
        if (isset(static::$commonTypos[$domain])) {
            $suggested = static::$commonTypos[$domain];
            $fail("Domain email \"$domain\" sepertinya typo. Mungkin yang Anda maksud adalah \"$suggested\"? Masukkan kembali email Anda.");
            return;
        }

        // Cek DNS MX record hanya jika bukan di environment testing
        if (app()->environment('production', 'staging')) {
            if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
                $fail("Domain email \"$domain\" tidak ditemukan. Periksa kembali email Anda.");
            }
        }
    }
}
