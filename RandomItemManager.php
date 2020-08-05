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
     * @param $items Item[]
     * @return int
     */
    private function getItemsTotalChance($items)
    {
        $sumChance = 0;
        foreach ($items as $item) {
            $sumChance += $item->getChance();
        }

        return $sumChance;
    }

    /**
     * Group and count items. Example
     * ["iron", "gold", "gold"] became
     * [
     *   "iron" => 1,
     *   "gold" => 2,
     * ]
     *
     * @param $items array
     * @return array
     */
    private function groupItems($items)
    {
        $resultItems = [];
        foreach ($items as $item) {
            if (!isset($resultItems[$item])) {
                $resultItems[$item] = 1;
            } else {
                $resultItems[$item]++;
            }
        }

        return $resultItems;
    }

    private function getMaxCount($groupItems)
    {
        $max = 0;
        foreach ($groupItems as $key => $count) {
            if ($count > $max) {
                $max = $count;
            }
        }

        return $max;
    }

    /**
     * Generates random items which have weight <= [[$this->maxWeight]]. At least 1 item will be present in result.
     *
     * @param $items Item[]
     * @return array
     */
    public function generateSet($items)
    {
        $result = [];
        $sumChances = $this->getItemsTotalChance($items);
        $leftSpace = $this->maxWeight;
        while (true) {
            $randomChance = rand(1, $sumChances);
            $tempSumChances = 0;
            $randomItem = null;
            foreach ($items as $item) {
                $tempSumChances += $item->getChance();
                if ($randomChance <= $tempSumChances) {
                    $randomItem = $item;
                    break;
                }
            }

            //backpack is full
            if ($leftSpace - $randomItem->getWeight() < 0) {
                break;
            }

            //invalid unique items
            $groupItems = $this->groupItems(array_merge($result, [$randomItem->getName()]));
            if (count($groupItems) > $this->maxSingleItemCount) {
                break;
            }

            //invalid count item
            if ($this->getMaxCount($groupItems) > $this->maxUniqueItem) {
                break;
            }

            $leftSpace -= $randomItem->getWeight();
            $result[] = $randomItem->getName();
        }

        return $this->groupItems($result);
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
