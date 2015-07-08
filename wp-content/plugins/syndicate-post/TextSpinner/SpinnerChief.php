<?php

require_once 'TextSpinner.php';
require_once SYNDICATE_POST_PLUGIN_DIR . 'Preverseable.php';
/**
 * @author dkociuba
 */
class SpinnerChief implements TextSpinner, Preverseable {

    private $username;
    private $password;
    private $apikey;
    //Default values:
    private $orderly = 0;
    private $useGrammarAI = 0;
    private $usePartOfSpeech = 0; /* unavaliable in free version */
    private $protectHTML = "null";
    private $spinType = 0;
    private $notUseOglword = 0;
    private $useHurricane = 0; /* unavaliable in free version */
    private $charType = 1;
    private $convertBase = 0;
    private $oneCharForword = 0;
    private $percent = 0;
    private $spinHTML = 0; /* unavaliable in free version */
    private $protectWords = "null";
    private $tagProtect = "null";
    private $rule = 0;
    private $wordQuality = 0;
    private $spinFreq = 1;
    private $autoSpin = 1;
    private $thesaurus = 'english';
    private $wordscount = 3;
    private $querytimes = 0;
    private $replacetype = 0;
    private $phrasecount = 2;
    private $address = 'api.spinnerchief.com:443';

    function getUsername() {
        return $this->username;
    }

    function getPassword() {
        return $this->password;
    }

