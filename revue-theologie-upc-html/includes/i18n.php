<?php
/**
 * Internationalisation — langue courante et traduction.
 * Langues supportées : fr, en, ln (lingala).
 */

const LANG_SUPPORTED = ['fr', 'en', 'ln'];
const LANG_DEFAULT = 'fr';

/**
 * Retourne le code de la langue courante (fr, en, ln).
 */
function current_lang(): string {
    $lang = $_SESSION['lang'] ?? LANG_DEFAULT;
    return in_array($lang, LANG_SUPPORTED, true) ? $lang : LANG_DEFAULT;
}

/**
 * Définit la langue en session. Retourne true si la langue est supportée.
 */
function set_lang(string $code): bool {
    $code = strtolower($code);
    if (!in_array($code, LANG_SUPPORTED, true)) {
        return false;
    }
    $_SESSION['lang'] = $code;
    return true;
}

/** @var array|null Cache des traductions chargées */
$GLOBALS['_i18n_translations'] = null;

/**
 * Charge le fichier de traduction pour la langue courante.
 */
function _i18n_load(): array {
    if ($GLOBALS['_i18n_translations'] !== null) {
        return $GLOBALS['_i18n_translations'];
    }
    $lang = current_lang();
    $file = BASE_PATH . '/lang/' . $lang . '.php';
    $GLOBALS['_i18n_translations'] = is_file($file) ? (require $file) : [];
    return $GLOBALS['_i18n_translations'];
}

/**
 * Traduit une clé. Si la clé n'existe pas, retourne la clé.
 */
function __(string $key): string {
    $t = _i18n_load();
    return $t[$key] ?? $key;
}
