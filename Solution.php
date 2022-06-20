<?php

final class Solution
{

    public function __invoke(int $n, array $a, array $b): bool
    {
        $pathsBetweenNodes = [];

        if( $n < 0){
            print('false');
            return false;
        }

        foreach (range($a, $b) as $node => $index) {

            var_dump($node, $index);

            return true;
        }

        print('false');
        return false;
    }

}

(new Solution())(4, [1, 2, 4, 3], [2, 3, 3, 1]);
