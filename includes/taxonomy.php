<?php
// Register Custom Taxonomy
add_action('init', 'cryz_lang_register_taxonomy', 5);
function cryz_lang_register_taxonomy()
{

	$labels = array(
		'name'                       => _x('Languages', 'Taxonomy General Name', 'cryz_lang'),
		'singular_name'              => _x('language', 'Taxonomy Singular Name', 'cryz_lang'),
		'menu_name'                  => __('Languages', 'cryz_lang'),
		'all_items'                  => __('All Languages', 'cryz_lang'),
		'parent_item'                => __('Language Archives', 'cryz_lang'),
		'parent_item_colon'          => __('Parent Language:', 'cryz_lang'),
		'new_item_name'              => __('New Language Name', 'cryz_lang'),
		'add_new_item'               => __('Add New Language', 'cryz_lang'),
		'edit_item'                  => __('Edit language', 'cryz_lang'),
		'update_item'                => __('Update Language', 'cryz_lang'),
		'view_item'                  => __('View Language', 'cryz_lang'),
		'separate_items_with_commas' => __('Separate Items with commas', 'cryz_lang'),
		'add_or_remove_items'        => __('Add or remove languages', 'cryz_lang'),
		'choose_from_most_used'      => __('Choose from the most used', 'cryz_lang'),
		'popular_items'              => __('Popular Languages', 'cryz_lang'),
		'search_items'               => __('Search Languages', 'cryz_lang'),
		'not_found'                  => __('Not Found', 'cryz_lang'),
		'no_terms'                   => __('No language', 'cryz_lang'),
		'items_list'                 => __('Languages list', 'cryz_lang'),
		'items_list_navigation'      => __('Languages list navigation', 'cryz_lang'),
	);

	$rewrite_options = array(
		'slug'         => 'lang',
		'with_front'   => true,
		'hierarchical' => false,
	);

	$args = array(
		'labels'                => $labels,
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'show_in_nav_menus'     => true,
		'show_tagcloud'         => true,
		'show_in_rest'          => false,
		'rewrite'               => $rewrite_options,
		'query_var'				=> 'cryz_lang',
		'show_in_rest'			=> true,
		'rest_base'				=> 'cryz_lang',
		'meta_box_cb'			=> false,
		'show_in_quick_edit'    => false,
	);
	register_taxonomy('cryz_lang', array('post', 'page'), $args);
	flush_rewrite_rules();
}

add_action('cryz_lang_add_form_fields', 'cryz_lang_add_form_fields');
function cryz_lang_add_form_fields($taxonomy)
{
?>
	<div class="form-field">
		<label for="locale">Language</label>
		<?php
		wp_dropdown_languages([
			'explicit_option_en_us' => true
		]);
		?>
		<p class="description">The language to use.</p>
	</div>
<?php
}

add_action('cryz_lang_edit_form_fields', 'cryz_lang_edit_form_fields', 10, 2);
function cryz_lang_edit_form_fields($term, $taxonomy)
{
	$locale = get_term_meta($term->term_id, 'locale', true);
?>
	<tr class="form-field">
		<th><label for="locale">Language</label></th>
		<td>
			<?php
			wp_dropdown_languages([
				'selected' => $locale
			])
			?>
			<p class="description">The language to use.
			<p>
		</td>
	</tr>
<?php
}

add_action('created_cryz_lang', 'cryz_lang_save_form_fields');
add_action('edit_cryz_lang', 'cryz_lang_save_form_fields');
function cryz_lang_save_form_fields($term_id)
{
	update_term_meta(
		$term_id,
		'locale',
		sanitize_text_field($_POST['locale'])
	);
}
