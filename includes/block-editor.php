<?php


add_action( 'enqueue_block_editor_assets', 'cryz_lang_meta_box_enqueue' );
function cryz_lang_meta_box_enqueue() {
    $asset_file = include( plugin_dir_path( __DIR__ ) . 'build/main.asset.php');
    wp_enqueue_script(
        'cryz-lang-meta-box',
        plugins_url( 'build/main.js', __DIR__ ),
        $asset_file['dependencies'],
        $asset_file['version']
    );
}
