<?php

include_once "../simplexrd/simplexrd.class.php";

class SimpleXRDTest extends PHPUnit_Framework_TestCase {

    protected $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<XRD xmlns="http://docs.oasis-open.org/ns/xri/xrd-1.0"
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <Subject>http://example.com/</Subject>
  <Expires>1970-01-01T00:00:00Z</Expires>
  <Alias>https://example.com/</Alias>
  <Link rel="lrdd"
        template="/.well-known/webfinger?resource={uri}" />
  <Link rel="http://spec.example.net/photo/1.0" type="image/jpeg"
    href="http://photos.example.com/gpburdell.jpg">
    <Title>User Photo</Title>
    <Title xml:lang="de">Benutzerfoto</Title>
    <Property type="http://spec.example.net/created/1.0">1970-01-01</Property>
  </Link>
  <Property type="describedby">/me.foaf</Property>
  <Property type="private"
            xsi:nil="true" />
</XRD>
';

    public function testParse() {
        $parser = new SimpleXRD();
        $parser->load($this->xml);
        $jrd = $parser->parse();

        $this->assertEquals('/.well-known/webfinger?resource={uri}', $jrd['links'][0]['template']);
        $this->assertEquals(NULL, $jrd['properties']['private']);

        $this->assertEquals('http://photos.example.com/gpburdell.jpg', $jrd['links'][1]['href']);
        $this->assertEquals('User Photo', $jrd['links'][1]['titles']['und']);
        $this->assertEquals('Benutzerfoto', $jrd['links'][1]['titles']['de']);
        $this->assertEquals('1970-01-01', $jrd['links'][1]['properties']['http://spec.example.net/created/1.0']);

        $this->assertArrayNotHasKey('expires', $jrd);
    }

    public function testExpires() {
        $parser = new SimpleXRD();
        $parser->load($this->xml);
        $jrd = $parser->parse(true);

        $this->assertEquals('1970-01-01T00:00:00Z', $jrd['expires']);
    }
}
?>