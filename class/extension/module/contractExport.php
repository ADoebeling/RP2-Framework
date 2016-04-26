<?php

namespace rpf\extension\module;
use rpf\extension\extensionModule;

/**
 * Class contractExport
 *
 * @author Andreas Doebeling <ad@1601.com>
 * @copyright 1601.production siegler&thuemmler ohg
 * @link https://github.com/ADoebeling/RP2-Framework
 * @link http://xing.doebeling.de
 * @link https://www.1601.com
 */
class contractExport extends extensionModule
{
    protected $oeids = [];

    protected $data = [];
    
    public function addOeid($oeid)
    {
        $this->oeids[] = $oeid;
        return $this;
    }
    
    public function addDomains($addAuthCode = true)
    {
        foreach ($this->oeids as $oeid)
        {
            $results = $this->getApi()->getDomainReadEntry()->setOeid($oeid)->getArray();
            foreach ($results as $row)
            {
                $this->data['domains'][$row['name']] =
                    [
                        'name' => $row['name'] ,
                        'authcode' => $addAuthCode && isset($row['authcode']) ? $row['authcode'] : NULL,
                    ];
            }
        }
        return $this;
    }

    public function getDomains()
    {
        $result =  "-----------------------------------------------------------------\n";
        $result .= "+ Domains mit Stand vom ".date("d. F Y H:i")." Uhr\n";
        $result .= "-----------------------------------------------------------------\n\n";

        foreach ($this->data['domains'] as $row) {
            $result .= "- {$row['name']} ";
            $result .= isset($row['authcode']) ? "(AuthCode: \"{$row['authcode']}\")" : '';
            $result .= "\n";
        }
        return $result;
    }

    public function addMails($password = false)
    {
        foreach ($this->oeids as $oeid)
        {
            $results = $this
                ->getApi()
                ->getEmailReadAccount()
                ->setOeid($oeid)
                ->addUsed()
                ->getArray(true, 'email');

            foreach ($results as $pk => $row)
            {
                $alias = NULL;
                if (isset($row['address']) && is_array($row['address']))
                {
                    foreach($row['address'] as $a) if ($a['email'] != $pk) $alias .= empty($alias) ? $a['email'] : ", {$a['email']}";
                }

                $forwarder = NULL;
                if (isset($row['forwarder']) && is_array($row['forwarder'])) foreach($row['forwarder'] as $f) $forwarder .= empty($forwarder) ? $f : ", $f";

                $this->data['mails'][$pk] =
                    [
                        'name' => $row['email'] ,
                        'alias' => $alias,
                        'forwarder' => !empty($forwarder) ? $forwarder : NULL,
                        'imapSize' => isset($row['storage']['size']) ? $row['storage']['size'] : NULL,
                        'exchangeSize' => isset($row['exchange_storage']['size']) ? $row['exchange_storage']['size'] : NULL,
                        'spaceUsed' => isset($row['used']['space']) ? $row['used']['space'] : NULL,
                        'spaceMails' => isset($row['used']['mails']) ? $row['used']['mails'] : NULL,
                        'password' => $password && isset($row['password'])? $row['password'] : '********',

                        'exchangeDisplayName' => isset($row['personal_data']['displayname']) ? $row['personal_data']['displayname'] : NULL,
                        'simpleSpamFilter' => isset($row['simple_filters']['spam_action']) ? $row['simple_filters']['spam_action'] : NULL,
                        'simpleVirusFilter' => isset($row['simple_filters']['reject_viruses']) ? $row['simple_filters']['reject_viruses'] : NULL,
                    ];
            }
        }
        return $this;
    }

    public function getMails()
    {

        $result =  "-----------------------------------------------------------------\n";
        $result .= "+ E-Mail-Accounts mit Stand vom ".date("d. F Y H:i")." Uhr\n";
        $result .= "-----------------------------------------------------------------\n\n";

        foreach ($this->data['mails'] as $row)
        {
            $spam = '';
            // Simple Mail Filter doesn't take action on mail-forwarder, so they should not be displayed
            // https://github.com/ADoebeling/RP2-Framework/issues/45
            if (isset($row['simpleSpamFilter']) && (isset($row['imapSize']) || isset($row['exchangeSize'])))
            {
                switch ($row['simpleSpamFilter'])
                {
                    case 'tag':
                        $spam = "- Erkannte Spam-Mails werden im Betreff markiert\n";
                        break;
                    case 'reject':
                        $spam = "- Erkannte Spam-Mails werden mit Nachricht an Absender abgelehnt\n";
                        break;
                    case 'drop':
                        $spam = "- Erkannte Spam-Mails werden ohne Nachricht an Absender gelöscht\n";
                        break;
                    default:
                        $spam = '';
                        break;
                }
            }

            $result .= "{$row['name']}";
            $result .= isset($row['exchangeDisplayName']) ? " ({$row['exchangeDisplayName']})" : '';
            $result .= "\n";
            $result .= isset($row['imapSize']) ? "- POP3/IMAP/SMTP-Account mit {$this->formatSize($row['imapSize'])} Speicher\n" : '';
            $result .= isset($row['exchangeSize']) ? "- Exchange-Account mit {$this->formatSize($row['exchangeSize'])}\n" : '';
            $result .= isset($row['spaceUsed']) ? "- Davon aktuell {$this->formatSize($row['spaceUsed'])} durch {$row['spaceMails']} E-Mail(s) belegt\n" : '';
            $result .= isset($spam) ? $spam : '';

            // Simple Mail Filter doesn't take action on mail-forwarder, so they should not be displayed
            // https://github.com/ADoebeling/RP2-Framework/issues/45
            $result .= isset($row['simpleSpamFilter']) && $row['simpleVirusFilter'] == 1 && ($row['imapSize']) > 0 || $row['exchangeSize'] > 0 ? "- Erkannte Viren werden ohne Nachricht an Absender gelöscht\n" : '';

            // RPC-Bug: Passwords of deleted accounts are stored in the db
            // https://github.com/ADoebeling/RP2-Framework/issues/44
            $result .= isset($row['password']) &&  ($row['imapSize'] || isset($row['exchangeSize'])) ? "- Passwort: {$row['password']}\n" : '';
            $result .= isset($row['alias']) ? "- Alias-Adressen: {$row['alias']}\n" : '';
            $result .= isset($row['forwarder']) ? "- Weiterleitung: {$row['forwarder']}\n" : '';
            $result .= "\n";
        }
        return $result;
    }

    public static function formatSize($size)
    {
        $size = intval(number_format(floatval($size), 0, '.', ''));
        if ($size < 0)
        {
            return "unlimitiertem Speicher";
        }
        elseif ($size < 1024)
        {
            return number_format($size, 0, ',', '.').' MB';
        }
        else
        {
            return number_format($size/1024, 2, ',', '.').' GB';
        }
    }
}