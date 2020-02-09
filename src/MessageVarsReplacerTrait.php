<?php
/**
 * @package evas-php/evas-base
 */
namespace Evas\Base;

/**
 * Трейт-хелпер для подстановок значений переменных в сообщения.
 * на место :var подставляет значение переменной var
 * на место <текст :var> подставляет текст + переменную var, только при наличии значения var
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait MessageVarsReplacerTrait
{
    /**
     * Подстановка значения переменных в сообщение.
     * @param string сообщение
     * @param array|null значения для подстановки
     * @return string ообщение с вставленными переменными
     */
    public function setMessageVars(string $message, array $vars = null): string
    {
        if (null === $vars) $vars = @get_object_vars($this) ?? null;
        if (null === $vars) return $message;
        $message = preg_replace_callback(
            '/<(?<before>[^<]*):(?<key>[a-zA-Z]*)(?<after>[^<]*)>|:(?<key2>[a-zA-Z]*)/', 
            function ($matches) {
                extract($matches);
                if (empty($key)) $key = $key2;
                $value = $vars[$key] ?? null;
                if (empty($value)) {
                    if (empty(static::$message_aliases_keys)) return '';
                    $alias = static::$message_aliases_keys[$key] ?? null;
                    if (empty($alias)) return '';
                    $value = $vars[$alias] ?? null;
                    if (empty($value)) return '';
                }
                return $before . $value . $after;
            }, 
            $message);
        return $message;
    }
}
