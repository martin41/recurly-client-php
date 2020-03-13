<?php


class Recurly_AddonTest extends Recurly_TestCase
{
  function defaultResponses() {
    return array(
      array('GET', '/plans/gold/add_ons/ipaddresses', 'addons/show-200.xml')
    );
  }

  public function testConstructor() {
    $client = new Recurly_Client;
    $plan = new Recurly_Addon(null, $client);

    $prop = new ReflectionProperty($plan, '_client');
    $prop->setAccessible(true);

    $this->assertSame($client, $prop->getValue($plan));
  }

  public function testDelete() {
    $this->client->addResponse(
      'DELETE',
      'https://api.recurly.com/v2/plans/gold/add_ons/ipaddresses',
      'addons/destroy-204.xml'
    );

    $addon = Recurly_Addon::get('gold', 'ipaddresses', $this->client);
    $this->assertInstanceOf('Recurly_Addon', $addon);
    $addon->delete();
  }

  public function testCreateXmlItemBackedAddOn() {
    $item = new Recurly_Item();
    $item->item_code = 'little_llama';
    $addon = new Recurly_Addon();
    $addon->plan_code = 'gold';
    $addon->item_code = $item->item_code;
    $addon->unit_amount_in_cents->addCurrency('USD', 400);

    $this->assertEquals("<?xml version=\"1.0\"?>\n<add_on><item_code>little_llama</item_code><unit_amount_in_cents><USD>400</USD></unit_amount_in_cents></add_on>\n", $addon->xml());
  }
}
