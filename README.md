simplexrd
=========

SimpleXRD is an extraordinarily simple parser for
[XRD documents](http://docs.oasis-open.org/xri/xrd/v1.0/xrd-1.0.html) written
in PHP 5.

This XRD parser supports all the features of XRD which can be translated into
its JSON representation under [RFC 6415](http://tools.ietf.org/html/rfc6415).
This means that the parser does not support extensibility under the XRD
specification.

Usage
-----

Using the parser is straightforward.  Assuming the XRD code has been loaded
into a variable called `$xml`. Then the code is simply

<pre>
$parser = new SimpleXRD();
$parser->load($xml);
$jrd = $parser->parse();
$parser->free();
</pre>

The JSON representation can then be obtained by using `json_encode($jrd)`.

Licensing
---------

Licensing information for SimpleXRD can be found in the file COPYING.txt.