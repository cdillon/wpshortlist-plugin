<?php
/**
 * Default filter template.
 *
 * @package wpshortlist
 */

/*
HTML Structure:

<form id="wpshortlist-form" class="wpshortlist-form">
	<div class="wpshortlist-filterset">
		<div class="wpshortlist-filter">
			<fieldset>
				<legend>
					<h3 class="wpshortlist-filter-heading">...</h3>
				</legend>
				<ul class="wpshortlist-filter-list">
					<li class="wpshortlist-filter-list-item">
						<input type="radio" ...>
						<label>...</label>
					</li>
				</ul>
			</fieldset>
		</div><!-- .wpshortlist-filter -->
	</div><!-- .wpshortlist-filterset -->
</form>
*/

?>
<form id="wpshortlist-form" class="wpshortlist-form">
	<?php
	foreach ( $config as $filter_set ) :
		// Is this filter set for this term?
		if ( ! is_tax( $filter_set['taxonomy'], $filter_set['term'] ) ) {
			continue;
		}
		?>
	<div class="wpshortlist-filterset">
		<?php
		// Print filter.
		foreach ( $filter_set['filters'] as $filter ) :
			?>
		<div class="wpshortlist-filter">
			<fieldset>
				<legend>
					<h3 class="wpshortlist-filter-heading">
						<?php echo esc_html( $filter['name'] ); ?>
					</h3>
				</legend>
				<ul class="wpshortlist-filter-list">
					<?php
					foreach ( $filter['options'] as $option_id => $option_name ) :

						// Build a unique ID like 'supports-display-term-list-tags'.
						$unique_id = $filter['query_var'] . '-' . $option_id;

						$checked = isset( $current_args[ $filter['query_var'] ] )
							&& $option_id === $current_args[ $filter['query_var'] ];

						?>
					<li class="wpshortlist-filter-list-item">
						<input type="radio"
								id="<?php echo esc_attr( $unique_id ); ?>"
								name="<?php echo esc_attr( $filter['id'] ); ?>"
								value="<?php echo esc_attr( $option_id ); ?>"
								title="<?php echo esc_attr( $option_id ); ?>"
								<?php checked( $checked ); ?> />
						<label for="<?php echo esc_attr( $unique_id ); ?>">
							<?php echo esc_html( $option_name ); ?>
						</label>
					</li>
					
					<?php endforeach; /* filter options */ ?>
				</ul>
			</fieldset>
		</div><!-- .wpshortlist-filter -->
		<?php endforeach; ?>
	</div><!-- .wpshortlist-filterset -->
	<?php endforeach; ?>
</form>
