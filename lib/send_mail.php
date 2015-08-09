<?php

function send_mail($to, $subject, $htmlbody, $altbody, $from_address = null, $from_full_name = '') {
    $mail_conf = new Config('lib/mail.ini');
    $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $from = mb_encode_mimeheader($mail_conf['application_name']) . '<' . $mail_conf['email_address'] . '>';
    $mime_boundary = '=_' . md5(time() . 'todolist.oneksys.cz') . '_=';
    $headers = [
                'MIME-Version: 1.0',
                'Content-Type: multipart/alternative; boundary=' . $mime_boundary,
                'Content-Transfer-Encoding: 8bit',
                'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
                'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>',
                'From: ' . $from,
                'Return-Path: ' . $from,
                'X-Mailer: PHP/' . phpversion(),
                'X-Originating-IP: ' . $_SERVER['SERVER_ADDR'],
    ];
    if ($from_address !== null)
        $headers[] = 'Reply-To: ' . mb_encode_mimeheader($from_full_name) . '<' . $from_address . '>';

    $body = '--' . $mime_boundary . "\r\n";
    $body .= 'Content-Type: text/plain; charset="charset=utf-8"' . "\r\n";
    $body .= 'Content-Transfer-Encoding: 8bit' . "\r\n\r\n";
    $body .= wordwrap($altbody, 70);
    $body .= "\r\n\r\n";

    $body .= '--' . $mime_boundary . "\n";
    $body .= 'Content-Type: text/html; charset="UTF-8"' . "\r\n";
    $body .= 'Content-Transfer-Encoding: 8bit' . "\r\n\r\n";
    $body .= wordwrap($htmlbody, 70);
    $body .= "\r\n\r\n";
    $body .= '--' . $mime_boundary . '--';

    if (mail($to, $subject, $body, implode("\r\n", $headers)))
        return true;
    return false;
}
