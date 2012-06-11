<?php

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

require_once JPATH_SITE.'/components/com_continued/router.php';

class plgSearchContinued extends JPlugin
{
	function onContentSearchAreas()
	{
		static $areas = array(
			'continued' => 'Content'
			);
			return $areas;
	}

	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$db		= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$tag = JFactory::getLanguage()->getTag();

		require_once JPATH_SITE.'/administrator/components/com_search/helpers/search.php';

		$searchText = $text;
		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}

		$limit			= $this->params->def('search_limit',		50);

		$nullDate		= $db->getNullDate();
		$date = JFactory::getDate();
		$now = $date->toMySQL();

		$text = trim($text);
		if ($text == '') {
			return array();
		}

		$wheres = array();
		switch ($phrase) {
			case 'exact':
				$text		= $db->Quote('%'.$db->getEscaped($text, true).'%', false);
				$wheres2	= array();
				$wheres2[]	= 'a.course_name LIKE '.$text;
				$wheres2[]	= 'a.course_keywords LIKE '.$text;
				$wheres2[]	= 'a.course_desc LIKE '.$text;
				$where		= '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();
				foreach ($words as $word) {
					$word		= $db->Quote('%'.$db->getEscaped($word, true).'%', false);
					$wheres2	= array();
					$wheres2[]	= 'a.course_name LIKE '.$word;
					$wheres2[]	= 'a.course_keywords LIKE '.$word;
					$wheres2[]	= 'a.course_desc LIKE '.$word;
					$wheres[]	= implode(' OR ', $wheres2);
				}
				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		$morder = '';
		switch ($ordering) {
			case 'oldest':
				$order = 'a.course_published ASC';
				break;

			case 'popular':
				$order = '';
				break;

			case 'alpha':
				$order = 'a.course_name ASC';
				break;

			case 'category':
				$order = 'c.cat_name ASC, a.course_name ASC';
				$morder = 'a.course_name ASC';
				break;

			case 'newest':
			default:
				$order = 'a.course_enddate DESC';
				break;
		}

		$rows = array();
		$query	= $db->getQuery(true);

		// search courses
		if ($limit > 0)
		{
			$query->clear();
			$query->select('a.course_name AS title, a.course_desc as metadesc, a.course_keywords as metakey, a.course_startdate AS created, '
						.'a.course_desc AS text, c.cat_name AS section, '
						.'a.course_id as slug, '
						.'c.cat_id as catslug, '
						.'"2" AS browsernav');
			$query->from('#__ce_courses AS a');
			$query->innerJoin('#__ce_cats AS c ON c.cat_id=a.course_cat');
			$query->where('('. $where .')' . 'AND a.published >= 1 AND c.published >= 1 AND a.access IN ('.$groups.') '
						.'AND a.course_searchable = 1 AND a.course_enddate >= NOW()');
			$query->group('a.course_id');
			$query->order($order);

			$db->setQuery($query, 0, $limit);
			$list = $db->loadObjectList();
			$limit -= count($list);

			if (isset($list))
			{
				foreach($list as $key => $item)
				{
					$list[$key]->href = JRoute::_("index.php?option=com_continued&view=course&course=".$item->slug);
				}
			}
			$rows[] = $list;
		}

		$results = array();
		if (count($rows))
		{
			foreach($rows as $row)
			{
				$new_row = array();
				foreach($row as $key => $article) {
					if (searchHelper::checkNoHTML($article, $searchText, array('text', 'title', 'metadesc', 'metakey'))) {
						$new_row[] = $article;
					}
				}
				$results = array_merge($results, (array) $new_row);
			}
		}

		return $results;
	}
}
