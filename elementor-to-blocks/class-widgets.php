<?php
/**
 * Widgets class.
 *
 * @category   Class
 * @package    ElementorAwesomesauce
 * @subpackage WordPress
 * @author     Ben Marshall <me@benmarshall.me>
 * @copyright  2020 Ben Marshall
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       link(https://www.benmarshall.me/build-custom-elementor-widgets/,
 *             Build Custom Elementor Widgets)
 * @since      1.0.0
 * php version 7.3.9
 */

namespace ElementorAwesomesauce;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * Class Widgets
 *
 * Main Widgets class — MIGRATED: replaced Elementor widget registration
 * with native WordPress block registration via register_block_type().
 *
 * @since 1.0.0
 */
class Widgets {

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Widgets The single instance of the class.
	 */
	private static $instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return Widgets An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Include Block files
	 *
	 * Load block registration files.
	 * MIGRATED: was include_widgets_files() loading from widgets/ directory.
	 * Now loads from blocks/ directory structure.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function include_block_files() {
		// MIGRATED: was require_once 'widgets/class-awesomesauce.php'
		// Now loads block registration from the blocks/ directory.
		require_once 'blocks/awesomesauce/index.php';
	}

	/**
	 * Register Blocks
	 *
	 * Register new WordPress core blocks.
	 * MIGRATED: was register_widgets() calling Elementor\Plugin::instance()->widgets_manager->register_widget_type().
	 * Now calls register_block_type() for each block on the init hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_blocks() {
		// It's now safe to include block files.
		$this->include_block_files();

		// MIGRATED: was \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Awesomesauce() )
		// Block registration is now handled inside each block's index.php via register_block_type().
		// If the block ships a block.json file, registration below is preferred:
		$block_dir = plugin_dir_path( __FILE__ ) . 'blocks/awesomesauce';
		if ( file_exists( $block_dir . '/block.json' ) ) {
			register_block_type( $block_dir );
		}

		// TODO: Manual review — if the awesomesauce block does not yet have a
		// block.json, ensure blocks/awesomesauce/index.php calls register_block_type()
		// directly with a 'render_callback' and 'attributes' array. See inline
		// block registration example in this file below.
	}

	/**
	 * Plugin class constructor
	 *
	 * Register plugin action hooks and filters.
	 * MIGRATED: replaced 'elementor/widgets/widgets_registered' hook with
	 * WordPress core 'init' hook for block registration.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		// MIGRATED: was add_action( 'elementor/widgets/widgets_registered', ... )
		// WordPress blocks must be registered on the 'init' hook.
		add_action( 'init', array( $this, 'register_blocks' ) );
	}
}

// Instantiate the Widgets class.
// MIGRATED: singleton instantiation preserved; no longer depends on Elementor being active.
Widgets::instance();

// ---------------------------------------------------------------------------
// Inline fallback block registration for the migrated "awesomesauce" block.
// MIGRATED: This replaces the Elementor Widget_Base subclass pattern.
// If a dedicated blocks/awesomesauce/index.php + block.json exist, this
// fallback is skipped automatically by the block registry (duplicate name).
// ---------------------------------------------------------------------------

/**
 * Render callback for the awesomesauce block.
 *
 * MIGRATED: was Widgets\Awesomesauce::render() — the former Elementor widget
 * render method that echoed output using $this->get_settings_for_display().
 * Now a plain function receiving $attributes (block attributes) and
 * $content (inner blocks HTML, if any).
 *
 * TODO: Manual review — port the full render logic from
 * widgets/class-awesomesauce.php into this callback (or into
 * blocks/awesomesauce/index.php). The placeholder below preserves the
 * block's place in the content without losing data.
 *
 * @param array  $attributes Block attributes.
 * @param string $content    Inner blocks HTML.
 * @return string Rendered HTML.
 */
function elementor_awesomesauce_block_render( array $attributes, string $content = '' ): string {
	// MIGRATED: Controls_Manager::TEXT -> string attribute 'title'
	$title = isset( $attributes['title'] ) ? esc_html( $attributes['title'] ) : '';

	// MIGRATED: Controls_Manager::TEXTAREA -> string attribute 'description'
	$description = isset( $attributes['description'] ) ? esc_textarea( $attributes['description'] ) : '';

	// MIGRATED: Controls_Manager::MEDIA -> object attribute 'image' (id + url)
	$image_url = isset( $attributes['image']['url'] ) ? esc_url( $attributes['image']['url'] ) : '';
	$image_id  = isset( $attributes['image']['id'] ) ? absint( $attributes['image']['id'] ) : 0;
	$image_alt = $image_id ? esc_attr( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ) : '';

	// MIGRATED: Controls_Manager::COLOR -> string attribute 'titleColor'
	// Inline style is used because theme.json palette mapping requires manual follow-up.
	$title_color = isset( $attributes['titleColor'] ) ? esc_attr( $attributes['titleColor'] ) : '';
	$title_style = $title_color ? ' style="color:' . $title_color . ';"' : '';

	// MIGRATED: Controls_Manager::SELECT -> string attribute 'headingLevel' (enum: h1–h6)
	$allowed_levels  = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
	$heading_level   = isset( $attributes['headingLevel'] ) && in_array( $attributes['headingLevel'], $allowed_levels, true )
		? $attributes['headingLevel']
		: 'h2';

	// MIGRATED: Controls_Manager::DIMENSIONS -> style.spacing.padding (inline style fallback)
	// TODO: Manual review — migrate padding values to block gap / theme.json spacing scale.
	$padding = '';
	if ( ! empty( $attributes['padding'] ) ) {
		$p       = $attributes['padding'];
		$top     = isset( $p['top'] )    ? esc_attr( $p['top'] )    : '0';
		$right   = isset( $p['right'] )  ? esc_attr( $p['right'] )  : '0';
		$bottom  = isset( $p['bottom'] ) ? esc_attr( $p['bottom'] ) : '0';
		$left    = isset( $p['left'] )   ? esc_attr( $p['left'] )   : '0';
		$unit    = isset( $p['unit'] )   ? esc_attr( $p['unit'] )   : 'px';
		$padding = sprintf( 'padding:%s%s %s%s %s%s %s%s;', $top, $unit, $right, $unit, $bottom, $unit, $left, $unit );
	}
	$wrapper_style = $padding ? ' style="' . $padding . '"' : '';

	// MIGRATED: Controls_Manager::WYSIWYG -> inner block content (passed as $content)
	$wysiwyg_content = ! empty( $content ) ? $content : '';

	ob_start();
	?>
	<div class="wp-block-elementor-awesomesauce-awesomesauce"<?php echo $wrapper_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( $image_url ) : ?>
			<figure class="wp-block-image">
				<img src="<?php echo $image_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
				     alt="<?php echo $image_alt; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
				     <?php if ( $image_id ) : ?>class="wp-image-<?php echo $image_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"<?php endif; ?>
				/>
			</figure>
		<?php endif; ?>

		<?php if ( $title ) : ?>
			<<?php echo $heading_level; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="wp-block-heading"<?php echo $title_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</<?php echo $heading_level; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php endif; ?>

		<?php if ( $description ) : ?>
			<p><?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>

		<?php if ( $wysiwyg_content ) : ?>
			<?php echo $wysiwyg_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Fallback registration for the awesomesauce block.
 *
 * MIGRATED: replaces \Elementor\Plugin::instance()->widgets_manager->register_widget_type(
 *     new Widgets\Awesomesauce()
 * ).
 *
 * Runs on 'init'. If a block.json already registered the block via
 * Widgets::register_blocks() above, this add_action is a no-op because
 * WordPress will skip duplicate block names.
 *
 * Attribute mapping:
 *   Controls_Manager::TEXT      -> title         (string)
 *   Controls_Manager::TEXTAREA  -> description   (string)
 *   Controls_Manager::WYSIWYG   -> (inner blocks / $content)
 *   Controls_Manager::NUMBER    -> (add per widget need, example: 'columns')
 *   Controls_Manager::SELECT    -> headingLevel   (string, enum)
 *   Controls_Manager::MEDIA     -> image          (object: id, url)
 *   Controls_Manager::COLOR     -> titleColor     (string)
 *   Controls_Manager::DIMENSIONS-> padding        (object: top, right, bottom, left, unit)
 */
add_action(
	'init',
	static function () {
		// Guard: skip if already registered via block.json in Widgets::register_blocks().
		if ( \WP_Block_Type_Registry::get_instance()->is_registered( 'elementor-awesomesauce/awesomesauce' ) ) {
			return;
		}

		// MIGRATED: register_block_type() replaces Widget_Base subclass pattern.
		register_block_type(
			'elementor-awesomesauce/awesomesauce',
			array(
				// MIGRATED: Widget_Base::render() -> render_callback function.
				'render_callback' => 'ElementorAwesomesauce\elementor_awesomesauce_block_render',

				// MIGRATED: Widget_Base::_register_controls() -> attributes array.
				'attributes'      => array(

					// MIGRATED: Controls_Manager::TEXT -> type "string"
					'title'        => array(
						'type'    => 'string',
						'default' => '',
					),

					// MIGRATED: Controls_Manager::TEXTAREA -> type "string"
					'description'  => array(
						'type'    => 'string',
						'default' => '',
					),

					// MIGRATED: Controls_Manager::SELECT -> type "string" with enum
					'headingLevel' => array(
						'type'    => 'string',
						'enum'    => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ),
						'default' => 'h2',
					),

					// MIGRATED: Controls_Manager::MEDIA -> type "object" (id + url)
					'image'        => array(
						'type'       => 'object',
						'default'    => array(),
						'properties' => array(
							'id'  => array( 'type' => 'integer' ),
							'url' => array( 'type' => 'string' ),
						),
					),

					// MIGRATED: Controls_Manager::COLOR -> type "string"
					'titleColor'   => array(
						'type'    => 'string',
						'default' => '',
					),

					// MIGRATED: Controls_Manager::DIMENSIONS -> type "object" (style.spacing.padding)
					// TODO: Manual review — consider migrating to block style.spacing.padding
					// via theme.json spacingScale for full Site Editor compatibility.
					'padding'      => array(
						'type'       => 'object',
						'default'    => array(),
						'properties' => array(
							'top'    => array( 'type' => 'string' ),
							'right'  => array( 'type' => 'string' ),
							'bottom' => array( 'type' => 'string' ),
							'left'   => array( 'type' => 'string' ),
							'unit'   => array( 'type' => 'string' ),
						),
					),

					// MIGRATED: Controls_Manager::NUMBER example attribute
					// Add additional number attributes here as needed.
					// 'columns' => array( 'type' => 'number', 'default' => 3 ),
				),

				// MIGRATED: widget category mapping.
				// Elementor categories (e.g. 'basic', 'general') -> block category.
				'category'        => 'widgets',

				// MIGRATED: widget supports — mirrors common Elementor section controls.
				'supports'        => array(
					'html'                    => true,
					'color'                   => array(
						'background' => true,
						'text'       => true,
					),
					'spacing'                 => array(
						'margin'  => true,
						'padding' => true,
					),
					'typography'              => true,
					'__experimentalBorder'    => array(
						'radius' => true,
						'width'  => true,
						'color'  => true,
						'style'  => true,
					),
				),
			)
		);
	},
	// Priority 11 ensures this runs after Widgets::register_blocks() at default priority 10.
	11
);