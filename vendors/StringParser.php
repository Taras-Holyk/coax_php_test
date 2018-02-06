<?php
class StringParser {
    /**
     * Parse string to array
     * @param $string
     * @return array
     */
    public function getParsedString($string)
    {
        $resultsArray = [];
        preg_match_all('/(?=\S)[^\'"\s]*(?:\'[^\']*\'[^\'"\s]*|"[^"]*"[^\s]*)*/', $string, $parsingResults);

        if (isset($parsingResults[0]) && !empty($parsingResults[0])) {
            foreach ($parsingResults[0] as $item) {
                $itemArray = explode(':', $item);
                if (isset($itemArray[0]) && isset($itemArray[1])) {
                    $resultsArray[$itemArray[0]] = stripcslashes(preg_replace('/^"|"$/', '', $itemArray[1]));
                }
            }
        }

        return $resultsArray;
    }
}