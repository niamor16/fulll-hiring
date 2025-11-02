<?php

class FizzBuzz
{
    private array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function display(int $number): void
    {
        for ($i = 1; $i <= $number; $i++) {
            $display = '';
            foreach ($this->rules as $divisor => $word) {
                if ($i % $divisor === 0) {
                    $display .= $word;
                }
            }
            echo ($display ?: $i) . PHP_EOL;
        }
    }
}

new FizzBuzz([3 => 'Fizz', 5 => 'Buzz'])->display(15);