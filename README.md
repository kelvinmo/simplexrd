simplexrd
=========

SimpleXRD is an extraordinary simple parser for
[XRD documents](http://docs.oasis-open.org/xri/xrd/v1.0/xrd-1.0.html).

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
$jrd = $parser->parse($xml);
$parser->free();
</pre>

Licensing
---------

Licensing information for SimpleID can be found in the file COPYING.txt.