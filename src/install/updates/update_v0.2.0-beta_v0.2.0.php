<?php

use DBA\AgentBinary;
use DBA\QueryFilter;
use DBA\Factory;

require_once(dirname(__FILE__) . "/../../inc/load.php");

echo "Apply updates...\n";

echo "Change logEntry level length... ";
Factory::getAgentFactory()->getDB()->query("ALTER TABLE `LogEntry` CHANGE `level` `level` VARCHAR(20) NOT NULL");
echo "OK\n";

echo "Change plaintext error on BinaryHash... ";
Factory::getAgentFactory()->getDB()->query("ALTER TABLE `HashBinary` CHANGE `plaintext` `plaintext` VARCHAR(200) NULL DEFAULT NULL;");
echo "OK\n";

echo "Check csharp binary... ";
$qF = new QueryFilter(AgentBinary::TYPE, "csharp", "=");
$binary = Factory::getAgentBinaryFactory()->filter([Factory::FILTER => $qF], true);
if ($binary != null) {
  if (Util::versionComparison($binary->getVersion(), "0.40") == 1) {
    echo "update version... ";
    $binary->setVersion("0.40");
    Factory::getAgentBinaryFactory()->update($binary);
    echo "OK";
  }
}
echo "\n";

echo "Update complete!\n";
