<?php

class RandomItemManager
{
    /** @var int */
    private $maxUniqueItem;
    /** @var int  */
    private $maxSingleItemCount;
    /** @var int  */
    private $maxWeight;

    public function __construct(int $maxUniqueItem, int $maxSingleItemCount, int $maxWeight)
    {
        $this->maxUniqueItem = $maxUniqueItem;
        $this->maxSingleItemCount = $maxSingleItemCount;
        $this->maxWeight = $maxWeight;
    }

    /**
     * Generates random items which have weight <= [[$this->maxWeight]]. At least 1 item will be present in result.
     *
     * @param $items
     * @return array
     */
    public function generateSet($items)
    {
        $result = [];
        $leftSpace = $this->maxWeight;
        $uniqueItem = 0;
        foreach ($items as $item) {
            if ($uniqueItem == $this->maxUniqueItem) {
                break;
            }
            if (rand(1, 100) < $item->getChance()) {
                $itemCount = rand(1, $this->maxSingleItemCount);
                $totalWeight = $itemCount * $item->getWeight();
                //check if have enough size in backpack
                if ($totalWeight <= $leftSpace) {
                    $leftSpace -= $totalWeight;
                    $result[$item->getName()] = $itemCount;
                    $uniqueItem++;
                } else {
                    //can be 0
                    $fitItemCount = (int) floor($leftSpace / $item->getWeight());
                    $leftSpace -= $fitItemCount * $item->getWeight();
                    $result[$item->getName()] = $fitItemCount;
                    if ($fitItemCount) {
                        $uniqueItem++;
                    }
                }
            } else {
                $result[$item->getName()] = 0;
            }

        }

        //if 0 item try one more time
        if ($leftSpace == $this->maxWeight) {
            return $this->generateSet($items);
        }

        return $result;
    }

    public function generateChangedSet($previousSet, $items)
    {
        $newSet = $this->generateSet($items);
        if ($this->isSameSets($previousSet, $newSet)) {
            return $this->generateChangedSet($previousSet, $items);
        }

        return $newSet;
    }

    /**
     * @param $setOne array
     * @param $setTwo array
     * @return bool
     */
    public function isSameSets($setOne, $setTwo): bool
    {
        foreach ($setOne as $itemName => $itemCount) {
            if ($setTwo[$itemName] != $itemCount) {
                return false;
            }
        }

        return true;
    }
}
