<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 19/12/2018
 * Time: 15:36
 */

namespace Console\Options;

/**
 * Class OptionParser
 * @package Console\Options
 */
class OptionParser implements \ArrayAccess
{
    /**
     * options
     * @var Option[]
     */
    private $options = [];
    /**
     * parameters
     * @var array
     */
    private $parameters;

    /**
     * OptionParser constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->parameters = [];
        foreach ($options as $option) {
            $this->addOption($option);
        }
    }

    /**
     * intègre une nouvelle option
     * @param Option $option
     */
    private function addOption(Option $option): void
    {
        if (array_key_exists('-' . $option->getName(),$this->options)) {
            throw new \RuntimeException("plusieur options portent le nom {$option->getName()}.");
        }
        $this->options['-' . $option->getName()] = $option;
        if ($option->getShortname() !== null) {
            if (array_key_exists('-' . $option->getShortname(), $this->options)) {
                throw  new \RuntimeException("plusieur options portent le nom court {$option->getShortname()}.");
            }
            $this->options['-' . $option->getShortname()] = $option;
        }
    }

    /**
     * lits les paramèrtes donné au script et les affectes aux options
     * @param $argv
     * @return void
     */
    public function parse($argv): void
    {
        for ($index = 1, $indexMax = count($argv); $index < $indexMax; $index++) {
            $argument = $argv[$index];
            if (isset($this->options[$argument])) {
                $option = $this->options[$argument];
                if ($option->isFlag()) {
                    $option->setValue(true);
                } else {
                    if (!isset($argv[$index + 1])) {
                        throw new \LogicException("l'option {$option->getName()} necessite une valeur.");
                    }
                    $value = $argv[$index + 1];
                    if (isset($this->options[$value])) {
                        throw new \LogicException("l'option {$option->getName()} necessite une valeur.");
                    }
                    $option->setValue($value);
                    $index++;
                }
            } else {
                $this->parameters[] = $argument;
            }
        }
        $this->testRequired();
    }

    /**
     * vérifie si une option nécéssite encore une valeur
     * @return void
     */
    private function testRequired(): void
    {
        foreach ($this->options as $option) {
            if ($option->isRequired()) {
                throw new \LogicException("l'option {$option->getName()} n'a pas été initialisée.");
            }
        }
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        if (is_int($offset)) {
            return isset($this->parameters[$offset]);
        }
        return array_key_exists('-' . $offset, $this->options) && $this->options['-' . $offset]->hasValue();
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (is_int($offset)) {
            if (isset($this->parameters[$offset])) {
                return $this->parameters[$offset];
            }
            throw new \OutOfRangeException("Le paramètre $offset n'existe pas.");
        }
        if (array_key_exists('-' . $offset, $this->options)) {
            if (!$this->options['-' . $offset]->hasValue()) {
                throw new \UnexpectedValueException("L'option $offset n'a pas de valeur.");
            }
            return $this->options['-' . $offset]->getValue();
        }
        throw new \OutOfBoundsException("L'option $offset n'existe pas.");

    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Offset en lecture seule');
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Offset en lecture seule');
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $options = [];
        foreach ($this->options as $option) {
            $options[$option->getName()] = $option->getValue();
        }
        return $options;
    }

}