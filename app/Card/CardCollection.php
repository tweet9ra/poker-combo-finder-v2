<?php


namespace App\Card;

class CardCollection implements \Iterator, \ArrayAccess, \Countable
{
    protected $items;

    /**
     * CardCollection constructor.
     * @param Card[] $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            if (!($item instanceof Card)) {
                throw new \InvalidArgumentException("Входной массив должен состоять только из ".Card::class);
            }
        }
        $this->items = $items;
    }

    public function sortByValue($order = 'desc')
    {
        $items = $this->items;

        usort($items, function (Card $a, Card $b) use ($order) {
            if ($b->getWeight() === $a->getWeight()) {
                return $a->getSuit() <=> $b->getSuit();
            }

            return $order === 'desc'
                ? $b->getWeight() <=> $a->getWeight()
                : $a->getWeight() <=> $b->getWeight();
        });

        $this->items = $items;

        return $this;
    }

    public function pluckSignatures()
    {
        return array_map(function (Card $card) {
            return $card->getSignature();
        }, $this->items);
    }

    public function intersect(CardCollection $collection)
    {
        return new CardCollection(
            array_uintersect(
                $this->items,
                $collection->toArray(),
                function (Card $e1, Card $e2) {
                    return $e1->getSignature() <=> $e2->getSignature();
                }
            )
        );
    }

    public function filterBySuit() : array
    {
        return $this->filterBy('suit');
    }

    public function filterByValue() : array
    {
        return $this->filterBy('value');
    }

    public function first() :? Card
    {
        return $this->items[0] ?? null;
    }

    protected function filterBy($by) : array
    {
        $valuesBy = [];

        foreach ($this->items as $card) {
            $key = $by === 'suit'
                ? $card->getSuit()
                : $card->getValue();

            if (!isset($valuesBy[$key])) {
                $valuesBy[$key] = [$card];
            } else {
                $valuesBy[$key][] = $card;
            }
        }

        return $valuesBy;
    }

    /**
     * Возвращает элементы, которые есть в передаваемой коллекции, но отсутствуют в текущей
     * @param CardCollection $collection
     * @return CardCollection
     */
    public function diff(self $collection) : self
    {
        $diff = new self;
        foreach ($this as $card) {
            if ($collection->search($card) === false) {
                $diff[] = $card;
            }
        }

        return $diff;
    }

    /**
     * Поиск карты в коллекции
     * @param Card $searchedCard
     * @return bool|int|string
     */
    public function search(Card $searchedCard) {
        foreach ($this->items as $key => $card) {
            if ($searchedCard->getSignature() === $card->getSignature()) {
                return $key;
            }
        }

        return false;
    }

    public function merge(\Traversable $collectionOrArray)
    {
        foreach ($collectionOrArray as $item) {
            $this->items[] = $item;
        }
        return $this;
    }

    public function slice(int $offset, int $length = null, bool $preserveKeys = false)
    {
        return new self(array_slice($this->items, $offset, $length, $preserveKeys));
    }

    public function push(Card $card)
    {
        $this->items[] = $card;
        return $this;
    }

    public function pop()
    {
        return array_pop($this->items);
    }

    public function toArray()
    {
        return $this->items;
    }

    public function count()
    {
        return count($this->items);
    }

    public function rewind()
    {
        reset($this->items);
    }

    public function current()
    {
        return current($this->items);
    }

    public function key()
    {
        return key($this->items);
    }

    public function next()
    {
        return next($this->items);
    }

    public function valid()
    {
        $key = key($this->items);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->items[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }
}