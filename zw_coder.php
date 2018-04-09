<?php
class zw_coder {
    //  Variables containg the zero-width characters.
    private $ZERO_WIDTH_JOINER = "‍";
    private $ZERO_WIDTH_NONJOINER = "‌";
    private $ZERO_WIDTH_SPACE = "​";
    private $ZERO_WIDTH_NOBREAK_SPACE = "⁠";

    //  Convert string to binary.
    protected function str2bin($text) {
        //  Array for holding the binary.
        $bin = array();

        //  Iterate over the input text.
        for ($i = 0; strlen($text) > $i; $i ++)
            //  Get ASCII value (decimal) of character, then convert it into binary.
            $bin[] = decbin(ord($text[$i]));

        //  Implode the array into a single string and return it.
        return implode(' ', $bin);
    }

    //  Convert binary to string.
    protected function bin2str($bin){
        //  Array for holding the text.
        $text = array();

        //  Explode the binary into a array by every space.
        $bin = explode(' ', $bin);

        //  Iterarte over the binary array.
        for ($i = 0; count($bin) > $i; $i ++)
            //  Convert the binary into decimal and then get the character for that decimal value.
            $text[] = chr(bindec($bin[$i]));

        //  Implode the text array and return it.
        return implode($text);
    }

    //  Encode a secret message, optionally inserting it into a message.
    function encode($secret, $message = null) {
        //  Convert the secret into binary.
        $secBin = $this->str2bin($secret);

        //  Variable for holding the final encoded secret.
        $secZW = "";

        //  Iterarte over the binary.
        for ($i = 0; $i < strlen($secBin); $i++) {
            if ($secBin[$i] == "1") {                       //  If the bit is a one, then add a ZERO_WIDTH_JOINER.
                $secZW .= $this->ZERO_WIDTH_JOINER;
            } elseif ($secBin[$i] == "0") {                 //  If the bit is a zero, then add a ZERO_WIDTH_NONJOINER.
                $secZW .= $this->ZERO_WIDTH_NONJOINER;
            } elseif ($secBin[$i] == " ") {                 //  If the bit is a space, then add a ZERO_WIDTH_SPACE.
                $secZW .= $this->ZERO_WIDTH_SPACE;
            }
        }

        //  Check if a message was specified.
        if (!empty($message)) {
            //  Get a random index betweeen 0 and the length of the message.
            $randIndex = rand(0, strlen($message));

            //  Insert the encoded secret at the index and return the new message.
            return substr($message, 0, $randIndex) . $secZW . substr($message, $randIndex);;
        } else {
            return $secZW;
        }
    }

    //  Decode a secret.
    function decode($secret) {
        //  Varaible for the bianry of the secret.
        $secBin = "";

        //  Get all matches for the four zero-width characters.
        preg_match_all("/(‍|‌|​|⁠)/", $secret, $matches);

        //  Iterarte over the matches.
        for ($i = 0; $i < count($matches[0]); $i++) {
            if ($matches[0][$i] == $this->ZERO_WIDTH_JOINER) {              //  If the bit is a ZERO_WIDTH_JOINER, then add a 1.
                $secBin .= "1";
            } elseif ($matches[0][$i] == $this->ZERO_WIDTH_NONJOINER) {     //  If the bit is a ZERO_WIDTH_NONJOINER, then add a 1.
                $secBin .= "0";
            } elseif ($matches[0][$i] == $this->ZERO_WIDTH_SPACE) {         //  If the bit is a ZERO_WIDTH_SPACE, then add a space.
                $secBin .= " ";
            }
        }

        //  Convert the vinary into text and return it.
        return $this->bin2str($secBin);
    }
}

?>