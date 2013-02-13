<?php

	namespace Habari;

class DraftDashModule extends Plugin
{
	private $theme;

	/**
	 * Add the block template to the theme
	 */
	function action_init()
	{
		$this->add_template( 'dashboard.block.draft_posts', __DIR__ . '/dashboard.block.draft_posts.php' );
	}

	/**
	 * Add the blocks this plugin provides to the list of available blocks
	 * @param array $block_list An array of block names, indexed by unique string identifiers
	 * @return array The altered array
	 */
	public function filter_block_list($block_list)
	{
		$block_list['draft_posts'] = _t( 'Latest Drafts');
		return $block_list;
	}
	
	/**
	 * Return a list of blocks that can be used for the dashboard
	 * @param array $block_list An array of block names, indexed by unique string identifiers
	 * @return array The altered array
	 */
	public function filter_dashboard_block_list($block_list)
	{
		return $this->filter_block_list($block_list);
	}
	
	/**
	 * Produce the content for the latest drafts block
	 * @param Block $block The block object
	 * @param Theme $theme The theme that the block will be output with
	 */
	public function action_block_content_draft_posts($block, $theme)
	{
		$block->recent_posts = Posts::get(array('status' => 'draft', 'limit' => 8, 'user_id' => User::identify()->id, 'orderby' => 'pubdate DESC'));
		if(User::identify()->can('manage_entries'))
		{
			$block->link = URL::get('admin', array('page' => 'posts', 'status' => Post::status('draft'), 'user_id' => User::identify()->id));
		}
	}

	/**
	 * action_update_check
	 * Register GUID for updates
	 */
	public function action_update_check()
	{
	 	Update::add( $this->info->name, $this->info->guid, $this->info->version );
	}
}
?>