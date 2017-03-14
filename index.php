<?php
/**
 * Halloween Jeopardy
 * Created: 10/24/2014 - Scott Carpenter
 * scott@payforstay.com
 */

require('jeopardy.inc');

$jeo = new jeopardy();

if (isset($_GET['reset']))
{
	$jeo->resetQuestions();
}

$highlightID = 0;

if (isset($_GET['open_questionID']))
{
	$highlightID = $_GET['open_questionID'];
	$set_state = jeopardy::STATE_QUESTION;
}
elseif (isset($_GET['show_answerID']))
{
	$highlightID = $_GET['show_answerID'];
	$set_state = jeopardy::STATE_ANSWERED;
}

if ($highlightID)
{
	$jeo->updateQuestionState($highlightID, $set_state);
}

$categories = $jeo->getCategories();
$questions = $jeo->getQuestions();

?>
<style type="text/css">
	/* jeopardy blue: #060ce9 */

	body {
		background: black;
		color: white;
		font-family: verdana;
		text-align: center;
		overflow: hidden;
	}

	table.jeopardy {
		border-collapse: collapse;
		margin: 0 auto;
		text-transform: uppercase;
	}

	table.jeopardy tr td,
	table.jeopardy tr th {
		width: 190px;
		height: 135px;
		border: solid 4px #000;
		text-align: center;
		vertical-align: middle;
	}

	tr.categories th {
		height: 50px !important;
		background-color: #777;
		color: white;
		letter-spacing: 1px;
		font-size: 1.3em;
	}

	tr.questions td {
		background-color: #060ce9;
	}

	td.current {
		border: solid 4px gold;
	}

	a {
		color: white;
		text-decoration: none;
	}

	a.value {
		font-size: 35px;
		font-weight: bold;
		color: gold;
	}

	a.show-answer {
		font-size: 0.9em;
	}

	div.question {
		font-size: 1.1em;
		margin-bottom: 10px;
	}

	div.answer {
		font-size: 1.2em;
		font-style: italic;
	}

	a.reset {
		color: #0a0a0a;
	}
</style>

<table class="jeopardy">
	<tr class="categories">
		<?php

		for ($categoryID=1; $categoryID<=5; $categoryID++)
		{
			?>
			<th>
				<?php echo $categories[$categoryID];?>
			</th>
			<?php
		}

		?>
	</tr>
	<?php

	for ($row=1; $row<=5; $row++)
	{
		?>
		<tr class="questions">
			<?php

			for ($col=1; $col<=5; $col++)
			{
				$questionID = (($row - 1) * 5) + $col;
				$question = $questions[$questionID]['question'];
				$answer = $questions[$questionID]['answer'];
				$state = $questions[$questionID]['state'];
				$value = ($row * 100);

// 				$state = jeopardy::STATE_QUESTION;

				?>
				<td class="<?php echo ($questionID==$highlightID ? 'current' : ($state==jeopardy::STATE_CLOSED ? 'closed' : 'opened'));?>">
					<?php

					if ($state == jeopardy::STATE_CLOSED)
					{
						?>
						<a class="value" href="?open_questionID=<?php echo $questionID;?>">$<?php echo $value;?></a>
						<?php
					}
					elseif ($state == jeopardy::STATE_QUESTION)
					{
						?>
						<div class="question">
							<a href="?show_answerID=<?php echo $questionID;?>"><?php echo $question;?></a>
						</div>
						<!--a class="show-answer" href="?show_answerID=<?php echo $questionID;?>" title="<?php echo $answer;?>">Show Answer</a-->
						<?php
					}
					elseif ($state == jeopardy::STATE_ANSWERED && $questionID == $highlightID)
					{
						?>
						<div class="answer" title="<?php echo $question;?>"><?php echo $answer;?></div>
						<?php
					}

					?>
				</td>
				<?php
			} // for $col=1..5

			?>
		</tr>
		<?php
	} // for $row=1..5

	?>
</table>
<a class="reset" href="?reset">Reset Board</a>
