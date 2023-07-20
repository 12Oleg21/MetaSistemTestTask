<?php

namespace app\commands;

use yii\console\Controller;

/**
 * This command is the entry point for the function which check-in brackets
 * to run it execute the command in console YII2 project:
 *  php ./yii testing 'your string with brackets like ({[]})'
 */
class TestingController extends Controller
{
    protected $brackets = ['(' => ')', '{' => '}', '[' => ']'];

    /**
     * This is the main function to check if brackets in a string are correctly closed.
     *
     * @param string $text
     * @return bool
     */
    public function actionIndex($text)
    {
        /* Remove from string all gaps and all other symbols except for brackets */
        $onlyBracketsArray = $this->cleanText($text);

        /* If string $text doesn't have any brackets, then we should return true */
        if (empty($onlyBracketsArray)) {
            var_dump("Return true");
            return true;
        }
        /* Check if brackets are correctly closed */
        $check = $this->parsing($onlyBracketsArray);
        var_dump($check);
        return $check;
    }

    /**
     * Clean up all symbols and gaps except for brackets and
     * create array of brackets
     *
     * @param string $text
     * @return array|false
     */
    private function cleanText($text)
    {
        /* Remove from string all gaps and all other symbols except for brackets */
        $onlyBrackets = preg_replace("/[^\( \) \{ \} \[ \]]| /", null, $text);

        return $onlyBrackets ? str_split($onlyBrackets) : [];
    }

    /**
     * Check if brackets are correctly closed
     *
     * @param array $onlyBracketsArray
     * @return bool
     */
    private function parsing(array $onlyBracketsArray)
    {
        $tmpOpenedBrackets = [];
        foreach ($onlyBracketsArray as $bracket) {
            if (array_key_exists($bracket, $this->brackets)) {
                /* This is an opened bracket. Write the bracket to a temporary array */
                $tmpOpenedBrackets[] = $bracket;
            } else {
                /* This is a closed bracket. Compare the bracket with the last element of the temporary array.
                The last element is used as a key to compare correctly */
                $lastBracket = array_pop($tmpOpenedBrackets);
                if (!$lastBracket or $this->brackets[$lastBracket] !== $bracket) {
                    return false;
                }
            }
        }

        return true;
    }
}
