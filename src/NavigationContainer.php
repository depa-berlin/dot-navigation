<?php
/**
 * @see https://github.com/dotkernel/dot-navigation/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-navigation/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Navigation;

/**
 * Class Container
 * @package Dot\Navigation
 */
class NavigationContainer implements \RecursiveIterator
{
    /**
     * Index of current active child
     * @var int
     */
    protected $index = 0;

    /**
     * Child nodes
     * @var array
     */
    protected $children = [];

    /**
     * NavigationContainer constructor.
     * @param array $pages
     */
    public function __construct(array $pages = [])
    {
        $this->addPages($pages);
    }

    /**
     * @param array $pages
     */
    public function addPages(array $pages)
    {
        foreach ($pages as $page) {
            $this->addPage($page);
        }
    }

    /**
     * @param Page $page
     */
    public function addPage(Page $page)
    {
        $this->children[] = $page;
    }

    /**
     * @return NavigationContainer
     */
    public function current(): NavigationContainer
    {
        return $this->children[$this->index];
    }

    /**
     * Increment current position to the next element
     */
    public function next(): void
    {
        $this->index++;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->children[$this->index]);
    }

    /**
     * Reset position to the first element
     */
    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    /**
     * @return NavigationContainer
     */
    public function getChildren(): NavigationContainer
    {
        return $this->children[$this->index];
    }

    /**
     * Find a single child by attribute
     *
     * @param string $attribute
     * @param mixed $value
     * @return Page|null
     */
    public function findOneByAttribute(string $attribute, $value): ?Page
    {
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);
        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getAttribute($attribute) === $value) {
                return $page;
            }
        }
        return null;
    }

    /**
     * Find all children by attribute
     *
     * @param string $attribute
     * @param mixed $value
     * @return array
     */
    public function findByAttribute(string $attribute, $value): array
    {
        $result = [];
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);

        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getAttribute($attribute) == $value) {
                $result[] = $page;
            }
        }
        return $result;
    }

    /**
     * Finds a single child by option.
     *
     * @param string $option
     * @param mixed $value
     * @return Page|null
     */
    public function findOneByOption(string $option, $value): ?Page
    {
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);
        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getOption($option) == $value) {
                return $page;
            }
        }
        return null;
    }

    /**
     * Finds all children by option.
     *
     * @param string $option
     * @param mixed $value
     * @return array
     */
    public function findByOption(string $option, $value): array
    {
        $result = [];
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);
        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getOption($option) == $value) {
                $result[] = $page;
            }
        }
        return $result;
    }
}
