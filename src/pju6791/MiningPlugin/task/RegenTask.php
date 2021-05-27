<?php

namespace pju6791\MiningPlugin\task;

use pocketmine\scheduler\Task;

use pocketmine\block\Block;

class RegenTask extends Task {

    public Block $block;

    public function __construct(Block $block) {
        $this->block = $block;
    }

    public function onRun($currentTick)
    {
        $this->block->getLevel()->setBlock($this->block, $this->block);
    }
}
