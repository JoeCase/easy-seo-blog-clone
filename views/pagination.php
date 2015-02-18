<?php
global $url, $current_page, $options;

$posts_count = intval(numberOfposts(null, array_merge($url, array("postdate" => date("Y-m-d  H:i:s")))));
if ($posts_count == 0) {
	$posts_count = intval(numberOfposts("demo", array_merge($url, array("postdate" => date("Y-m-d  H:i:s")))));
}
$pages = ceil($posts_count / $options['posts_per_page']);
if ($pages > 0) {
	$subUrl = "";
	foreach ($url as $k => $v) {
		$subUrl .= $k . "/" . $v . "/";
	}
	$prewPage = max(1, $current_page - 1);
	$nextPage = min($pages, $current_page + 1);

	?>
	<?php if ($prewPage != $current_page) : ?>
		<div>
		<?php if (isset($options['paginationShowFirstLast']) && $options['paginationShowFirstLast']) : ?>
			<span class="pagelink first">
				<a target="_top" href="<?php echo $GLOBALS['section-url'].$subUrl; ?>"><?php echo isset($options['firstPage']) && $options['firstPage'] ? $options['firstPage'] : __('firstPage'); ?></a>
			</span>
		<?php endif; ?>
		<?php if (isset($options['paginationShowNextPrev']) && $options['paginationShowNextPrev']): ?>
			<span class="pagelink previous">
				<a target="_top" href="<?php echo $GLOBALS['section-url'].$subUrl.($prewPage != 1 ? 'page/'.$prewPage : ''); ?>"><?php echo isset($options['prevPage']) && $options['prevPage'] ? $options['prevPage'] : __('prevPage'); ?></a>
			</span>
		<?php endif; ?>
		</div>
	<?php else: ?>
		<?php if (isset($options['paginationShowFirstLast']) && $options['paginationShowFirstLast']) : ?>
			<span class="pagelink first hidden">
				<?php echo isset($options['firstPage']) && $options['firstPage'] ? $options['firstPage'] : __('firstPage'); ?>
			</span>
		<?php endif; ?>
		<?php if (isset($options['paginationShowNextPrev']) && $options['paginationShowNextPrev']): ?>
			<span class="pagelink previous hidden">
				<?php echo isset($options['prevPage']) && $options['prevPage'] ? $options['prevPage'] : __('prevPage'); ?>
			</span>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ($nextPage != $current_page) : ?>
		<div>
		<?php if (isset($options['paginationShowNextPrev']) && $options['paginationShowNextPrev']): ?>
			<span class="pagelink next">
				<a target="_top" href="<?php echo $GLOBALS['section-url'].$subUrl.'page/'.$nextPage; ?>"><?php echo isset($options['nextPage']) && $options['nextPage'] ? $options['nextPage'] : __('nextPage'); ?></a>
			</span>
		<?php endif; ?>
		<?php if (isset($options['paginationShowFirstLast']) && $options['paginationShowFirstLast']): ?>
			<span class="pagelink last">
				<a target="_top" href="<?php echo $GLOBALS['section-url'].$subUrl.'page/'.$pages; ?>"><?php echo isset($options['lastPage']) && $options['lastPage'] ? $options['lastPage'] : __('lastPage'); ?></a>
			</span>
		<?php endif; ?>
		</div>
	<?php else: ?>
		<?php if (isset($options['paginationShowNextPrev']) && $options['paginationShowNextPrev']): ?>
			<span class="pagelink next hidden">
				<?php echo isset($options['nextPage']) && $options['nextPage'] ? $options['nextPage'] : __('nextPage'); ?>
			</span>
		<?php endif; ?>
		<?php if (isset($options['paginationShowFirstLast']) && $options['paginationShowFirstLast']): ?>
			<span class="pagelink last hidden">
				<?php echo isset($options['lastPage']) && $options['lastPage'] ? $options['lastPage'] : __('lastPage'); ?>
			</span>
		<?php endif; ?>
	<?php endif; ?>
	<?php if (isset($options['paginationShowNumbers']) && $options['paginationShowNumbers']) : ?>
		<?php
		$numbersCount = isset($options['paginationNumbersCount']) ? $options['paginationNumbersCount'] : 7;
		$pagesFrom = max(1, $current_page - floor($numbersCount / 2));
		$pagesTo = min($pages, $current_page + floor($numbersCount / 2));
		if (($remaining = ($numbersCount - 1) - ($pagesTo - $pagesFrom)) > 0) {
			if ($pagesFrom != 1) {
				$pagesFrom = max(1, $pagesFrom - $remaining);
			} else {
				$pagesTo = min($pages, $pagesTo + $remaining);
			}
		}
		?>
		<?php if ($pagesFrom != $pagesTo) :
			?>
			<p id="page-numbers">
				<?php
				for ($i = $pagesFrom; $i <= $pagesTo; $i++) : ?>
					<span class="pagelink <?php echo $i == $current_page ? 'current' : ''; ?>">
				<?php if ($i == 1) : ?>
					<a target="_top" href="<?php echo $GLOBALS['section-url'] . $subUrl; ?>">1</a>
				<?php else : ?>
					<a target="_top" href="<?php echo $GLOBALS['section-url'].$subUrl.'page/'.$i; ?>"><?php echo $i; ?></a>
				<?php endif; ?>
			</span>
				<?php endfor;
				?>
			</p>
		<?php
		endif; ?>
	<?php endif; ?>
<?php } ?>




