<?php
/**
 * Awesomesauce block registration and render callback.
 *
 * MIGRATED: Converted from Elementor Widget_Base subclass to native WordPress
 * block registered via register_block_type(). The Elementor widget class,
 * controls (_register_controls), and Backbone JS template (_content_template)
 * have been removed. All three controls are preserved as block attributes:
 *   - title       (Controls_Manager::TEXT)     -> attribute type "string"
 *   - description (Controls_Manager::TEXTAREA) -> attribute type "string"
 *   - content     (Controls_Manager::WYSIWYG)  -> attribute type "string" (rich HTML)
 *
 * @category   Block
 * @package    ElementorAwesomesauce
 * @subpackage WordPress
 * @author     Ben Marshall <me@benmarshall.me>
 * @copyright  2020 Ben Marshall
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       https://www.benmarshall.me/build-custom-elementor-widgets/
 * @since      2.0.0
 * php version 7.3.9
 */

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

// ---------------------------------------------------------------------------
// 1. Stylesheet registration
//    MIGRATED: wp_register_style() call moved out of the old Widget_Base
//    constructor. The handle 'awesomesauce' is preserved so existing CSS
//    continues to apply without changes to the stylesheet itself.
// ---------------------------------------------------------------------------
add_action(
	'init',
	function () {
		wp_register_style(
			'awesomesauce',
			plugins_url( '/assets/css/awesomesauce.css', ELEMENTOR_AWESOMESAUCE ),
			array(),
			'1.0.0'
		);
	}
);

// ---------------------------------------------------------------------------
// 2. Block render callback
//    MIGRATED: Replaces Awesomesauce::render() and Awesomesauce::_content_template().
//
//    Attribute mapping from Elementor controls:
//      Controls_Manager::TEXT     -> $attributes['title']       (plain text, esc_html)
//      Controls_Manager::TEXTAREA -> $attributes['description'] (plain text, esc_html)
//      Controls_Manager::WYSIWYG  -> $attributes['content']     (rich HTML, wp_kses_post)
//
//    The wrapper <div> carries the block's CSS class so the existing
//    awesomesauce.css selector targeting can remain unchanged.
// ---------------------------------------------------------------------------

/**
 * Render callback for the awesomesauce/awesomesauce block.
 *
 * @since 2.0.0
 *
 * @param array  $attributes Block attributes supplied by the editor.
 * @param string $content    Inner block content (unused; rich HTML is stored
 *                           in the 'content' attribute as a WYSIWYG field).
 * @return string Rendered HTML output.
 */
function awesomesauce_block_render( $attributes, $content ) {
	// Enqueue the stylesheet on the frontend when this block is rendered.
	// MIGRATED: Previously enqueued via get_style_depends() on the widget.
	wp_enqueue_style( 'awesomesauce' );

	$title       = isset( $attributes['title'] )       ? $attributes['title']       : __( 'Title', 'elementor-awesomesauce' );
	$description = isset( $attributes['description'] ) ? $attributes['description'] : __( 'Description', 'elementor-awesomesauce' );
	$rich_content = isset( $attributes['content'] )    ? $attributes['content']     : __( 'Content', 'elementor-awesomesauce' );

	// Build wrapper class list including any extra classes added in the editor.
	// MIGRATED: Replaces Elementor's get_render_attribute_string() mechanism.
	$wrapper_classes = 'wp-block-awesomesauce-awesomesauce';
	if ( ! empty( $attributes['className'] ) ) {
		$wrapper_classes .= ' ' . esc_attr( $attributes['className'] );
	}

	ob_start();
	?>
	<div class="<?php echo $wrapper_classes; ?>">
		<h2 class="awesomesauce-title"><?php echo esc_html( $title ); ?></h2>
		<div class="awesomesauce-description"><?php echo esc_html( $description ); ?></div>
		<div class="awesomesauce-content"><?php echo wp_kses_post( $rich_content ); ?></div>
	</div>
	<?php
	return ob_get_clean();
}

// ---------------------------------------------------------------------------
// 3. Block registration
//    MIGRATED: Replaces Plugin::instance()->widgets_manager->register( new Awesomesauce() )
//    and the entire Widget_Base subclass.
//
//    Attribute schema derived from Elementor control types:
//      Controls_Manager::TEXT     -> type "string"
//      Controls_Manager::TEXTAREA -> type "string"
//      Controls_Manager::WYSIWYG  -> type "string" (stores serialised HTML;
//                                    treat as inner content for rich editing)
//
//    The block is placed in the 'text' category, matching the original
//    'general' Elementor category as the closest core equivalent.
//
// TODO: Manual review — Create a companion block.json file at
//       blocks/awesomesauce/block.json and a block.js editor script to
//       provide a rich editing experience (RichText controls) for the
//       title, description, and WYSIWYG content fields inside the Block
//       Editor. The render_callback here handles the frontend output, but
//       a full client-side edit() function is needed for live editor
//       previews to replace the removed Backbone _content_template().
// ---------------------------------------------------------------------------
add_action(
	'init',
	function () {
		register_block_type(
			'awesomesauce/awesomesauce',
			array(
				// MIGRATED: 'fa fa-pencil' icon from get_icon() has no direct
				// register_block_type() equivalent; set the icon in block.json
				// or in the JS block definition using the 'icon' property.

				'render_callback' => 'awesomesauce_block_render',

				// MIGRATED: get_style_depends() -> style handle enqueued inside
				// render callback above so it only loads when the block is present.

				'attributes'      => array(

					// Controls_Manager::TEXT -> type "string"
					'title'       => array(
						'type'    => 'string',
						'default' => 'Title',
					),

					// Controls_Manager::TEXTAREA -> type "string"
					'description' => array(
						'type'    => 'string',
						'default' => 'Description',
					),

					// Controls_Manager::WYSIWYG -> type "string" (rich HTML)
					// TODO: Manual review — In the JS edit() function, bind this
					// attribute to a <RichText> component with multiline support
					// so authors get the full WYSIWYG editing experience that
					// Elementor's Controls_Manager::WYSIWYG provided.
					'content'     => array(
						'type'    => 'string',
						'default' => 'Content',
					),

					// Core block supports: extra CSS class(es) added in the editor.
					'className'   => array(
						'type'    => 'string',
						'default' => '',
					),
				),

				// Enable standard block supports so authors can use colour /
				// typography / spacing controls from the Block Editor sidebar
				// without additional code.
				'supports'        => array(
					'html'       => false,
					'className'  => true,
					'color'      => array(
						'text'       => true,
						'background' => true,
					),
					'typography' => array(
						'fontSize' => true,
					),
					'spacing'    => array(
						'margin'  => true,
						'padding' => true,
					),
				),
			)
		);
	}
);