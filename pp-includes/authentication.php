<?php
class Login
{
	private $user_id = null;
	private $user_name = "";
	private $user_email = "";
	public $user_gravatar_image_url = "";
	public $user_gravatar_image_tag = "";
	public $errors = array();
	public $messages = array();
	private $user_is_logged_in = false;

	public function __construct()
	{
		// check if session has started
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		if (isset($_GET["logout"])) {
			$this->doLogout();
		} elseif (!empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)) {
			$this->continueWithSession();

			if (isset($_POST['user_change_password'])) {
				$this->updatePassword($_POST['user_old_password'], $_POST['user_new_password'], $_POST['user_new_password_repeat']);
			} elseif (isset($_POST['user_change_name'])) {
				$this->updateName($_POST['user_new_fname'], $_POST['user_new_lname']);
			} elseif (isset($_POST['user_change_email'])) {
				$this->updateEmail($_POST['user_new_email']);
			}

		} elseif (isset($_COOKIE['rememberme'])) {
			$this->loginCookie();

		} elseif (isset($_POST["login"])) {
			if (!isset($_POST['user_rememberme'])) {
				$_POST['user_rememberme'] = null;
			}
			$this->loginNow($_POST['user_name'], $_POST['user_password'], $_POST['user_rememberme']);
		}
	}

	public function isUserLoggedIn()
	{
		return $this->user_is_logged_in;
	}
}


/*
 * Generate a secure hash for a given password. The cost is passed
 * to the blowfish algorithm. Check the PHP manual page for crypt to
 * find more information about this setting.
 */
function generate_hash($password, $cost=11){
        /* To generate the salt, first generate enough random bytes. Because
         * base64 returns one character for each 6 bits, the we should generate
         * at least 22*6/8=16.5 bytes, so we generate 17. Then we get the first
         * 22 base64 characters
         */
       	$salt=substr(base64_encode(openssl_random_pseudo_bytes(17)),0,22);
        /* As blowfish takes a salt with the alphabet ./A-Za-z0-9 we have to
         * replace any '+' in the base64 string with '.'. We don't have to do
         * anything about the '=', as this only occurs when the b64 string is
         * padded, which is always after the first 22 characters.
         */
        $salt=str_replace("+",".",$salt);
        /* Next, create a string that will be passed to crypt, containing all
         * of the settings, separated by dollar signs
         */
        $param='$'.implode('$',array(
                "2y", //select the most secure version of blowfish (>=PHP 5.3.7)
                str_pad($cost,2,"0",STR_PAD_LEFT), //add the cost in two digits
                $salt //add the salt
        ));
       
        //now do the actual hashing
        return crypt($password,$param);
}
 
/*
 * Check the password against a hash generated by the generate_hash
 * function.
 */
function validate_pw($password, $hash){
        /* Regenerating the with an available hash as the options parameter should
         * produce the same hash if the same password is passed.
         */
        return crypt($password, $hash)==$hash;
}
?>