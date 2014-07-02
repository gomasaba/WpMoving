<?php
/**
 * 定数の設定
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
/**
 * wp-config.phpのパス
 */
if (!defined('WP_CONFIG')) {
    define('WP_CONFIG',__DIR__ . '/../blog/wp-config.php');
}

/**
 * ドキュメントルート
 */
if (!defined('DOC_ROOT')) {
    define('DOC_ROOT', __DIR__ . '/../');
}

/**
 * 画像保存先
 */
if (!defined('IMAGE_DIR')) {
    define('IMAGE_DIR', __DIR__ . '/../blog/wp-content/uploads');
}

