<?php
/**
 * Вспомогательные функции.
 * @package evas-php\evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */

use Evas\Base\Env;
use Evas\Base\Help\PhpHelp;
use Evas\Base\Loader;

/**
 * Получение значения ENV свойства или значения по умолчанию.
 * @param string имя свойства
 * @param mixed|null значение по умолчанию
 */
function env(string $name, $default = null) {
    return Env::get($name, $default);
}

/**
 * Проверка на запуск из консоли.
 */
 function isCli(): bool {
    return PhpHelp::isCli();
}

/**
 * @todo Это временный вариант. 
 * @todo Сделать дополнительные тесты и оптимизировать в будущем.
 * 
 * Вывод debug информации
 * @param mixed сообщение или сообщения
 */
function evasDebug(...$messages) {
    // if (!defined('EVAS_DEBUG') || EVAS_DEBUG != true) return;
    $traces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
    // foreach ($traces as $i => $trace) {
    //     echo $i . ' => '; print_r($trace); echo $eol;
    // }
    // echo $eol;

    $trace = $traces[0] ?? null;
    // if (!$trace 
    //     || (isset($trace['function']) && $trace['function'] !== 'evasDebug')
    // ) {
    //     $trace = $traces[0];
    // }

    // определение неймспейса пакета Evas
    if (isset($trace['file'])) {
        $names = ['EVAS_DEBUG'];
        $evasDir = Loader::getEvasDir();
        $file = $trace['file'];
        if (mb_strpos($file, $evasDir) === 0) {
            $file = mb_substr($file, mb_strlen($evasDir) + 1);
            $parts = explode(DIRECTORY_SEPARATOR, $file);
            $file = array_shift($parts);
            $parts = explode('-', $file);
            foreach ($parts as &$name) {
                $name = ucfirst($name);
            }
            $info = implode('\\', $parts) . ' debug';
            if (isset($parts[1])) {
                $level = strtoupper($parts[1]);
                $names[] = "EVAS_DEBUG_{$level}";
            }
            $finded = false;
            foreach ($names as $name) {
                if (!defined($name)) continue; 
                if (constant($name) == true) {
                    $finded = true;
                    break;
                }
            }
            if (!$finded) return;
        } else {
            $info = null;
        }
    }

    $eol = isCli() ? "\n" : "<br>";
    $deol = isCli() ? "\n\n" : "<hr>";
    foreach ($messages as &$message) {
        if (is_object($message) || is_array($message) || is_callable($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        }
    }
    $messages = implode($eol, $messages);

    $call = sprintf('Called in %s on line %s', $trace['file'], $trace['line']);
    if (isCli()) {
        $call = "\e[36m{$call}\e[0m";
    } else {
        $call = "<span style=\"color: #008080;\">$call</span>";
    }

    if (isCli()) {
        $color = "\e[35m";
        if (!$info) { 
            $info = 'Unknown Evas debug';
            $color = "\e[31m";
        }
        $info = "{$color}{$info}:\e[0m";
        $result = $info . ' ' . $messages . $eol . $call . $deol;
    } else {
        $color = "#800080";
        if (!$info) { 
            $info = 'Unknown Evas debug';
            $color = "#880000";
        }
        $info = "<span style=\"color: $color;\">$info:</span>";
        $result = "<div class=\"evasDebug\" style=\"font-family: Arial;\">"
        . $info . ' ' . $messages . $eol . $call . $deol
        . '</div>';
    }
    echo $result;
}
