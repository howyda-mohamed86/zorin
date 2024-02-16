<?php

namespace Tasawk\Lib;

use Tasawk\Settings\ThirdPartySettings;
use Illuminate\Support\Facades\Http;

class Firebase {

    private array $headers = [];
    private array $tokens = [];
    private array $moreData = [];
    private string $authorizationKey = '';
    private int $type = 1;
    private string $title = '';
    private string $body = '';
    private bool $isSent = false;


    public function __destruct() {
        if (!$this->isSent) {
            $this->do();
        }
    }

    static public function make() {
        return new self();
    }

    function do() {
        $response = Http::withToken($this->getAuthorizationKey())->post('https://fcm.googleapis.com/fcm/send', $this->getFields());
        $this->isSent = true;
        return $response->json();

    }

    /**
     * @param mixed $authorizationKey
     * @return Firebase
     */
    public function setAuthorizationKey($authorizationKey) {
        $this->authorizationKey = $authorizationKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorizationKey() {
        $settings = new ThirdPartySettings();
        if (!is_null($this->authorizationKey) && !trim($this->authorizationKey)) {
            $this->authorizationKey = $settings->firebase_server_key;
        }
        return $this->authorizationKey;
    }

    /**
     * @return array
     */

    /**
     * @param int $type
     * @return Firebase
     */
    public function setType(int $type): Firebase {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int {
        return $this->type;
    }

    /**
     * @param string $title
     * @return Firebase
     */
    public function setTitle(string $title): Firebase {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @param string $body
     * @return Firebase
     */
    public function setBody(string $body): Firebase {
        $this->body = $body;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string {
        return $this->body;
    }

    /**
     * @param array $tokens
     * @return Firebase
     */
    public function setTokens(array $tokens): Firebase {
        $this->tokens = $tokens;
        return $this;
    }

    /**
     * @return array
     */
    public function getTokens(): array {
        return $this->tokens;
    }

    /**
     * @param array $moreData
     * @return Firebase
     */
    public function setMoreData(array $moreData): Firebase {
        $this->moreData = $moreData;
        return $this;
    }

    public function getFields(): array {
        $fields = [
            'registration_ids' => $this->getTokens(),
            'data' => [
                'title' => $this->getTitle(),
                'body' => $this->getBody(),
            ]
//            'notification' => [
//                'title' => $this->getTitle(),
//                'body' => $this->getBody(),
//                'type' => $this->getType(),
//                'tickerText' => '',
//                'vibrate' => 1,
//                'sound' => 1,
//                'largeIcon' => 'large_icon',
//                'smallIcon' => 'small_icon',
//            ],


        ];
        if (count($this->getMoreData())) {
            $fields['data'] =[...$fields['data'], ...$this->getMoreData()];
        }
        return $fields;
    }

    public function getMoreData(): array {
        return $this->moreData;
    }

}
