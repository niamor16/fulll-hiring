<?php

function fizzBuzz($number)
{
    $rules = [3 => 'Fizz', 5 => 'Buzz'];
    for ($i = 1; $i <= $number; $i++) {
        $display = '';
        foreach ($rules as $divisor => $word) {
            if ($i % $divisor === 0) {
                $display .= $word;
            }
        }
        echo ($display ?: $i) . PHP_EOL;
    }
}

fizzBuzz(15);