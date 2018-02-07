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
        $string = str_replace('\"', '\'', $string);
        preg_match_all('/(?=\S)[^"\s]*((?<![\\\\])["])((?:.(?!(?<![\\\\])\1))*.?)\1|(?=\S)[^"\s]*/', $string, $parsingResults);

        if (isset($parsingResults[0]) && !empty($parsingResults[0])) {
            foreach ($parsingResults[0] as $item) {
                $itemArray = explode(':', $item);
                if (isset($itemArray[0]) && isset($itemArray[1])) {
                    $resultsArray[$itemArray[0]] = str_replace('\'', '"', preg_replace('/^"|"$/', '', $itemArray[1]));
                }
            }
        }

        return $resultsArray;
    }
}