    function getApikey() {
        return $this->apikey;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setApikey($apikey) {
        $this->apikey = $apikey;
    }

    function getAddress() {
        return $this->address;
    }

    function setAddress($address) {
        $this->address = $address;
    }

    function getOrderly() {
        return $this->orderly;
    }

    function getUseGrammarAI() {
        return $this->useGrammarAI;
    }

    function getUsePartOfSpeech() {
        return $this->usePartOfSpeech;
    }

    function getProtectHTML() {
        return $this->protectHTML;
    }

    function getSpinType() {
        return $this->spinType;
    }

    function getNotUseOglword() {
        return $this->notUseOglword;
    }

    function getUseHurricane() {
        return $this->useHurricane;
    }

    function getCharType() {
        return $this->charType;
    }

    function getConvertBase() {
        return $this->convertBase;
    }

    function getOneCharForword() {
        return $this->oneCharForword;
    }

    function getPercent() {
        return $this->percent;
    }

    function getSpinHTML() {
        return $this->spinHTML;
    }

    function getProtectWords() {
        return $this->protectWords;
    }

    function getTagProtect() {
        return $this->tagProtect;
    }

    function getRule() {
        return $this->rule;
    }

    function getWordQuality() {
        return $this->wordQuality;
    }

    function setOrderly($orderly) {
        $this->orderly = $orderly;
    }

    function setUseGrammarAI($useGrammarAI) {
        $this->useGrammarAI = $useGrammarAI;
    }

    function setUsePartOfSpeech($usePartOfSpeech) {
        $this->usePartOfSpeech = $usePartOfSpeech;
    }

    function setProtectHTML($protectHTML) {
        $this->protectHTML = $protectHTML;
    }

    function setSpinType($spinType) {
        $this->spinType = $spinType;
    }

    function setNotUseOglword($notUseOglword) {
        $this->notUseOglword = $notUseOglword;
    }

    function setUseHurricane($useHurricane) {
        $this->useHurricane = $useHurricane;
    }

    function setCharType($charType) {
        $this->charType = $charType;
    }

    function setConvertBase($convertBase) {
        $this->convertBase = $convertBase;
    }

    function setOneCharForword($oneCharForword) {
        $this->oneCharForword = $oneCharForword;
    }

    function setPercent($percent) {
        $this->percent = $percent;
    }

    function setSpinHTML($spinHTML) {
        $this->spinHTML = $spinHTML;
    }

    function setProtectWords($protectWords) {
        $this->protectWords = $protectWords;
    }

    function setTagProtect($tagProtect) {
        $this->tagProtect = $tagProtect;
    }

    function setRule($rule) {
        $this->rule = $rule;
    }

    function setWordQuality($wordQuality) {
        $this->wordQuality = $wordQuality;
    }

    function getSpinFreq() {
        return $this->spinFreq;
    }

    function getAutoSpin() {
        return $this->autoSpin;
    }

    function getThesaurus() {
        return $this->thesaurus;
    }

    function getWordscount() {
        return $this->wordscount;
    }

    function getQuerytimes() {
        return $this->querytimes;
    }

    function getReplacetype() {
        return $this->replacetype;
    }

    function getPhrasecount() {
        return $this->phrasecount;
    }

    function setSpinFreq($spinFreq) {
        $this->spinFreq = $spinFreq;
    }

    function setAutoSpin($autoSpin) {
        $this->autoSpin = $autoSpin;
    }

    function setThesaurus($thesaurus) {
        $this->thesaurus = $thesaurus;
    }

    function setWordscount($wordscount) {
        $this->wordscount = $wordscount;
    }

    function setQuerytimes($querytimes) {
        $this->querytimes = $querytimes;
    }

    function setReplacetype($replacetype) {
        $this->replacetype = $replacetype;
    }

    function setPhrasecount($phrasecount) {
        $this->phrasecount = $phrasecount;
    }

    public function testConnection() {
        //resut of spinning emty string is empty string too. When error occur, returned string isnt empty
        return $this->spinTextToRawFormat('') === '';
    }

    public function spinText($text) {
        $rawText = $this->spinTextToRawFormat($text);
    }

    private function spinTextToRawFormat($text) {
        $this->validateParameters();
        $urlSufix = $this->prepareGetPartOfUrl();
        list($url, $port) = explode(':', $this->address);
        if (empty($port)) {
            $port = 443;
        }

        $spinnedText = $this->curl_request('http://' . $url . ':' . $port . $urlSufix, $text);

        return $spinnedText;
    }

    private function validateParameters() {
        if (empty($this->username)) {
            throw new Exception('User name is not set. Use setUsername function');
        }
        if (empty($this->password)) {
            throw new Exception('Password is not set. Use setPassword function');
        }
        if (empty($this->apikey)) {
            throw new Exception('Api key is not set. Use setApikey function');
        }
    }

    private function prepareGetPartOfUrl() {
        $urlTemplate = "/spintype=:spintype&spinfreq=:spinfreq"
                . "&autospin=:autospin&original=:original" .
                "&wordscount=:wordscount&usehurricane=:usehurricane&chartype=:chartype"
                . "&convertbase=:convertbase&onecharforword=:onecharforword" .
                "&percent=:percent&protecthtml=:protecthtml&spinhtml=:spinhtml"
                . "&orderly=:orderly&wordquality=:wordquality"
                . "&username=:username&password=:password" .
                "&apikey=:apikey"
                . "&protectwords=:protectwords&querytimes=:querytimes"
                . "&replacetype=:replacetype&pos=:pos" .
                "&UseGrammarAI=:UseGrammarAI&rule=:rule&thesaurus=:thesaurus"
                . "&phrasecount=:phrasecount&tagprotect=:tagprotect";

        $url = strtr($urlTemplate, array(
            ':spintype' => $this->spinType,
            ':spinfreq' => $this->spinFreq,
            ':autospin' => $this->autoSpin,
            ':original' => $this->notUseOglword,
            ':wordscount' => $this->wordscount,
            ':usehurricane' => $this->useHurricane,
            ':chartype' => $this->charType,
            ':convertbase' => $this->convertBase,
            ':onecharforword' => $this->oneCharForword,
            ':percent' => $this->percent,
            ':protecthtml' => $this->protectHTML,
            ':spinhtml' => $this->spinHTML,
            ':orderly' => $this->orderly,
            ':wordquality' => $this->wordQuality,
            ':username' => $this->username,
            ':password' => $this->password,
            ':apikey' => $this->apikey,
            ':protectwords' => $this->protectWords,
            ':querytimes' => $this->querytimes,
            ':replacetype' => $this->replacetype,
            ':pos' => $this->usePartOfSpeech,
            ':UseGrammarAI' => $this->useGrammarAI,
            ':rule' => $this->rule,
            ':thesaurus' => $this->thesaurus,
            ':phrasecount' => $this->phrasecount,
            ':tagprotect' => $this->tagProtect
        ));
        return $url;
    }

    private function curl_request($url, $data) {
        $req = curl_init();
        curl_setopt($req, CURLOPT_URL, $url);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($req, CURLOPT_POST, true);
        curl_setopt($req, CURLOPT_POSTFIELDS, $data);
        $result = trim(curl_exec($req));
        curl_close($req);
        return $result;
    }

    public function loadPreservedParameters() {
        $params = get_option('syndicate_post_spinner_parameters');

        $this->spinType = $params['spintype'];
        $this->spinFreq = $params['spinfreq'];
        $this->autoSpin = $params['autospin'];
        $this->notUseOglword = $params['original'];
        $this->wordscount = $params['wordscount'];
        $this->useHurricane = $params['usehurricane'];
        $this->charType = $params['chartype'];
        $this->convertBase = $params['convertbase'];
        $this->oneCharForword = $params['onecharforword'];
        $this->percent = $params['percent'];
        $this->protectHTML = $params['protecthtml'];
        $this->spinHTML = $params['spinhtml'];
        $this->orderly = $params['orderly'];
        $this->wordQuality = $params['wordquality'];
        $this->username = $params['username'];
        $this->password = $params['password'];
        $this->apikey = $params['apikey'];
        $this->protectWords = $params['protectwords'];
        $this->querytimes = $params['querytimes'];
        $this->replacetype = $params['replacetype'];
        $this->usePartOfSpeech = $params['pos'];
        $this->useGrammarAI = $params['UseGrammarAI'];
        $this->rule = $params['rule'];
        $this->thesaurus = $params['thesaurus'];
        $this->phrasecount = $params['phrasecount'];
        $this->tagProtect = $params['tagprotect'];
    }

    public function preserveParameters() {
        $params = array(
            'spintype' => $this->spinType,
            'spinfreq' => $this->spinFreq,
            'autospin' => $this->autoSpin,
            'original' => $this->notUseOglword,
            'wordscount' => $this->wordscount,
            'usehurricane' => $this->useHurricane,
            'chartype' => $this->charType,
            'convertbase' => $this->convertBase,
            'onecharforword' => $this->oneCharForword,
            'percent' => $this->percent,
            'protecthtml' => $this->protectHTML,
            'spinhtml' => $this->spinHTML,
            'orderly' => $this->orderly,
            'wordquality' => $this->wordQuality,
            'username' => $this->username,
            'password' => $this->password,
            'apikey' => $this->apikey,
            'protectwords' => $this->protectWords,
            'querytimes' => $this->querytimes,
            'replacetype' => $this->replacetype,
            'pos' => $this->usePartOfSpeech,
            'UseGrammarAI' => $this->useGrammarAI,
            'rule' => $this->rule,
            'thesaurus' => $this->thesaurus,
            'phrasecount' => $this->phrasecount,
            'tagprotect' => $this->tagProtect
        );
        update_option('syndicate_post_spinner_parameters', $params);
    }

}
