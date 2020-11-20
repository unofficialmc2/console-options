<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 19/12/2018
 * Time: 15:41
 */

namespace Console\Options;

class Option
{
    /**
     * Type d'option
     */
    const T_FLAG = 1;
    const T_INTEGER = 2;
    const T_DOUBLE = 16;
    const T_STRING = 4;
    const T_PATH = 8;
    /**
     * nom long de l'option
     * @var string
     */
    private $name;
    /**
     * nom court de l'option
     * @var string|null
     */
    private $shortname;
    /**
     * type de l'option
     * @var int
     */
    private $type;
    /**
     * option requise
     * @var bool
     */
    private $required;
    /**
     * l'option a reçu une valeur
     * @var bool
     */
    private $hasValue;
    /**
     * valeur de l'option
     * @var mixed
     */
    private $value;
    /**
     * l'option a reçu une valeur par defaut
     * @var bool
     */
    private $hasDefault;
    /**
     * valeur par defaut de l'option
     * @var mixed
     */
    private $default;

    /**
     * Option constructor.
     * @param string $name
     * @param string|null $shortname
     */
    public function __construct(string $name, ?string $shortname = null)
    {
        $this->name = $name;
        $this->shortname = $this->valideShortName($shortname);
        $this->type = self::T_STRING;
        $this->required = false;
    }

    /**
     * valide un nom court
     * @param string|null $shortname
     * @return string|null
     */
    private function valideShortName(?string $shortname): ?string
    {
        if (is_null($shortname) || strlen($shortname) == 1) {
            return $shortname;
        }
        throw new \RuntimeException("Le nom court de l'option {$this->name} ne peut pas être $shortname");
    }

    /**
     * @param int $type
     * @return Option
     */
    public function setType(int $type): Option
    {
        $this->type = $this->valideType($type);
        if ($type == self::T_FLAG) {
            $this->setDefault(false);
        } elseif ($this->hasDefault) {
            $this->setDefault($this->default);
        }
        return $this;
    }

    /**
     * @param int $type
     * @return int
     */
    private function valideType(int $type): int
    {
        $lstType = [
            self::T_FLAG,
            self::T_INTEGER,
            self::T_STRING,
            self::T_PATH,
            self::T_DOUBLE,
        ];
        if (array_search($type, $lstType) !== false) {
            return $type;
        }
        throw new \RuntimeException("Le type de l'option {$this->name} ne peut pas être $type");
    }

    /**
     * @param mixed $default
     * @return Option
     */
    public function setDefault($default)
    {
        $this->hasDefault = true;
        $this->default = $this->cast($default);
        return $this;
    }

    /**
     * cast une valeur selon le type de l'option
     * @param mixed $value
     * @return bool|float|int|string
     */
    private function cast($value)
    {
        switch ($this->type) {
            case self::T_FLAG:
                return (bool)$value;
            case self::T_INTEGER:
                return (int)$value;
            case self::T_DOUBLE:
                return (double)$value;
            case self::T_STRING:
            case self::T_PATH:
            default:
                return (string)$value;
        }
    }

    /**
     * retourne la valeur de l'option
     * @return mixed
     */
    public function getValue()
    {
        if ($this->hasValue) {
            return $this->value;
        }
        if ($this->hasDefault) {
            return $this->default;
        }
        return null;
    }

    /**
     * initialise la value de l'option
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->hasValue = true;
        $this->value = $this->cast($value);
    }

    /**
     * test si l'option a une valeur toujours requise
     * @return bool
     */
    public function isRequired(): bool
    {
        return (!$this->hasValue && $this->required);
    }

    /**
     * @return Option
     */
    public function setRequired(): Option
    {
        $this->required = true;
        return $this;
    }

    /**
     * retourne le nom court
     * @return string|null
     */
    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    /**
     * retourne le nom
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * test si l'option est un flag
     * @return bool
     */
    public function isFlag(): bool
    {
        return ($this->type & self::T_FLAG) === self::T_FLAG;
    }

    /**
     * @return bool
     */
    public function hasValue(): bool
    {
        return $this->hasDefault || $this->hasValue;
    }
}
