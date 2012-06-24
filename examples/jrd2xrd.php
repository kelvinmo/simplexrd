<?php
/*
 * SimpleXRD
 *
 * Copyright (C) Kelvin Mo 2012
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above
 *    copyright notice, this list of conditions and the following
 *    disclaimer in the documentation and/or other materials provided
 *    with the distribution.
 *
 * 3. The name of the author may not be used to endorse or promote
 *    products derived from this software without specific prior
 *    written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 * GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER
 * IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN
 * IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

include "../simplexrd/simplexrd.class.php";

$json = file_get_contents("php://stdin");
$jrd = json_decode($json);

print "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
print "<XRD xmlns=\"http://docs.oasis-open.org/ns/xri/xrd-1.0\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">\n";

foreach ($jrd as $key => $value) {
    switch ($key) {
        case 'subject':
        case 'expires':
            $element = ucfirst($key);
            print '<' . $element . '>';
            print htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            print '</' . $element . ">\n";
            break;
        case 'aliases':
            foreach ($value as $alias) {
                print '<Alias>';
                print htmlspecialchars($alias, ENT_QUOTES, 'UTF-8');
                print "</Alias>\n";
            }
            break;
        case 'properties':
            foreach ($value as $type => $property) {
                if (is_null($property)) {
                    print '<Property type="' . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . '" xsi:nil="true"/>';
                } else {
                    print '<Property type="' . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . '">';
                    print htmlspecialchars($property, ENT_QUOTES, 'UTF-8');
                    print "</Property>\n";
                }
            }
            break;
        case 'links':
            foreach ($value as $link) {
                link2xrd($link);
            }
            break;
    }
}

print "</XRD>\n";


function link2xrd($link) {
    $attribs = array();
    $contents = '';
    
    foreach ($link as $key => $value) {
        switch ($key) {
            case 'properties':
                foreach ($value as $type => $property) {
                    if (is_null($property)) {
                        $contents .= '<Property type="' . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . '" xsi:nil="true"/>';
                    } else {
                        $contents .= '<Property type="' . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . '">';
                        $contents .= htmlspecialchars($property, ENT_QUOTES, 'UTF-8');
                        $contents .= "</Property>\n";
                    }
                }
                break;
            case 'titles':
                foreach ($value as $lang => $title) {
                    if ($lang == 'default') {
                        $contents .= '<Title>';
                    } else {
                        $contents .= '<Title xml:lang="' . htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') . '">';
                    }
                    $contents .= htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
                    $contents .= "</Title>\n";
                }
                break;
            default:
                $attribs[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
    }
    
    print '<Link ';
    foreach ($attribs as $key => $value) {
        print $key;
        print '="';
        print $value;
        print '" ';
    }
    print ">\n";
    print $contents;
    print "</Link>\n";
}
?>