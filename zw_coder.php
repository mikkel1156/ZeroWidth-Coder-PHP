<?php
class zw_coder {
    private $ZERO_WIDTH_JOINER = "‍";
    private $ZERO_WIDTH_NONJOINER = "‌";
    private $ZERO_WIDTH_SPACE = "​";
    private $ZERO_WIDTH_NOBREAK_SPACE = "⁠";

    //  Convert string to binary.
    protected function str2bin($text) {
        $bin = array();
        for ($i = 0; strlen($text) > $i; $i ++)
            $bin[] = decbin(ord($text[$i]));
        return implode(' ', $bin);
    }

    //  Convert binary to string.
    protected function bin2str($bin){
        $text = array();
        $bin = explode(' ', $bin);
        for ($i = 0; count($bin) > $i; $i ++)
            $text[] = chr(bindec($bin[$i]));
        return implode($text);
    }

    function encode($secret, $message = null) {
        $secBin = $this->str2bin($secret);
        $secZW = "";

        for ($i = 0; $i < strlen($secBin); $i++) {
            if ($secBin[$i] == "1") {
                $secZW .= $this->ZERO_WIDTH_JOINER;
            } elseif ($secBin[$i] == "0") {
                $secZW .= $this->ZERO_WIDTH_NONJOINER;
            } elseif ($secBin[$i] == " ") {
                $secZW .= $this->ZERO_WIDTH_SPACE;
            }
        }

        if (!empty($message)) {
            $randIndex = rand(0, strlen($message));
            $newMessage = substr($message, 0, $randIndex) . $secZW . substr($message, $randIndex);
            return $newMessage;
        } else {
            return $secZW;
        }
    }

    function decode($secret) {
        $secBin = "";
        preg_match_all("/(‍|‌|​|⁠)/", $secret, $matches);
        for ($i = 0; $i < count($matches[0]); $i++) {
            if ($matches[0][$i] == $this->ZERO_WIDTH_JOINER) {
                $secBin .= "1";
            } elseif ($matches[0][$i] == $this->ZERO_WIDTH_NONJOINER) {
                $secBin .= "0";
            } elseif ($matches[0][$i] == $this->ZERO_WIDTH_SPACE) {
                $secBin .= " ";
            }
        }
        return $this->bin2str($secBin);;
    }
}

?>