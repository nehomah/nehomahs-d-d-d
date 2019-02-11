<?php
namespace Edu\Cnm\DateDan\Test;
require_once("test.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;

/**
 * answer class is where the answers to users questions based on Dan's interests appear. They will be graded on their
 * answers to those questions.
 **/

class answer implements \JsonSerializable {
	use ValidateUuid;

	/**Id for answers user will be graded by, this is the primary key
	@var string|Uuid $answerUserId
	 **/

	private $answerUserId;
	/**
	 * Id to link answer to user, this is a foreign key
	 * @var string|Uuid $answerQuestionId
	 **/
	private $answerQuestionId;
	/**
	 * Space where the answer to the question appears
	 * @var string|Uuid $answerResult
	 **/
	private $answerResult;
	/**
	 *Actual score for user based on answers
	 **/
	private $answerScore;
}
/**


/*******Constructor for answer class************
 *
 *@param string|Uuid $newAnswerUserId id for new answers linked to user
 *@param string $newAnswerQuestionId id for new answers linked to questions
 *@param string $newAnswerResult id for result of answer from user
 *@param TINYINT $newAnswerScore value that gets calculated from answers to questions
 *@throws \InvalidArgumentException if data types are not valid
 *@throws \RangeException if data values are out of bounds
 *@throws \Exception for when an exception is thrown
 *@throws \TypeError if data types violate type hints
 *
 ************************************************************/