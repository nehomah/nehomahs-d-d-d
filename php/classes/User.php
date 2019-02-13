<?php
namespace DeepDiveDatingApp\DeepDiveDating;
require_once ("autoload.php");
require_once (dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
/**
 * The user class will contain the identifying info for the account.
 */
class user implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * Id for the user's account, this is the primary key
	 * @var string | Uuid $userId
	 */
	private $userId;
	/**
	 *activation token for the user account
	 * @var string $userActivationToken
	 */
	private $userActivationToken;
	/**
	 * the browser and system info used by an account, recorded to help with the blocking/reporting process.
	 * @var string $userAgent
	 */
	private $userAgent;
	/**
	 * url for the user avatar
	 * @var string $userAvatarUrl
	 */
	private $userAvatarUrl;
	/**
	 *info on the blocked status on the account
	 * @var string $userBlocked
	 */
	private $userBlocked;
	/**
	 * email address associated with the user account; this is unique
	 * @var string $userEmail
	 */
	private $userEmail;
	/**
	 * handle for the user account, this is unique and should also be indexed
	 * @var string $userHandle
	 */
	private $userHandle;
	/**
	 * encrypted password for the user account
	 * @var string $userHash
	 */
	private $userHash;
	/**
	 * recorded ip address for the user account
	 * @var string userIpAddress
	 */
	private $userIpAddress;

	/**
	 * Constructor for this user profile
	 *
	 * @param string|Uuid $newUserId id of this user or null if a new user account
	 * @param string $newUserActivationToken activation token to safe guard against malicious accounts
	 * @param string $newUserAgent string recorded info about the browser and system to assist with blocking/reporting
	 * @param string $newUserAvatarUrl string url to the user's avatar image
	 * @param string $newUserBlocked tinyint info on the blocked status of the user
	 * @param string $newUserEmail string containing the user email
	 * @param string $newUserHandle string conatining the handle/username of the user
	 * @param string $newUserHash string containing the password hash
	 * @param string $newUserIpAddress binary, requiring some conversion of the ip address
	 * @throws \InvalidArguementException if the data types are not valid
	 * @throws \RangeException if the data values are out of bounds (e.g. strings too long, negative integers)
	 * @throws \TypeError if the data type violates the data hint
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 */
	public function __construct($newUserId, $newUserActivationToken, $newUserAgent, $newUserAvatarUrl, $newUserBlocked, $newUserEmail, $newUserHandle, $newUserHash, $newUserIpAddress) {
		try {
			$this->setUserId($newUserId);
			$this->setUserActivationToken($newUserActivationToken);
			$this->setUserAgent($newUserAgent);
			$this->setUserAvatarUrl($newUserAvatarUrl);
			$this->setUserBlocked($newUserBlocked);
			$this->setUserEmail($newUserEmail);
			$this->setUserHandle($newUserHandle);
			$this->setUserHash($newUserHash);
			$this->setUserIpAddress($newUserIpAddress);
 		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
					// determine what type of error was thrown
					$exceptionType = get_class($exception);
					throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}
	/**
	 * accessor method for user id
	 *
	 * @return string value of user id
	 */
	public function getUserId(): string {
		return($this->userId);
	}

	/**
	 * Mutator method for user Id
	 *
	 * @param string | string $newUserId new value of user id
	 * @throws \RangeException if $newUserId is not positive
	 * @throws \TypeError if the user id is not the correct type
	 */
	public function setUserId($newUserId): void {
		try{
					$uuid = self::validateUuid($newUserId);
		} catch(\InvalidArgumentException | \RangeException |\Exception | \TypeError $exception) {
					$exceptionType = get_class($exception);
					throw( new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store user Id
		$this->userId = $uuid;
	}
	/**
	 * accessor method for user activation token
	 *
	 * @return string value of the user activation token
	 */
	public function getUserActivationToken(): ?string {
			return ($this->userActivationToken);
	}
	/**
	 * @param string $newUserActivationToken
	 * @throws \InvalidArgumentException if the url is not a string or is insecure
	 * @throws \RangeException if the url is over 255 characters
	 * @throws \TypeError if the url is not a string
	 */
	public function setUserActivationToken(?string $newUserActivationToken): void {
		if($newUserActivationToken === null) {
			$this->userActivationToken = null;
			return;
		}
		$newUserActivationToken = strtolower(trim($newUserActivationToken));
		if(ctype_xdigit($newUserActivationToken) === false) {
				throw(new\InvalidArgumentException("user activation token is not valid"));
		}
		// make sure the user activation token is only 32 characters
		if(strlen($newUserActivationToken) !== 32) {
							throw (new\RangeException("user token needs to be 32 characters"));
		}
		$this->userActivationToken = $newUserActivationToken;
	}
	/**
	 * accessor method for user agent
	 *
	 * @return string value for user agent
	 */
	public function getUserAgent(): string {
				return ($this->userAgent);
	}
	/**
	 * mutator method for the user agent
	 *
	 * @param string $newUserAgent new value of the user agent
	 * @throws \InvalidArgumentException if $newUserAgent is not a string or insecure
	 * @throws \RangeException if $newUserAgent is > 255 characters
	 * @throws \TypeError if $newUserAgent is not a string
	 */
	public function setUserAgent (string $newUserAgent) : void {
		//verify the the user agent is secure
		$newUserAgent = trim($newUserAgent);
		$newUserAgent = filter_var($newUserAgent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserAgent) === true) {
					throw (new \InvalidArgumentException("user agent is empty or insecure"));
		}
		//verify the user agent will fit in the database
		if(strlen($newUserAgent) > 255) {
					throw(new \RangeException("user agent is too large"));
		}
		//store the user agent
		$this->userAgent = $newUserAgent;
	}
	/**
	 * accessor method for user avatar url
	 *
	 * @return string value of user avatar url
	 */
	public function getUserAvatarUrl(): string {
		return ($this->userAvatarUrl);
	}
	/**
	 * mutator method for the user avatar url
	 *
	 * @param string $newUserAvatarUrl new value of the user avatar
	 * @throws \InvalidArgumentException if $newUserAvatarUrl is not a string or is insecure
	 * @throws \RangeException if $newUserAvatarUrl is > 255 characters
	 * @throws \TypeError if the $newUserAvatarUrl is not a string
	 */
	public function setUserAvatarUrl (string $newUserAvatarUrl) : void {
		//verify the user Avatar url is secure
		$newAuthorAvatarUrl = trim($newUserAvatarUrl);
		$newUserAvatarUrl = filter_var($newUserAvatarUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserAvatarUrl) === true) {
					throw(new \InvalidArgumentException("user avatar url is empty or insecure"));
		}
		//verify the user avatar url is secure
		$newUserAvatarUrl = trim($newUserAvatarUrl);
		$newUserAvatarUrl = filter_var($newUserAvatarUrl, FILTER_SANITIZE_STRING, FILTER_NO_ENCODE_QUOTES);
		if(empty($newUserAvatarUrl) === true) {
					throw (new \InvalidArgumentException("user avatar url is empty or insecure"));
		}
		//store the user avatar url
		$this->userAvatarUrl = $newUserAvatarUrl;
	}
	/**
	 *
	 *
	 *
	 *
	 *
	 * userBlocked
	 *
	 *
	 *
	 *
	 */
	/**
	 * accessor method for user email
	 *
	 * @return string value for email
	 */
	public function getUserEmail(): string {
				return $this->userEmail;
	}
	/**
	 * mutator method for email
	 *
	 *@param string $newUserEmail new value of email
	 *@throws \InvalidArgumentException if $newUserEmail is not valid or is insecure
	 *@throws \RangeException if $newUserEmail is > 128 characters
	 *@throws \TypeError if $newUserEmail is not a string
	 */
	public function setUserEmail(string $newUserEmail): void {
		//verify the email is secure
		$newUserEmail = trim($newUserEmail);
		$newUserEmail = filter_var($newUserEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newUserEmail) === true) {
					throw(new \InvalidArgumentException("user email may be invalid or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newUserEmail) > 128) {
					throw(new \RangeException("user email is too large"));
		}
		//store the email
		$this->userEmail = $newUserEmail;
	}
	/**
	 * accessor method for the user handle
	 *
	 * @return string value for userHandle
	 */
	public function getUserHandle(): string {
				return ($this->userHandle);
	}
	/**
	 * mutator method for user handle
	 *
	 * @param string $newUserHandle new value of the user handle
	 * @throws \InvalidArgumentException if $newUserHandle is not a string or is insecure
	 * @throws \RangeException if $newUserHandle is > 32 characters
	 * @throws \TypeError if $newUserHandle is not a string
	 */
	public function setUserHandle(string $newUserHandle): void {
		//verify the handle is secure
		$newUserHandle = trim($newUserHandle);
		$newUserHandle = filter_var($newUserHandle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserHandle) === true) {
					throw(new \InvalidArgumentException("user handle is empty or insecure"));
		}
		//verify the author username will fit in the database
		if(strlen($newUserHandle) > 32) {
					throw(new \InvalidArgumentException("user handle is too large"));
		}
		//store the username
		$this->userHandle = $newUserHandle;
	}
	/**
	 * accessor method for user hash
	 *
	 * @return string value of hash
	 */
	public function getUserhash(): string {
				return $this->userHash;
	}

	/**
	 * mutator method for the user hash password
	 *
	 * @param string $newUserHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 97 characters
	 * @throws \TypeError if the user hash is not a string
	 */
	public function setUserHash(string $newUserHash): void {
		//enforce that the hash is properly formatted
		$newUserHash = trim($newUserHash);
		if(empty($newUserHash) === true) {
					throw(new \InvalidArgumentException("user password hash is empty or insecure"));
		}
		//enforce this hash is really an argon hash
		$userHashInfo = password_get_info($newUserHash);
		if($userHashInfo["algoname"] !== "argon2i") {
					throw(new \InvalidArgumentException("user hash must be 97 characters"));
		}
		//enforce the hash is exactly 97 characters
		if(strlen($newUserHash) !== 97) {
			throw(new \RangeException("user hash must be 97 characters"));
		}
		//store the hash
		$this->userHash = $newUserHash;
	}
	/**
	 * accessor method for the ip address
	 *
	 * @return string value for the ip address
	 */
	public function getUserIpAddress(): string {
				return ($this->userIpAddress);
	}
	/**
	 * mutator method for user Ip Address
	 *
	 * @param string $newUserIpAddress
	 * @throws \InvalidArgumentException if ip address is empty or insecure
	 * @throws \TypeError if the ip address is not a string
	 */
<<<<<<< Updated upstream
	public function setUserIpAddress(string $newUserIpAddress) {
		//detect the Ip's format and assign it in binary mode
		if(@inet_pton($newUserIpAddress) !== false) {
			$this->userIpAddress = inet_pton($newUserIpAddress);
		} else if (@inet_ntop($newUserIpAddress) !== false) {
			$this->userIpAddress = $newUserIpAddress;
		} else {
			throw(new \InvalidArgumentException("invalid user IP address"));
		}
		//store the ip address
		$this->userIpAddress =$newUserIpAddress;
	}
	/**
	 *inserts this user into mySQL
	 *
	 *@param \PDO $pdo PDO connection object
	 *@throws \PDOException when mySQL related errors occur
	 *@throws \TypeError if $pdo is not a PDO connection object
	 */
	public function insert(\PDO $pdo) : void {

		// create query template
		$query = "INSERT INTO user(userId, userActivationToken, userAgent, userAvatarUrl, userBlocked, userEmail, userHandle, userHash, userIpAddress) VALUES(:userId, :userActivationToken, :userAgent, :userAvatarUrl, :userBlocked, :userEmail, :userHandle, :userHash, :userIpAddress)";
		$statement = $pdo->prepare($query);

		//bind the member variables to the placeholders in the template
		/**
		 *
		 *double check which ones need getBytes
		 *
		 */
		$parameters = ["userId" => $this->userId->getBytes(), "userActivationToken" => $this->userActivationToken->getBytes(), "userAgent" => $this->userAgent, "userAvatarUrl" => $this->userAvatarUrl, "userBlocked" => $this->userBlocked->getBytes(), "userEmail" => $this->userEmail, "userHandle" => $this->userHandle, "userHash" => $this->userHash, "userIpAddress" => $this->userIpAddress->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * deletes this user from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function delete(\PDO $pdo): void {

		//create query template
		$query = "DELETE FROM user WHERE userId = :userId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holder in the template
		$parameters = ["userId" =>$this->userId->getBytes()];
		$statement->execute($parameters);
	}
	/**
	 * updates this user in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function update(\PDO $pdo) : void {
		//create query template
		$query = "UPDATE user SET userAgent = :userAgent, userAvatarUrl = :userAvatarUrl, userEmail = :userEmail, userHandle = :userHandle, userIpAddress = :userIpAddress WHERE userId = :userId";
		$statement = $pdo->prepare($query);

		$parameters = ["userId" => $this->userId->getBytes(), "userActivationToken" => $this->userActivationToken->getBytes(), "userAgent" => $this->userAgent, "userAvatarUrl" => $this->userAvatarUrl, "userBlocked" => $this->userBlocked->getBytes(), "userEmail" => $this->userEmail, "userHandle" => $this->userHandle, "userHash" => $this->userHash, "userIpAddress" => $this->userIpAddress->getBytes()];
		$statement->execute($parameters);
	}
}
=======
}

>>>>>>> Stashed changes
