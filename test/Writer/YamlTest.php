<?php
/**
 * @see       https://github.com/zendframework/zend-config for the canonical source repository
 * @copyright Copyright (c) 2005-2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-config/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Config\Writer;

use Zend\Config\Config;
use Zend\Config\Reader\Yaml as YamlReader;
use Zend\Config\Writer\Yaml as YamlWriter;

/**
 * @group      Zend_Config
 */
class YamlTest extends AbstractWriterTestCase
{
    public function setUp()
    {
        if (! getenv('TESTS_ZEND_CONFIG_YAML_ENABLED')) {
            $this->markTestSkipped('Yaml test for Zend\Config skipped');
        }

        if ($lib = getenv('TESTS_ZEND_CONFIG_YAML_LIB_INCLUDE')) {
            require_once $lib;
        }

        if ($readerCallback = getenv('TESTS_ZEND_CONFIG_READER_YAML_CALLBACK')) {
            $yamlReader = explode('::', $readerCallback);
            if (isset($yamlReader[1])) {
                $this->reader = new YamlReader([$yamlReader[0], $yamlReader[1]]);
            } else {
                $this->reader = new YamlReader([$yamlReader[0]]);
            }
        } else {
            $this->reader = new YamlReader();
        }

        if ($writerCallback = getenv('TESTS_ZEND_CONFIG_WRITER_YAML_CALLBACK')) {
            $yamlWriter = explode('::', $writerCallback);
            if (isset($yamlWriter[1])) {
                $this->writer = new YamlWriter([$yamlWriter[0], $yamlWriter[1]]);
            } else {
                $this->writer = new YamlWriter([$yamlWriter[0]]);
            }
        } else {
            $this->writer = new YamlWriter();
        }
    }

    public function testNoSection()
    {
        $config = new Config(['test' => 'foo', 'test2' => ['test3' => 'bar']]);

        $this->writer->toFile($this->getTestAssetFileName(), $config);

        $config = $this->reader->fromFile($this->getTestAssetFileName());

        $this->assertEquals('foo', $config['test']);
        $this->assertEquals('bar', $config['test2']['test3']);
    }

    public function testWriteAndReadOriginalFile()
    {
        $config = $this->reader->fromFile(__DIR__ . '/_files/allsections.yaml');

        $this->writer->toFile($this->getTestAssetFileName(), $config);

        $config = $this->reader->fromFile($this->getTestAssetFileName());

        $this->assertEquals('multi', $config['all']['one']['two']['three']);
    }
}
