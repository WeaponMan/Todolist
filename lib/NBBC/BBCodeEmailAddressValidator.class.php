<?php
namespace NBBC;
class BBCodeEmailAddressValidator {

    function check_email_address($strEmailAddress) {
        if (preg_match('/[\x00-\x1F\x7F-\xFF]/', $strEmailAddress)) {
            return false;
        }
        $intAtSymbol = strrpos($strEmailAddress, '@');
        if ($intAtSymbol === false) {
            return false;
        }
        $arrEmailAddress[0] = substr($strEmailAddress, 0, $intAtSymbol);
        $arrEmailAddress[1] = substr($strEmailAddress, $intAtSymbol + 1);
        $arrTempAddress[0] = preg_replace('/"[^"]+"/'
                , ''
                , $arrEmailAddress[0]);
        $arrTempAddress[1] = $arrEmailAddress[1];
        $strTempAddress = $arrTempAddress[0] . $arrTempAddress[1];
        if (strrpos($strTempAddress, '@') !== false) {
            return false;
        }
        if (!$this->check_local_portion($arrEmailAddress[0])) {
            return false;
        }
        if (!$this->check_domain_portion($arrEmailAddress[1])) {
            return false;
        }
        return true;
    }

    function check_local_portion($strLocalPortion) {
        if (!$this->check_text_length($strLocalPortion, 1, 64)) {
            return false;
        }
        $arrLocalPortion = explode('.', $strLocalPortion);
        for ($i = 0, $max = sizeof($arrLocalPortion); $i < $max; $i++) {
            if (!preg_match('.^('
                            . '([A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]'
                            . '[A-Za-z0-9!#$%&\'*+/=?^_`{|}~-]{0,63})'
                            . '|'
                            . '("[^\\\"]{0,62}")'
                            . ')$.'
                            , $arrLocalPortion[$i])) {
                return false;
            }
        }
        return true;
    }

    function check_domain_portion($strDomainPortion) {
        if (!$this->check_text_length($strDomainPortion, 1, 255)) {
            return false;
        }
        if (preg_match('/^(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])'
                        . '(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}$/'
                        , $strDomainPortion) ||
                preg_match('/^\[(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])'
                        . '(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}\]$/'
                        , $strDomainPortion)) {
            return true;
        } else {
            $arrDomainPortion = explode('.', $strDomainPortion);
            if (sizeof($arrDomainPortion) < 2) {
                return false;
            }
            for ($i = 0, $max = sizeof($arrDomainPortion); $i < $max; $i++) {
                if (!$this->check_text_length($arrDomainPortion[$i], 1, 63)) {
                    return false;
                }
                if (!preg_match('/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|'
                                . '([A-Za-z0-9]+))$/', $arrDomainPortion[$i])) {
                    return false;
                }
            }
        }
        return true;
    }

    function check_text_length($strText, $intMinimum, $intMaximum) {
        $intTextLength = strlen($strText);
        if (($intTextLength < $intMinimum) || ($intTextLength > $intMaximum)) {
            return false;
        } else {
            return true;
        }
    }

}