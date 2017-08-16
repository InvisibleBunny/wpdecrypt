<?php
require_once("PasswordHash.php");

Class WP_Decrypt {
	private $WP_HASHER;
	public $wordlist;
	public $HASH;

	public function __construct() {
		$explode = (strtoupper(substr(PHP_OS, 0, 3)) === "WIN") ? "\r\n" : "\n";
		$this->wordlist = file_get_contents("wordlist.txt");
		$this->wordlist = explode($explode, $this->wordlist);

		$this->WP_HASHER = new PasswordHash(8, TRUE);

		$this->run();
	}

	public function input() {
		$handle = fopen("php://stdin", "r");
		$fgets  = fgets($handle);
		$fgets  = str_replace(["\n","\r"], "", $fgets);
		fclose($handle);
		return $fgets;
	}

	public function decrypt() {
		$result = array();
		foreach($this->wordlist as $list) {
			if($this->WP_HASHER->CheckPassword($list, $this->HASH)) {
				$result[] = $list;
			}
		}
		$output = implode("", $result);
		print (empty($output) ? "[!] ".$this->HASH." -> Not Found!\n" : "[!] ".$this->HASH." -> $output\n");
	}

	public function run() {
		print "[*] HASH: ";
		$this->HASH = $this->input();
		print $this->decrypt();
	}
}

$wp = new WP_Decrypt;
?>