<?php
/**
 * Jeopardy class
 * Created: 10/24/2014 - Scott Carpenter
 * scott@payforstay.com
 */

class jeopardy
{
	const STATE_CLOSED   = 1;
	const STATE_QUESTION = 2;
	const STATE_ANSWERED = 3;

	public function __construct()
	{
		mysql_connect('127.0.0.1', 'jeopardy', 'jeopardy');
		mysql_select_db('jeopardy');
	}

	public function getCategories()
	{
		$result = mysql_query('
			SELECT
				*
			FROM
				categories
			ORDER BY
				categoryID
		');

		$categories = array();

		while ($row = mysql_fetch_assoc($result))
		{
			$categories[$row['categoryID']] = $row['category'];
		}

		return $categories;
	} // end public function getCategories

	public function getQuestions()
	{
		$result = mysql_query('
			SELECT
				*
			FROM
				questions
			ORDER BY
				questionID
		');

		$questions = array();

		while ($row = mysql_fetch_assoc($result))
		{
			$questions[$row['questionID']] = $row;
		}

		return $questions;
	} // end public function getQuestions

	public function updateQuestionState($questionID, $state)
	{
		$sql = "
			UPDATE
				questions
			SET
				state = $state
			WHERE
				questionID = $questionID
		";
		mysql_query($sql);
	}

	public function resetQuestions()
	{
		$sql = "
			UPDATE
				questions
			SET
				state = " . jeopardy::STATE_CLOSED
		;
		mysql_query($sql);
	}
} // end class jeopardy

/* END OF FILE */
/* Location: ./jeopardy.inc */