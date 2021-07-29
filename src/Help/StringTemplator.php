<?php
/**
 * Шаблонизатор строк с возможностью подстановки свойств.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Base\Help;

class StringTemplator
{
    /** @static string символ начала свойства */
    public $varOpenSym = '{{';
    /** @static string символ конца свойства */
    public $varCloseSym = '}}';
    /** @static string символ начала необязательного(опционального) свойства */
    public $optiOpenSym = '\(\(';
    /** @static string символ конца необязательного(опционального) свойства */
    public $optiCloseSym = '\)\)';
    /** @static string маска свойства */
    protected $varMask = '[a-zA-Z_]{1}[a-zA-Z0-9_]*';

    /**
     * Конструктор.
     * @param array|null свойства
     */
    public function __construct(array $props = null)
    {
        if (!empty($props)) foreach ($props as $name => $value) {
            $this->$name = $value;
        }
    }

    /**
     * Получение маски.
     * @return string
     */
    public function getMask(): string
    {
        $varOpenSym = $this->varOpenSym;
        $varCloseSym = $this->varCloseSym;
        $optiOpenSym = $this->optiOpenSym;
        $optiCloseSym = $this->optiCloseSym;
        $varMask = $this->varMask;
        return "/{$optiOpenSym}(?<before>[^{$optiOpenSym}]*){$varOpenSym}(?<key>{$varMask}){$varCloseSym}(?<after>[^{$optiCloseSym}]*){$optiCloseSym}|{$varOpenSym}(?<key2>{$varMask}){$varCloseSym}/";
    }

    /**
     * Сборка сообщения со значениями по шаблону.
     * @param string шаблон сообщения
     * @param array|object|null маппинг или объект со значениями
     * @param array|null маппинг псевдонимов
     * @return string сообщение
     * @throws \InvalidArgumentException
     */
    public function build(string $template, $vars = null, array $keyAliases = null): string
    {
        if (null !== $vars && !is_array($vars) && !is_object($vars)) {
            throw new \InvalidArgumentException(sprintf('Argument 2 passed to %s() must be an array or an object, %s given', __METHOD__, gettype($vars)));
        }
        if (is_object($vars)) $vars = get_object_vars($vars);
        return preg_replace_callback(
            $this->getMask(), 
            function ($matches) use ($vars, $keyAliases) {
                extract($matches);
                if (empty($key)) $key = $key2;
                $value = $vars[$key] ?? null;
                if (empty($value)) {
                    $value = @$vars[$keyAliases[$key]];
                    if (null === $value) return '';
                }
                return $before . $value . $after;
            }, 
            $template
        );
    }
}
