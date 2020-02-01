<?php
/**
 * @package evas-php/evas-base
 */
namespace Evas\Base;

use Evas\Base\MessageReplacerTrait;

/**
 * Класс-хепер для подстановок значений переменных в сообщения.
 * на место :var подставляет значение переменной var
 * на место <текст :var> подставляет текст + переменную var, только при наличии значения var
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class MessageVarsReplacer
{
    use MessageVarsReplacerTrait;
}
